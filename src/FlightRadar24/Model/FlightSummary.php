<?php

namespace Stevro\FlightTracking\FlightRadar24\Model;

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
     * @JMS\SerializedName("dest_icao")
     */
    public $destIcao;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dest_icao_actual")
     */
    public $destIcaoActual;

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
     * @JMS\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")
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

//{
//"fr24_id": "391fdd79",
//"flight": "D84529",
//"callsign": "NSZ4529",
//"operating_as": "NSZ",
//"painted_as": "NSZ",
//"type": "B38M",
//"reg": "SE-RTC",
//"orig_icao": "ESSA",
//"datetime_takeoff": null,
//"dest_icao": "GMAD",
//"dest_icao_actual": "GMAD",
//"datetime_landed": null,
//"hex": "4ACA83",
//"first_seen": "2025-02-14T11:47:06Z",
//"last_seen": "2025-02-14T13:11:49Z",
//"flight_ended": true
//}
}