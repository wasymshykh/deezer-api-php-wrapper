<?php

namespace wasymshykh\DeezerAPI;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

/**
 * Class DeezerAPIClient
  */
class DeezerAPIClient
{
    const DEEZER_API_URL = 'https://api.deezer.com';

    /**
     * Return types for json_decode
     */
    const RETURN_AS_OBJECT = 0;
    const RETURN_AS_ASSOC = 1;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $responseType = self::RETURN_AS_OBJECT;

    /**
     * DeezerAPIClient constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct()
    {
        $this->httpClient = new CurlHttpClient();
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param int $responseType
     */
    public function setResponseType(int $responseType): void
    {
        $this->responseType = $responseType;
    }

    /**
     * @return int
     */
    public function getResponseType(): int
    {
        return $this->responseType;
    }

    /**
     * @param string                                      $method
     * @param string                                      $service
     * @param array                                       $headers
     * @param array|string|resource|\Traversable|\Closure $body
     *
     * @return object|array
     *
     * @throws DeezerAPIException
     */
    public function apiRequest(string $method, string $service, array $headers = [], $body = null)
    {
        $url = sprintf(
            '%s/%s?access_token=%s',
            self::DEEZER_API_URL,
            $service,
            $this->accessToken
        );

        try {
            $response = $this->httpClient->request($method, $url, ['headers' => $headers, 'body' => $body]);

            return json_decode($response->getContent(), $this->responseType === self::RETURN_AS_ASSOC);
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | TransportExceptionInterface $exception) {
            throw new DeezerAPIException(
                'API Request: '.$service.', '.$exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    public function publicApiRequest(string $method, string $service, array $headers = [], $body = null)
    {
        $url = sprintf('%s/%s', self::DEEZER_API_URL, $service);

        try {
            $response = $this->httpClient->request($method, $url, ['headers' => $headers, 'body' => $body]);

            return json_decode($response->getContent(), $this->responseType === self::RETURN_AS_ASSOC);
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | TransportExceptionInterface $exception) {
            throw new DeezerAPIException(
                'API Request: '.$service.', '.$exception->getMessage(),
                $exception->getCode()
            );
        }
    }
}
