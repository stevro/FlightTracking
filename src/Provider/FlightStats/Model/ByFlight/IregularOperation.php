<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class IregularOperation
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $type;

    /**
     * @var string|null
     * @JMS\Type("string")
     */
    public $relatedFlightId;

    /**
     * @var \DateTimeInterface
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $dateUtc;
}