<?php

namespace Stevro\FlightTracking\Tests\FlightRadar24;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Stevro\FlightTracking\FlightRadar24\Service\FlightStatusService;

class FlightStatusServiceTest extends TestCase
{
    /**
     * @var FlightStatusService
     */
    private $service;

    /**
     * @var MockHandler
     */
    private $mockHandler;

    public function testGetStatusForLandedFlight()
    {
        // Prepare test data
        $flightNumber = 'BA123';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');
        $flightDateTimeTo = new \DateTime('2023-10-01 20:00:00');

        // Mock API response for flight summary (landed flight)
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA123',
                    'fr24_id' => 'fr24-12345',
                    'orig_icao' => 'EGLL',
                    'dest_icao' => 'KJFK',
                    'dest_icao_actual' => 'KJFK',
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T18:30:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom, $flightDateTimeTo);

        // Assert
        $this->assertEquals('BA123', $status->flightNumber);
        $this->assertEquals('EGLL', $status->originIcao);
        $this->assertEquals('KJFK', $status->destinationIcao);
        $this->assertEquals('fr24-12345', $status->flightId);
        $this->assertTrue($status->isLanded);
        $this->assertNotNull($status->landedAt);
        $this->assertNull($status->eta);
    }

    public function testGetStatusForInFlightFlight()
    {
        // Prepare test data
        $flightNumber = 'BA456';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');
        $flightDateTimeTo = new \DateTime('2023-10-01 20:00:00');

        // Mock API response for flight summary (in-flight)
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA456',
                    'fr24_id' => 'fr24-67890',
                    'orig_icao' => 'EGLL',
                    'dest_icao' => 'LFPG',
                    'dest_icao_actual' => null,
                    'flight_ended' => false,
                ],
            ],
        ];

        // Mock API response for flight position
        $positionResponseData = [
            'data' => [
                [
                    'eta' => '2023-10-01T14:30:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData)),
            new Response(200, [], json_encode($positionResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom, $flightDateTimeTo);

        // Assert
        $this->assertEquals('BA456', $status->flightNumber);
        $this->assertEquals('EGLL', $status->originIcao);
        $this->assertEquals('LFPG', $status->destinationIcao);
        $this->assertEquals('fr24-67890', $status->flightId);
        $this->assertFalse($status->isLanded);
        $this->assertNull($status->landedAt);
        $this->assertNotNull($status->eta);
    }

    public function testGetStatusUsesActualDestinationWhenAvailable()
    {
        // Prepare test data
        $flightNumber = 'BA789';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');
        $flightDateTimeTo = new \DateTime('2023-10-01 20:00:00');

        // Mock API response with different actual destination
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA789',
                    'fr24_id' => 'fr24-11111',
                    'orig_icao' => 'EGLL',
                    'dest_icao' => 'LFPG',
                    'dest_icao_actual' => 'LFPO',
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T15:00:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom, $flightDateTimeTo);

        // Assert - should use actual destination
        $this->assertEquals('LFPO', $status->destinationIcao);
    }

    public function testGetStatusUsesFallbackDestinationWhenActualIsNull()
    {
        // Prepare test data
        $flightNumber = 'BA999';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');
        $flightDateTimeTo = new \DateTime('2023-10-01 20:00:00');

        // Mock API response without actual destination
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA999',
                    'fr24_id' => 'fr24-22222',
                    'orig_icao' => 'EGLL',
                    'dest_icao' => 'EDDF',
                    'dest_icao_actual' => null,
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T16:00:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom, $flightDateTimeTo);

        // Assert - should use fallback destination
        $this->assertEquals('EDDF', $status->destinationIcao);
    }

    protected function setUp()
    {
        parent::setUp();

        // Create a mock handler for Guzzle
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Create service
        $this->service = new FlightStatusService('test-api-key');

        // Inject Guzzle client into APIs using reflection
        $reflectionService = new \ReflectionClass($this->service);

        $summaryProperty = $reflectionService->getProperty('flightSummaryAPI');
        $summaryProperty->setAccessible(true);
        $flightSummaryAPI = $summaryProperty->getValue($this->service);

        $positionProperty = $reflectionService->getProperty('flightPositionAPI');
        $positionProperty->setAccessible(true);
        $flightPositionAPI = $positionProperty->getValue($this->service);

        // Inject client into each API
        $this->injectClientIntoAPI($flightSummaryAPI, $client);
        $this->injectClientIntoAPI($flightPositionAPI, $client);
    }

    private function injectClientIntoAPI($api, Client $client)
    {
        $reflectionAPI = new \ReflectionClass($api);
        $clientProperty = $reflectionAPI->getProperty('httpClient');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($api, $client);
    }
}
