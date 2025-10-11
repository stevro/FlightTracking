<?php

namespace Stevro\FlightTracking\Provider\FlightRadar24\API;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class FlightPositionsAPI extends BaseAPI
{

    /**
     * @return array<\Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightPosition>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function full(array $flights = null, array $ids = null): array
    {
        try {
            $response = $this->httpClient->get(
                'live/flight-positions/full',
                [
                    'query' => [
                        'flights' => $flights ? implode(',', $flights) : null,
                        'flight_ids' => $ids ? implode(',', $ids) : null,
                    ],
                ]
            );
        } catch (BadResponseException $e) {
            $this->handleBadResponse($e);
        } catch (ClientExceptionInterface $e) {
            throw new APIException($e->getMessage(), '', $e->getCode(), $e);
        }

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightPosition>'
        );
    }

    /**
     * @return array<\Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightPosition>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function light(array $flights = null, array $ids = null)
    {
        try {
            $response = $this->httpClient->get('live/flight-positions/light', [
                'query' => [
                    'flights' => $flights ? implode(',', $flights) : null,
                    'flight_ids' => $ids ? implode(',', $ids) : null,
                ],
            ]);
        } catch (BadResponseException $e) {
            $this->handleBadResponse($e);
        } catch (ClientExceptionInterface $e) {
            throw new APIException($e->getMessage(), '', $e->getCode(), $e);
        }

        $payload = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->fromArray(
            $payload['data'],
            'array<Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightPosition>'
        );
    }

}