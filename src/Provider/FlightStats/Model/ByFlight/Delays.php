<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Delays
{

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $departureGateDelayMinutes;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $departureRunwayDelayMinutes;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $arrivalGateDelayMinutes;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $arrivalRunwayDelayMinutes;

}