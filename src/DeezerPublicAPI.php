<?php

namespace wasymshykh\DeezerAPI;

/**
 * Class DeezerPublicAPI.
 */
class DeezerPublicAPI
{
    /**
     * @var DeezerAPIClient
     */
    protected $client;

    /**
     * DeezerAPI constructor.
     * @param DeezerAPIClient $client
     */
    public function __construct(DeezerAPIClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return DeezerAPIClient
     */
    public function getDeezerAPIClient(): DeezerAPIClient
    {
        return $this->client;
    }
    
    /**
     * Return the search data.
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function search ($keyword, $limit = 5)
    {
        $query_string = http_build_query(['q' => $keyword, 'limit' => $limit]);
        return $this->client->publicApiRequest('GET', 'search?'.$query_string, []);
    }

    /**
     * Return the track search data.
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function searchTrack ($keyword, $limit = 5)
    {
        $query_string = http_build_query(['q' => $keyword, 'limit' => $limit]);
        return $this->client->publicApiRequest('GET', 'search/track?'.$query_string, []);
    }

}
