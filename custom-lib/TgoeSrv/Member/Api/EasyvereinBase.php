<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Api;

use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;
use GuzzleHttp\Client;
use TgoeSrv\Tools\Logger;

abstract class EasyvereinBase
{

    private const MAX_PAGE_QUERIES = 25;

    private const PAGE_SIZE = 100;

    /**
     * Send request to list API.
     * If not all records are returned, loop
     * all available pages. Combine all results to one array.
     *
     * @param string $function
     * @param array $queryParams
     */
    protected function executeRestQueryWithPaging(string $function, array $queryParams) : array
    {
        $queryParams['page'] = 0;
        $queryParams['limit'] = self::PAGE_SIZE;
        $combinedResults = array();

        do {
            $queryParams['page'] ++;

            Logger::info(__METHOD__.' Requesting page '.$queryParams['page']);
            $result = $this->executeRestQuery($function, $queryParams);
            $combinedResults = array_merge($combinedResults, $result['results']);

            $hasNextPage = isset($result['next']);
        } while ($queryParams['page'] < self::MAX_PAGE_QUERIES && $hasNextPage);
        
        return $combinedResults;
    }

    /**
     * Apply default settings for Easyverein API and send request.
     *
     * @param string $function
     * @param array $queryParams
     */
    protected function executeRestQuery(string $function, array $queryParams) : array
    {
        $evUrl = ConfigManager::getValue(ConfigKey::EASYVEREIN_SERVICEURL);
        $evAuth = ConfigManager::getValue(ConfigKey::EASYVEREIN_APIKEY);

        // https://docs.guzzlephp.org/en/stable/request-options.html
        $requestOptions = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Token ' . $evAuth
            ],
            'query' => $queryParams
        ];

        $client = new Client([
            'base_uri' => $evUrl
        ]);
        
        $response = $client->request('GET', $function, $requestOptions);

        if ($response->getStatusCode() != 200) {
            $msg = "Easyverein:: API call to easyverein returned with status ".$response->getStatusCode();
            Logger::error(__METHOD__.' '.$msg);
            Logger::info(__METHOD__.' Response body = '.$response->getBody());
            throw new \Exception($msg);
        }
        
        $json = $response->getBody()->getContents();
        $result = json_decode($json, true);
        if( !is_array($result) ) {
            $msg = "Easyverein:: API call to easyverein returned invalid JSON ";
            Logger::error(__METHOD__.' '.$msg);
            Logger::info(__METHOD__.' Response body = '.$response->getBody());
            throw new \Exception($msg);
        }
        
        return $result;
    }
}

