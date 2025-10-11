<?php

namespace Stevro\FlightTracking\Model;


class FlightStatus
{

    const STATUS_LANDED = 'LANDED';
    const STATUS_SCHEDULED = 'SCHEDULED';
    const STATUS_DEPARTED = 'DEPARTED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_DIVERTED = 'DIVERTED';
    const STATUS_UNKNOWN = 'UNKNOWN';

    /** @var string */
    public $flightId;
    /** @var string */
    public $flightNumber;

    /** @var \DateTimeInterface|null */
    public $departedAt;

    /** @var \DateTimeInterface|null */
    public $landedAt;

    /** @var \DateTimeInterface|null */
    public $eta;

    public $status = self::STATUS_UNKNOWN;

    /** @var string */
    public $originIata;

    /** @var string */
    public $destinationIata;

    /**
     * @var Delays
     */
    public $delays;

}