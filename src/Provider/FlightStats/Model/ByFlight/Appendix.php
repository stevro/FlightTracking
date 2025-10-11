<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Appendix
{
    /**
     * @var array
     * @JMS\Type("array<Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Airline>")
     */
    public $airlines = [];

    /**
     * @var array
     * @JMS\Type("array<Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Airport>")
     */
    public $airports = [];
}