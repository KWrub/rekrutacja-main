<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
            $response = $this->httpClient->request('GET', $this->phoenixBaseUrl . '/api/photos', [
                'headers' => [
                    'access-token' => $accessToken,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                throw new \Exception('Failed to fetch photos from Phoenix API. Status: ' . $statusCode);
            }

            return $response->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error communicating with Phoenix API: ' . $e->getMessage());
        }
    }
}
