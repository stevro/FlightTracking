<?php

namespace Stevro\FlightTracking\FlightRadar24\API;

use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class FlightSummaryAPI extends BaseAPI
{
    /**
     * @return array<\Stevro\FlightTracking\FlightRadar24\Model\FlightSummary>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function light(array $flights = null, array $ids = null, \DateTime $flightDateTimeFrom = null, \DateTime $flightDateTimeTo = null): array
    {
        $response = $this->doExecute('flight-summary/light', [
            'query' => [
                'flights' => $flights ? implode(',', $flights) : null,
                'flight_ids' => $ids ? implode(',', $ids) : null,
                'flight_date_time_from' => $flightDateTimeFrom ? $flightDateTimeFrom->format('Y-m-dTH:i:s') : null,
                'flight_date_time_to' => $flightDateTimeTo ? $flightDateTimeTo->format('Y-m-dTH:i:s') : null,
            ],
        ]);

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\FlightRadar24\Model\FlightSummary>'
        );
    }

    /**
     * @return array<\Stevro\FlightTracking\FlightRadar24\Model\FlightSummary>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function full(array $flights = [], array $ids = [], \DateTime $flightDateTimeFrom = null, \DateTime $flightDateTimeTo = null): array
    {
        $response = $this->doExecute('flight-summary/full', [
            'query' => [
                'flights' => $flights ? implode(',', $flights) : null,
                'flight_ids' => $ids ? implode(',', $ids) : null,
                'flight_date_time_from' => $flightDateTimeFrom ? $flightDateTimeFrom->format('Y-m-dTH:i:s') : null,
                'flight_date_time_to' => $flightDateTimeTo ? $flightDateTimeTo->format('Y-m-dTH:i:s') : null,
            ],
        ]);

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\FlightRadar24\Model\FlightSummary>'
        );
    }


}