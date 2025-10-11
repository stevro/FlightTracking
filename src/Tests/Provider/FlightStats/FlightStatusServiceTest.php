<?php

namespace Stevro\FlightTracking\Tests\Provider\FlightStats;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Stevro\FlightTracking\Model\FlightStatus;
use Stevro\FlightTracking\Provider\FlightStats\Service\FlightStatusService;

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

    public function testGetStatusForInFlightFlight()
    {
        $flightNumber = 'BA456';
        $flightDateTimeFrom = new \DateTime('2023-10-01 10:00:00');

        // Mock API response for flight status
        $apiResponseData = [
            'appendix' => [],
            'flightStatuses' => [
                [
                    'flightNumber' => 'BA456',
                    'flightId' => 'BA-67890',
                    'carrierFsCode' => 'FR',
                    'departureAirportFsCode' => 'LHR',
                    'arrivalAirportFsCode' => 'JFK',
                    'status' => 'A',
                    'operationalTimes' => [
                        'scheduledGateDeparture' => [
                            'dateLocal' => '2023-10-01T10:00:00',
                            'dateUtc' => '2023-10-01T10:00:00Z',
                        ],
                        'scheduledGateArrival' => [
                            'dateLocal' => '2023-10-01T14:00:00',
                            'dateUtc' => '2023-10-01T14:00:00Z',
                        ],
                        'estimatedGateArrival' => [
                            'dateLocal' => '2023-10-01T14:00:00',
                            'dateUtc' => '2023-10-01T14:00:00Z',
                        ],
                    ],
                ],
            ],
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($apiResponseData))
        );

        // Execute
        $status = $this->service->getStatus($flightNumber, $flightDateTimeFrom, ['carrier' => 'FR']);

        // Assert
        $this->assertEquals('BA456', $status->flightNumber);
        $this->assertEquals('LHR', $status->originIata);
        $this->assertEquals('JFK', $status->destinationIata);
        $this->assertEquals('BA-67890', $status->flightId);
        $this->assertEquals(FlightStatus::STATUS_DEPARTED, $status->status);
        $this->assertNull($status->landedAt);
        $this->assertNotNull($status->eta);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->service = new FlightStatusService('test', '1231234123');

        // Inject Guzzle client into APIs using reflection
        $reflectionService = new \ReflectionClass($this->service);

        $apiProperty = $reflectionService->getProperty('api');
        $apiProperty->setAccessible(true);
        $api = $apiProperty->getValue($this->service);

        // Inject client into each API
        $this->injectClientIntoAPI($api, $client);
    }

    private function injectClientIntoAPI($api, Client $client)
    {
        $reflectionAPI = new \ReflectionClass($api);
        $clientProperty = $reflectionAPI->getProperty('httpClient');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($api, $client);
    }

}