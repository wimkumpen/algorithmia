<?php
namespace Algorithmia\Http;

use Algorithmia\Exceptions\ResponseException;
use Algorithmia\Exceptions\SDKException;
use Algorithmia\Request;

/**
 * Class Response
 *
 * @package Algorithmia
 */
class Response
{
    /**
     * @var int The HTTP status code response from Algorithmia.
     */
    protected $httpStatusCode;

    /**
     * @var array The headers returned from Algorithmia.
     */
    protected $headers;

    /**
     * @var string The raw body of the response from Algorithmia.
     */
    protected $body;

    /**
     * @var array The decoded body of the Algorithmia response.
     */
    protected $decodedBody = [];

    /**
     * @var Request The original request that returned this response.
     */
    protected $request;

    /**
     * @var SDKException The exception thrown by this request.
     */
    protected $thrownException;

    /**
     * Creates a new Response entity.
     *
     * @param Request $request
     * @param string|null     $body
     * @param int|null        $httpStatusCode
     * @param array|null      $headers
     */
    public function __construct(Request $request, $body = null, $httpStatusCode = null, array $headers = [])
    {
        $this->request = $request;
        $this->body = $body;
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $headers;

        $this->decodeBody();
    }

    /**
     * Return the original request that returned this response.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Return the access token that was used for this response.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->request->getAccessToken();
    }

    /**
     * Return the HTTP status code for this response.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Return the HTTP headers for this response.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the raw body response.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the decoded body response.
     *
     * @return array
     */
    public function getDecodedBody()
    {
        return $this->decodedBody;
    }

    /**
     * Returns true if Algorithmia returned an error message.
     *
     * @return boolean
     */
    public function isError()
    {
        return isset($this->decodedBody['error']) || isset($this->decodedBody['errors']);
    }

    /**
     * Throws the exception.
     *
     * @throws SDKException
     */
    public function throwException()
    {
        throw $this->thrownException;
    }

    /**
     * Instantiates an exception to be thrown later.
     */
    public function makeException()
    {
        $this->thrownException = ResponseException::create($this);
    }

    /**
     * Returns the exception that was thrown for this request.
     *
     * @return SDKException|null
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }
}
