<?php

namespace Stevro\FlightTracking\Provider\FlightRadar24\Service;

use Stevro\FlightTracking\Model\FlightStatus;
use Stevro\FlightTracking\Provider\FlightRadar24\API\FlightPositionsAPI;
use Stevro\FlightTracking\Provider\FlightRadar24\API\FlightSummaryAPI;
use Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightPosition;
use Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightSummary;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightStatusService implements \Stevro\FlightTracking\Interfaces\FlightStatusServiceInterface
{

    private $flightSummaryAPI;

    private $flightPositionAPI;

    private $options = [];

    public function __construct($apiKey)
    {
        $this->flightSummaryAPI = new FlightSummaryAPI($apiKey);
        $this->flightPositionAPI = new FlightPositionsAPI($apiKey);
    }

    public function getStatus($flightNumber, \DateTime $flightDate, array $options = []): FlightStatus
    {
        $this->resolveOptions($options);

        $flightDateTimeFrom = (clone $flightDate)->setTime(0, 0, 0);
        $flightDateTimeTo = (clone $flightDate)->setTime(23, 59, 59);

        if(isset($this->options['carrier']) && !empty($this->options['carrier'])) {
            $flightNumber = $this->options['carrier'] . $flightNumber;
        }

        $summaryData = $this->flightSummaryAPI->light([$flightNumber], null, $flightDateTimeFrom, $flightDateTimeTo);

        /** @var FlightSummary $myFlight */
        $myFlight = reset($summaryData);

        $status = new FlightStatus('FlightRadar24');
        if (!$myFlight) {
            $status->flightNumber = $flightNumber;
            $status->status = FlightStatus::STATUS_UNKNOWN;

            return $status;
        }

        $status->flightNumber = $myFlight->flight;
        $status->destinationIata = $myFlight->destIataActual ?: $myFlight->destIata;
        $status->originIata = $myFlight->origIata;
        $status->flightId = $myFlight->fr24Id;
        $status->departedAt = $myFlight->dateTimeTakeoff;

        if (true === $myFlight->flightEnded) {
            $status->status = FlightStatus::STATUS_LANDED;
            $status->landedAt = $myFlight->dateTimeLanded;

            return $status;
        }

        $positionData = $this->flightPositionAPI->full(null, [$myFlight->fr24Id]);

        /** @var FlightPosition $myPosition */
        $myPosition = reset($positionData);

        $status->status = $myFlight->dateTimeTakeoff ? FlightStatus::STATUS_DEPARTED : FLightStatus::STATUS_SCHEDULED;
        $status->eta = $myPosition->eta;

        return $status;
    }

    protected function resolveOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['carrier']);

        $resolver->setAllowedTypes('carrier', 'string');
    }

}