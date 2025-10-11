<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class Airport
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
     * @var string
     * @JMS\Type("string")
     */
    public $city;
    /**
     * @var string
     * @JMS\Type("string")
     *
     */
    public $cityCode;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $countryCode;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $countryName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $regionName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $timeZoneRegionName;
    /**
     * @var \DateTimeInterface
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $localTime;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $utcOffsetHours;
    /**
     * @var float
     * @JMS\Type("float")
     */
    public $latitude;
    /**
     * @var float
     * @JMS\Type("float")
     */
    public $longitude;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $elevationFeet;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $classification;
    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    public $active;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $delayIndexUrl;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $weatherUrl;


}