<?php

namespace Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight;

use JMS\Serializer\Annotation as JMS;

class FlightStatus
{

    /**
     * @var string
     * @JMS\Type("string")
     */
    public $flightId;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $carrierFsCode;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $flightNumber;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $departureAirportFsCode;
    /**
     * @var string
     * @JMS\Type("string")
     */
    public $arrivalAirportFsCode;

    /**
     * @var Airport | null
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Airport")
     */
    public $divertedAirport;

    /**
     * @var Date
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Date")
     */
    public $departureDate;
    /**
     * @var Date
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Date")
     */
    public $arrivalDate;

    /**
     * @var Delays|null
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Delays")
     */
    public $delays;

    /**
     * @var string
     * @JMS\Type("string")
     *
     */
    public $status;
    /**
     * @var OperationalTimes
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\OperationalTimes")
     */
    public $operationalTimes;
    /**
     * @var FlightDurations
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\FlightDurations")
     */
    public $flightDurations;
    /**
     * @var array<IregularOperation>
     * @JMS\Type("array<Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\IregularOperation>")
     */
    public $irregularOperations = [];
    /**
     * @var ConfirmedIncident
     * @JMS\Type("Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\ConfirmedIncident")
     */
    public $confirmedIncident;
}

