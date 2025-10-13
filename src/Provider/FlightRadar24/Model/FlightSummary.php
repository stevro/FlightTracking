<?php

namespace Stevro\FlightTracking\Provider\FlightRadar24\Model;

use JMS\Serializer\Annotation as JMS;

class FlightSummary
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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("operating_as")
     */
    public $operatingAs;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("painted_as")
     */
    public $paintedAs;
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
     * @JMS\SerializedName("orig_icao")
     */
    public $origIcao;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("orig_iata")
     */
    public $origIata;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_icao")
     */
    public $destIcao;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_iata")
     */
    public $destIata;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_icao_actual")
     */
    public $destIcaoActual;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_iata_actual")
     */
    public $destIataActual;

    /**
     * @var \DateTimeImmutable
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     * @JMS\SerializedName("datetime_takeoff")
     */
    public $dateTimeTakeoff;
    /**
     * @var \DateTimeImmutable|null
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     * @JMS\SerializedName("datetime_landed")
     */
    public $dateTimeLanded;
    /**
     * @var \DateTimeImmutable|null
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
     * @JMS\SerializedName("first_seen")
     */
    public $firstSeen;
    /**
     * @var \DateTimeImmutable|null
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:sZ'>")
     * @JMS\SerializedName("last_seen")
     */
    public $lastSeen;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public $hex;

    /**
     * @var bool
     * @JMS\Type("boolean")
     * @JMS\SerializedName("flight_ended")
     */
    public $flightEnded;

}