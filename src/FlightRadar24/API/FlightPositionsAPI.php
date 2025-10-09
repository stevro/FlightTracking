<?php

namespace Stevro\FlightTracking\FlightRadar24\API;

use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class FlightPositionsAPI extends BaseAPI
{

    /**
     * @return array<\Stevro\FlightTracking\FlightRadar24\Model\FlightPosition>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function full(array $flights = null, array $ids = null): array
    {
        $response = $this->doExecute('live/flight-positions/full', [
            'query' => [
                'flights' => $flights ? implode(',', $flights) : null,
                'flight_ids' => $ids ? implode(',', $ids) : null,
            ],
        ]);

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\FlightRadar24\Model\FlightPosition>'
        );
    }

    /**
     * @return array<\Stevro\FlightTracking\FlightRadar24\Model\FlightPosition>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function light(array $flights = null, array $ids = null)
    {
        $response = $this->doExecute('live/flight-positions/light', [
            'query' => [
                'flights' => $flights ? implode(',', $flights) : null,
                'flight_ids' => $ids ? implode(',', $ids) : null,
            ],
        ]);

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\FlightRadar24\Model\FlightPosition>'
        );
    }

}