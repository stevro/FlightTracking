<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Service;

use Stevro\FlightTracking\Interfaces\FlightStatusServiceInterface;
use Stevro\FlightTracking\Model\Delays;
use Stevro\FlightTracking\Model\FlightStatus;
use Stevro\FlightTracking\Provider\FlightStats\API\FlightStatusByFlightAPI;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightStatusService implements FlightStatusServiceInterface
{

    /**
     * @var FlightStatusByFlightAPI
     */
    private $api;
    /**
     * @var array
     */
    private $options = [];

    public function __construct(string $appId, string $apiKey)
    {
        $this->api = new FlightStatusByFlightAPI($appId, $apiKey);
    }

    public function getStatus($flightNumber, \DateTime $flightDate, array $options = []): FlightStatus
    {
        $this->resolveOptions($options);

        $response = $this->api->getByArrivalDate($flightNumber, $this->options['carrier'], $flightDate, $this->options['queryParams']);

        $flightStatus = new FlightStatus();

        if (!$response->flightStatuses) {
            $flightStatus->flightNumber = $flightNumber;
            $flightStatus->status = FlightStatus::STATUS_UNKNOWN;

            return $flightStatus;
        }

        /** @var \Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\FlightStatus $responseStatus */
        $responseStatus = reset($response->flightStatuses);

        $flightStatus->flightId = $responseStatus->flightId;
        $flightStatus->flightNumber = $responseStatus->flightNumber;
        $flightStatus->eta = $responseStatus->operationalTimes->estimatedGateArrival->dateLocal;
        if ($responseStatus->delays) {
            $flightStatus->delays = new Delays();
            $flightStatus->delays->departureDelayMinutes = $responseStatus->delays->departureGateDelayMinutes;
            $flightStatus->delays->arrivalDelayMinutes = $responseStatus->delays->arrivalGateDelayMinutes;
        }
        $flightStatus->destinationIata = $responseStatus->divertedAirport->iata ?? $responseStatus->arrivalAirportFsCode;
        $flightStatus->originIata = $responseStatus->departureAirportFsCode;

        switch ($responseStatus->status) {
            case 'S':
                $flightStatus->status = FlightStatus::STATUS_SCHEDULED;
                break;
            case 'A':
                $flightStatus->status = FlightStatus::STATUS_DEPARTED;
                $flightStatus->departedAt = $responseStatus->operationalTimes->scheduledGateDeparture->dateLocal;
                break;
            case 'C':
            case 'NO':
                $flightStatus->status = FlightStatus::STATUS_CANCELLED;
                break;
            case 'D':
                $flightStatus->status = FlightStatus::STATUS_DIVERTED;
                break;
            case 'L':
                $flightStatus->status = FlightStatus::STATUS_LANDED;
                $flightStatus->landedAt = $responseStatus->operationalTimes->estimatedGateArrival->dateLocal;
                break;
            case 'DN':
            case 'U':
                $flightStatus->status = FlightStatus::STATUS_UNKNOWN;
                break;
        }

        return $flightStatus;
    }

    protected function resolveOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['carrier', 'queryParams']);

        $resolver->setRequired('carrier')->setAllowedTypes('carrier', 'string');

        $resolver->setDefaults([
            'queryParams' => [],
        ]);

        $resolver->setAllowedTypes('queryParams', 'array');
    }
}