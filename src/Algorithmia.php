<?php
namespace Algorithmia;

use Algorithmia\Exceptions\SDKException;
use Algorithmia\HttpClients\HttpClientsFactory;

/**
 * Class Algorithmia
 *
 * @package Algorithmia
 */
class Algorithmia
{
    /**
     * @const string Version number of the Algorithmia PHP SDK.
     */
    const VERSION = 'v1';

    /**
     * @var Client The Algorithmia client service.
     */
    protected $client;

    /**
     * @var AccessToken|null The default access token to use with requests.
     */
    protected $defaultAccessToken;

    /**
     * @var Response|null Stores the last request made to Algorithmia.
     */
    protected $lastResponse;

    protected $request;

    /**
     * Instantiates a new Algorithmia super-class object.
     *
     * @param array $config
     *
     * @throws SDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'default_access_token' => null,
            'http_client_handler' => null,
            'persistent_data_handler' => null,
            'debug' => false
        ], $config);

        if (is_null($config['default_access_token'])) {
            throw new SDKException('You forgot to set your token with param "default_access_token".');
        }

        $this->client = new Client(
            HttpClientsFactory::createHttpClient($config['http_client_handler'])
        );
        $this->client->getHttpClientHandler()->setDebug($config['debug']);

        $this->defaultAccessToken = $config['default_access_token'];
    }

    /**
     * Returns the default AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getDefaultAccessToken()
    {
        return $this->defaultAccessToken;
    }

    /**
     * Returns the Client service.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sends a request to Algorithmia and returns the result.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array|\CURLFile|null    $params
     * @param array                   $headers
     *
     * @return Response
     *
     * @throws SDKException
     */
    public function sendRequest($method, $endpoint, $params = [], array $headers = [])
    {
        $request = $this->request($method, $endpoint, $params, $headers);

        return $this->lastResponse = $this->client->sendRequest($request);
    }
    
    /**
     * Instantiates a new Request entity.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array|\CURLFile|null    $params
     * @param array                   $headers
     *
     * @return Request
     *
     * @throws SDKException
     */
    public function request($method, $endpoint, $params = [], array $headers = [])
    {
        $request =  new Request(
            $this->defaultAccessToken,
            $method,
            $endpoint,
            $params
        );

        $request->setHeaders($headers);

        return $request;
    }

    /**
     * Returns the last response returned from Algorithmia.
     *
     * @return Response|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
}
