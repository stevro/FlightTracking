<?php

namespace Stevro\FlightTracking\Interfaces;

use Stevro\FlightTracking\Model\FlightStatus;

interface FlightStatusServiceInterface
{
    public function getStatus($flightNumber, \DateTime $flightDate, array $options = []): FlightStatus;
}