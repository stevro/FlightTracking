<?php

namespace Stevro\FlightTracking\Model;

class FlightStatus
{

    /** @var string */
    public $flightId;
    /** @var string */
    public $flightNumber;
    public $isLanded = false;
    /** @var \DateTimeInterface|null */
    public $landedAt;
    /** @var \DateTimeInterface|null */
    public $eta;
    /**
     * @var string
     */
    public $originIcao;

    /**
     * @var string
     */
    public $destinationIcao;

}