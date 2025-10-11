<?php

namespace Stevro\FlightTracking\Provider\FlightStats\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientInterface;
use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class BaseAPI
{


    protected $baseUrl = 'https://api.flightstats.com/flex/flightstatus/rest/v2/';
    /**
     * @var Client|ClientInterface
     */
    protected $httpClient;


    public function __construct(string $appId, string $appKey, ClientInterface $httpClient = null, SerializerInterface $serializer = null)
    {
        $this->httpClient = $httpClient
            ?: new Client([
                'base_uri' => $this->baseUrl,
                'timeout' => 5,
                'verify' => true,
                'headers' => [
                    'appId' => $appId,
                    'appKey' => $appKey,
                ],
            ]);
        $this->serializer = $serializer ?: $this->initSerializer();
    }

    /**
     * @return SerializerInterface
     */
    protected function initSerializer(): SerializerInterface
    {
        return SerializerBuilder::create()->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
            ->build();
    }

    protected function handleBadResponse(BadResponseException $e)
    {
        $responseBody = $this->serializer->deserialize($e->getResponse()->getBody()->getContents(), 'array', 'json');

        if ($e->getResponse()->getStatusCode() === 400) {
            throw new ValidationErrorException(
                $responseBody['message'] ?? $e->getMessage(), '', $e->getCode(), $e
            );
        }

        if ($e->getResponse()->getStatusCode() === 401) {
            throw new UnauthenticatedException(
                $responseBody['message'] ?? $e->getMessage(), '', $e->getCode(), $e
            );
        }
        if ($e->getResponse()->getStatusCode() === 403) {
            throw new ForbiddenException(
                $responseBody['message'] ?? $e->getMessage(), '', $e->getCode(), $e
            );
        }

        throw new APIException($responseBody['message'] ?? $e->getMessage(), '', $e->getCode(), $e);
    }

}