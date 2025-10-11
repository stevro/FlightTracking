<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Airline
{

    /**
     * @var string
     * @JMS\Type("string")
     */
    public $fs;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $iata;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $icao;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $name;
    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    public $active;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $category;


}