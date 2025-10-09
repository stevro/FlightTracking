<?php

namespace Stevro\FlightTracking\FlightRadar24\Model;

use JMS\Serializer\Annotation as JMS;


class FlightPosition
{

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("fr24_id")
     */
    public $fr24Id;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $flight;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $callsign;
    /**
     * @var float
     * @JMS\Type("float")
     */
    public $lat;
    /**
     * @var float
     * @JMS\Type("float")
     */
    public $lon;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $track;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $alt;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $gspeed;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $vspeed;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $squawk;
    /**
     * @var \DateTimeImmutable
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $timestamp;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $source;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $hex;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $type;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $reg;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("painted_as")
     */
    public $paintedAs;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("operating_as")
     */
    public $operatingAs;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("orig_iata")
     */
    public $origIata;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("orig_icao")
     */
    public $origIcao;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_iata")
     */
    public $destIata;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_icao")
     */
    public $destIcao;
    /**
     * @var \DateTimeImmutable
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     */
    public $eta;

    /*
     * "fr24_id": "321a0cc3",
    "flight": "AF1463",
    "callsign": "AFR1463",
    "lat": -0.08806,
    "lon": -168.07118,
    "track": 219,
    "alt": 38000,
    "gspeed": 500,
    "vspeed": 340,
    "squawk": 6135,
    "timestamp": "2023-11-08T10:10:00Z",
    "source": "ADSB",
    "hex": "394C19",
    "type": "A321",
    "reg": "F-GTAZ",
    "painted_as": "THY",
    "operating_as": "THY",
    "orig_iata": "ARN",
    "orig_icao": "ESSA",
    "dest_iata": "LHR",
    "dest_icao": "EGLL",
    "eta": "2023-11-08T16:12:24Z"
     */
}