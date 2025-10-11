<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class FlightDurations
{

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $scheduledBlockMinutes;

}