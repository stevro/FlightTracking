<?php

namespace Stevro\FlightTracking\FlightRadar24\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Stevro\FlightTracking\Exception\APIException;
use Stevro\FlightTracking\Exception\ForbiddenException;
use Stevro\FlightTracking\Exception\UnauthenticatedException;
use Stevro\FlightTracking\Exception\ValidationErrorException;

class BaseAPI
{

    protected $apiKey;

    protected $baseUrl = 'https://fr24api.flightradar24.com/api/';
    /**
     * @var Client|ClientInterface
     */
    protected $httpClient;

    public function __construct(string $apiKey, ClientInterface $httpClient = null, SerializerInterface $serializer = null)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient
            ?: new Client([
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Accept-Version' => 'v1',
                    'Authorization' => 'Bearer '.$this->apiKey,
                ],
                'verify' => true,
            ]);
        $this->serializer = $serializer ?: $this->initSerializer();
    }

    /**
     * @return SerializerInterface
     */
    protected function initSerializer(): SerializerInterface
    {
        return SerializerBuilder::create()->build();
    }

    protected function doExecute($uri, $options): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->httpClient->get(
                $uri,
                $options
            );
        } catch (BadResponseException $e) {
            $responseBody = $this->serializer->deserialize($e->getResponse()->getBody()->getContents(), 'array', 'json');

            if ($e->getResponse()->getStatusCode() === 400) {
                throw new ValidationErrorException(
                    $responseBody['message'] ?? $e->getMessage(),
                    $responseBody['details'] ?? '', $e->getCode(), $e
                );
            }

            if ($e->getResponse()->getStatusCode() === 401) {
                throw new UnauthenticatedException(
                    $responseBody['message'] ?? $e->getMessage(),
                    $responseBody['details'] ?? '', $e->getCode(), $e
                );
            }

            if ($e->getResponse()->getStatusCode() === 402) {
                throw new ForbiddenException(
                    $responseBody['message'] ?? $e->getMessage(),
                    $responseBody['details'] ?? '', $e->getCode(), $e
                );
            }

            throw new APIException($responseBody['message'] ?? $e->getMessage(), '', $e->getCode(), $e);
        } catch (ClientExceptionInterface $e) {
            throw new APIException(
                $e->getMessage(),
                '',
                $e->getCode(),
                $e
            );
        }
    }

}