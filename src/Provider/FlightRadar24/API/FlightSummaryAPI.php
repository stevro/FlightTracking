<?php

namespace Stevro\FlightTracking\Provider\FlightRadar24\API;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class FlightSummaryAPI extends BaseAPI
{
    /**
     * @return array<\Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightSummary>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function light(
        array $flights = null,
        array $ids = null,
        \DateTime $flightDateTimeFrom = null,
        \DateTime $flightDateTimeTo = null
    ): array {
        try {
            $response = $this->httpClient->get('flight-summary/light', [
                'query' => [
                    'flights' => $flights ? implode(',', $flights) : null,
                    'flight_ids' => $ids ? implode(',', $ids) : null,
                    'flight_datetime_from' => $flightDateTimeFrom ? $flightDateTimeFrom->format('Y-m-d\TH:i:s') : null,
                    'flight_datetime_to' => $flightDateTimeTo ? $flightDateTimeTo->format('Y-m-d\TH:i:s') : null,
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
            'array<Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightSummary>'
        );
    }

    /**
     * @return array<\Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightSummary>
     * @throws ForbiddenException
     * @throws ValidationErrorException
     * @throws UnauthenticatedException
     *
     * @throws APIException
     */
    public function full(array $flights = [], array $ids = [], \DateTime $flightDateTimeFrom = null, \DateTime $flightDateTimeTo = null): array
    {
        try {
            $response = $this->httpClient->get('flight-summary/full', [
                'query' => [
                    'flights' => $flights ? implode(',', $flights) : null,
                    'flight_ids' => $ids ? implode(',', $ids) : null,
                    'flight_datetime_from' => $flightDateTimeFrom ? $flightDateTimeFrom->format('Y-m-d\TH:i:s') : null,
                    'flight_datetime_to' => $flightDateTimeTo ? $flightDateTimeTo->format('Y-m-d\TH:i:s') : null,
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
            'array<Stevro\FlightTracking\Provider\FlightRadar24\Model\FlightSummary>'
        );
    }


}