<?php
namespace Algorithmia;

use Algorithmia\Url\UrlManipulator;
use Algorithmia\Exceptions\SDKException;

/**
 * Class Request
 *
 * @package Algorithmia
 */
class Request
{
    /**
     * @var string|null The access token to use for this request.
     */
    protected $accessToken;

    /**
     * @var string The HTTP method for this request.
     */
    protected $method;

    /**
     * @var string The Algorithmia endpoint for this request.
     */
    protected $endpoint;

    /**
     * @var array The headers to send with this request.
     */
    protected $headers = [];

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * @var string Algorithmia version to use for this request.
     */
    protected $algorithmiaVersion;

    /**
     * Creates a new Request entity.
     *
     * @param AccessToken|string|null $accessToken
     * @param string|null             $method
     * @param string|null             $endpoint
     * @param array|\CURLFile|null    $params
     */
    public function __construct($accessToken = null, $method = null, $endpoint = null, $params = [])
    {
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
    }

    /**
     * Set the access token for this request.
     *
     * @param AccessToken|string
     *
     * @return Request
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Return the access token for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the HTTP method for this request.
     *
     * @param string
     *
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        
        return $this;
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws SDKException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new SDKException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'])) {
            throw new SDKException('Invalid HTTP method specified.');
        }
    }

    /**
     * Set the endpoint for this request.
     *
     * @param string
     *
     * @return Request
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        
        return $this;
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getEndpoint()
    {
        // For batch requests, this will be empty
        return $this->endpoint;
    }

    /**
     * Generate and return the headers for this request.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = static::getDefaultHeaders();

        return array_merge($headers, $this->headers);
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     *
     * @return Request
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Set the params for this request.
     *
     * @param array|\CURLFile|null $params
     *
     * @return Request
     *
     * @throws SDKException
     */
    public function setParams($params = [])
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;

        return $params;
    }

    /**
     * Only return params on POST requests.
     *
     * @return array
     */
    public function getPostParams()
    {
        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            return $this->getParams();
        }

        return [];
    }

    /**
     * The Algorithmia version used for this request.
     *
     * @return string
     */
    public function getAlgorithmiaVersion()
    {
        return $this->algorithmiaVersion;
    }

    /**
     * Generate and return the URL for this request.
     *
     * @return string
     */
    public function getUrl()
    {
        $this->validateMethod();

        $url = UrlManipulator::forceSlashPrefix($this->getEndpoint());

        if ($this->getMethod() !== 'POST') {
            $params = $this->getParams();
            $params['v'] = $this->getAlgorithmiaVersion();
            $url = UrlManipulator::appendParamsToUrl($url, $params);
        } else {
            $url = UrlManipulator::appendParamsToUrl($url, array('v' => $this->getAlgorithmiaVersion()));
        }

        return $url;
    }

    /**
     * Return the default headers that every request should use.
     *
     * @return array
     */
    public static function getDefaultHeaders()
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}
