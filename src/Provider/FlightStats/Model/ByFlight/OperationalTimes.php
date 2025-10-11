<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class OperationalTimes
{
    /**
     * @var Date
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Date")
     */
    public $scheduledGateDeparture;
    /**
     * @var Date
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Date")
     */
    public $scheduledGateArrival;
    /**
     * @var Date
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Date")
     */
    public $estimatedGateArrival;
}