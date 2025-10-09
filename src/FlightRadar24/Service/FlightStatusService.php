<?php

namespace Stevro\FlightTracking\FlightRadar24\Service;

use Stevro\FlightTracking\FlightRadar24\API\FlightPositionsAPI;
use Stevro\FlightTracking\FlightRadar24\API\FlightSummaryAPI;
use Stevro\FlightTracking\FlightRadar24\Model\FlightPosition;
use Stevro\FlightTracking\FlightRadar24\Model\FlightSummary;
use Stevro\FlightTracking\Model\FlightStatus;

class FlightStatusService
{

    private $flightSummaryAPI;

    private $flightPositionAPI;

    public function __construct($apiKey)
    {
        $this->flightSummaryAPI = new FlightSummaryAPI($apiKey);
        $this->flightPositionAPI = new FlightPositionsAPI($apiKey);
    }

    public function getStatus($flightNumber, \DateTime $flightDateTimeFrom, \DateTime $flightDateTimeTo): FlightStatus
    {
        $summaryData = $this->flightSummaryAPI->light([$flightNumber], null, $flightDateTimeFrom, $flightDateTimeTo);

        /** @var FlightSummary $myFlight */
        $myFlight = reset($summaryData);

        $status = new FlightStatus();
        $status->flightNumber = $myFlight->flight;
        $status->destinationIcao = $myFlight->destIcaoActual ?: $myFlight->destIcao;
        $status->originIcao = $myFlight->origIcao;
        $status->flightId = $myFlight->fr24Id;

        if (true === $myFlight->flightEnded) {
            $status->isLanded = true;
            $status->landedAt = $myFlight->dateTimeLanded;

            return $status;
        }

        $positionData = $this->flightPositionAPI->light(null, [$myFlight->fr24Id]);

        /** @var FlightPosition $myPosition */
        $myPosition = reset($positionData);

        $status->isLanded = false;
        $status->eta = $myPosition->eta;

        return $status;
    }

}