<?php
namespace Algorithmia;

use Algorithmia\Http\DataResponse;
use Algorithmia\Http\DirectoryResponse;
use Algorithmia\Http\FileResponse;
use Algorithmia\HttpClients\HttpClientInterface;
use Algorithmia\Exceptions\SDKException;

/**
 * Class Client
 *
 * @package Algorithmia
 */
class Client
{
    /**
     * @const string Production Algorithmia API URL.
     */
    const BASE_ALGORITHMIA_URL = 'https://api.algorithmia.com';

    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var HttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * @var int The number of calls that have been made to Algorithmia.
     */
    public static $requestCount = 0;

    /**
     * Instantiates a new Client object.
     *
     * @param HttpClientInterface|null $httpClientHandler
     */
    public function __construct(HttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param HttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(HttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return HttpClientInterface
     */
    public function getHttpClientHandler()
    {
        return $this->httpClientHandler;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param Request $request
     *
     * @return array
     */
    public function prepareRequestMessage(Request $request)
    {
        $url = static::BASE_ALGORITHMIA_URL . $request->getUrl();

        if ($request->getPostParams() instanceof \CURLFile) {
            $jsonPost = $request->getPostParams();
        } else {
            $jsonPost = json_encode($request->getPostParams());
        }

        $request->setHeaders([
            'Authorization' => 'Simple ' . $request->getAccessToken()
        ]);

        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $jsonPost,
        ];
    }

    /**
     * Makes the request to Algorithmia and returns the result.
     *
     * @param Request $request
     *
     * @return DataResponse|DirectoryResponse
     *
     * @throws SDKException
     */
    public function sendRequest(Request $request)
    {
        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);

        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `SDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);

        static::$requestCount++;

        if (isset($rawResponse->getHeaders()['X-Data-Type']) || $rawResponse->getHeaders()['X-Data-Type'] == 'file') {
            $returnResponse = new FileResponse(
                $request,
                $rawResponse->getBody(),
                $rawResponse->getHttpResponseCode(),
                $rawResponse->getHeaders()
            );
        } else if (isset($rawResponse->getHeaders()['X-Data-Type']) || $rawResponse->getHeaders()['X-Data-Type'] == 'direcotry') {
            $returnResponse = new DirectoryResponse(
                $request,
                $rawResponse->getBody(),
                $rawResponse->getHttpResponseCode(),
                $rawResponse->getHeaders()
            );
        } else {
            $returnResponse = new DataResponse(
                $request,
                $rawResponse->getBody(),
                $rawResponse->getHttpResponseCode(),
                $rawResponse->getHeaders()
            );
        }

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }
}
