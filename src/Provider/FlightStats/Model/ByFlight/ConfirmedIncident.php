<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class ConfirmedIncident
{
    /**
     * @var \DateTimeInterface
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $publishedDate;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public $message;
}