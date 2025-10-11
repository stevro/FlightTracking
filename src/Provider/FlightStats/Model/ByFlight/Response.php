<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Response
{

    /**
     * @var Appendix
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Appendix")
     */
    public $appendix;

    /**
     * @var array<FlightStatus>
     * @JMS\SerializedName("flightStatuses")
     * @JMS\Type("array<Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\FlightStatus>")
     */
    public $flightStatuses = [];

    public function addFlightStatus(FlightStatus $flightStatus)
    {
        $this->flightStatuses[] = $flightStatus;
    }

}