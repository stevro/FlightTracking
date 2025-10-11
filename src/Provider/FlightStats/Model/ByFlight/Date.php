<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Date
{

    /**
     * @var \DateTimeInterface
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $dateLocal;
    /**
     * @var \DateTimeInterface
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sZ'>")
     */
    public $dateUtc;

}