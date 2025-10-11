<?php

namespace Stevro\FlightTracking\Tests\Provider\FlightRadar24;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Stevro\FlightTracking\Model\FlightStatus;
use Stevro\FlightTracking\Provider\FlightRadar24\Service\FlightStatusService;

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

        // Mock API response for flight summary (landed flight)
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA123',
                    'fr24_id' => 'fr24-12345',
                    'orig_iata' => 'LHR',
                    'dest_iata' => 'JFK',
                    'dest_iata_actual' => 'JFK',
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T18:30:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom);

        // Assert
        $this->assertEquals('BA123', $status->flightNumber);
        $this->assertEquals('LHR', $status->originIata);
        $this->assertEquals('JFK', $status->destinationIata);
        $this->assertEquals('fr24-12345', $status->flightId);
        $this->assertEquals(FlightStatus::STATUS_LANDED, $status->status);
        $this->assertNotNull($status->landedAt);
        $this->assertNull($status->eta);
    }

    public function testGetStatusForInFlightFlight()
    {
        // Prepare test data
        $flightNumber = 'BA456';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');


        // Mock API response for flight summary (in-flight)
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA456',
                    'fr24_id' => 'fr24-67890',
                    'orig_iata' => 'LHR',
                    'dest_iata' => 'CDG',
                    'dest_iata_actual' => null,
                    'datetime_takeoff' => '2023-10-01T10:02:00',
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
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom);

        // Assert
        $this->assertEquals('BA456', $status->flightNumber);
        $this->assertEquals('LHR', $status->originIata);
        $this->assertEquals('CDG', $status->destinationIata);
        $this->assertEquals('fr24-67890', $status->flightId);
        $this->assertEquals(FlightStatus::STATUS_DEPARTED, $status->status);
        $this->assertNull($status->landedAt);
        $this->assertNotNull($status->eta);
    }

    public function testGetStatusUsesActualDestinationWhenAvailable()
    {
        // Prepare test data
        $flightNumber = 'BA789';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');

        // Mock API response with different actual destination
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA789',
                    'fr24_id' => 'fr24-11111',
                    'orig_iata' => 'LHR',
                    'dest_iata' => 'CDG',
                    'dest_iata_actual' => 'ORY',
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T15:00:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom);

        // Assert - should use actual destination
        $this->assertEquals('ORY', $status->destinationIata);
    }

    public function testGetStatusUsesFallbackDestinationWhenActualIsNull()
    {
        // Prepare test data
        $flightNumber = 'BA999';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');


        // Mock API response without actual destination
        $summaryResponseData = [
            'data' => [
                [
                    'flight' => 'BA999',
                    'fr24_id' => 'fr24-22222',
                    'orig_iata' => 'LHR',
                    'dest_iata' => 'FRA',
                    'dest_iata_actual' => null,
                    'flight_ended' => true,
                    'datetime_landed' => '2023-10-01T16:00:00',
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($summaryResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom);

        // Assert - should use fallback destination
        $this->assertEquals('FRA', $status->destinationIata);
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
