<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PhoenixApiService
{
    private string $phoenixBaseUrl;

    public function __construct(
        private HttpClientInterface $httpClient,
        ParameterBagInterface $parameterBag
    ) {
        $this->phoenixBaseUrl = rtrim($parameterBag->get('phoenix_base_url'), '/');
    }

    /**
     * Fetch photos from Phoenix API
     *
     * @param string $accessToken The phoenix_app_token
     * @return array List of photos from Phoenix API
     * @throws \Exception
     */
    public function getPhotos(string $accessToken): array
    {
        if (empty($accessToken)) {
            throw new BadRequestException('Access token is required');
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->phoenixBaseUrl . '/api/photos',
                [
                    'headers' => [
                        'access-token' => $accessToken,
                    ],
                ]
            );

            $statusCode = $response->getStatusCode();

            return match ($statusCode) {
                200 => $response->toArray(),

                401 => throw new UnauthorizedHttpException(
                    '',
                    'Invalid or expired Phoenix access token.'
                ),

                403 => throw new UnauthorizedHttpException(
                    '',
                    'Access to Phoenix API was denied.'
                ),

                404 => throw new \RuntimeException(
                    'Phoenix photos endpoint was not found.'
                ),

                429 => throw new ServiceUnavailableHttpException(
                    300,
                    'Phoenix API rate limit exceeded.'
                ),

                default => throw new \RuntimeException(
                    sprintf(
                        'Unexpected Phoenix API response status: %d',
                        $statusCode
                    )
                ),
            };
        } catch (
            TransportExceptionInterface |
            RedirectionExceptionInterface $e
        ) {
            throw new ServiceUnavailableHttpException(
                300,
                'Unable to communicate with Phoenix API.',
                $e
            );
        } catch (
            ClientExceptionInterface |
            ServerExceptionInterface $e
        ) {
            throw new \RuntimeException(
                'Phoenix API request failed.',
                previous: $e
            );
        }
    }
}
