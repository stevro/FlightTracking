<?php

namespace Stevro\FlightTracking\Provider\FlightStats\API;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Provider\FlightStats\Model\ByFlight\Response;

class FlightStatusByFlightAPI extends BaseAPI
{


    public function getByArrivalDate(
        string $flightNumber,
        string $carrier,
        \DateTimeInterface $arrivalDate,
        array $queryParams = []
    ): Response {
        try {
            $response = $this->httpClient->get(
                sprintf(
                    'json/flight/status/%s/%s/arr/%s/%s/%s',
                    $carrier,
                    $flightNumber,
                    $arrivalDate->format('Y'),
                    $arrivalDate->format('m'),
                    $arrivalDate->format('d')
                ),
                [
                    'query' => array_merge([
                        'extendedOptions' => 'useHttpErrors',
                    ], $queryParams),
                ]
            );
        } catch (BadResponseException $e) {
            $this->handleBadResponse($e);
        } catch (ClientExceptionInterface $e) {
            throw new APIException($e->getMessage(), '', $e->getCode(), $e);
        }

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            Response::class,
            'json'
        );
    }

    public function getByDepartureDate(
        string $flightNumber,
        string $carrier,
        \DateTimeInterface $departureDate,
        array $queryParams = []
    ): Response {
        try {
            $response = $this->httpClient->get(
                sprintf(
                    'json/flight/status/%s/%s/dep/%s/%s/%s',
                    $carrier,
                    $flightNumber,
                    $departureDate->format('Y'),
                    $departureDate->format('m'),
                    $departureDate->format('d')
                ),
                [
                    'query' => $queryParams,
                ]
            );
        } catch (BadResponseException $e) {
            $this->handleBadResponse($e);
        } catch (ClientExceptionInterface $e) {
            throw new APIException($e->getMessage(), '', $e->getCode(), $e);
        }

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            Response::class,
            'json'
        );
    }

}