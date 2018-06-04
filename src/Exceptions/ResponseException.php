<?php
namespace Algorithmia\Exceptions;

use Algorithmia\Http\Response;

/**
 * Class ResponseException
 *
 * @package Algorithmia
 */
class ResponseException extends SDKException
{
    /**
     * @var Response The response that threw the exception.
     */
    protected $response;

    /**
     * @var array Decoded response.
     */
    protected $responseData;

    /**
     * Creates a ResponseException.
     *
     * @param Response     $response          The response that threw the exception.
     * @param SDKException $previousException The more detailed exception.
     */
    public function __construct(Response $response, SDKException $previousException = null)
    {
        $this->response = $response;

        parent::__construct($previousException->getMessage(), $previousException->getCode(), $previousException);
    }

    /**
     * A factory for creating the appropriate exception based on the response from Algorithmia.
     *
     * @param Response $response The response that threw the exception.
     *
     * @return ResponseException
     */
    public static function create(Response $response)
    {
        $data = $response->getDecodedBody();

        if (isset($data['error']['message'])) {
            $message = $data['error']['message'];
        } else {
            $message = 'Unknown error from Algorithmia.';
        }

        switch ($message) {
            case 'authorization required':
                return new static($response, new AuthorizationException($message, 401));
        }

        return new static($response, new OtherException($message, 404));
    }

    /**
     * Checks isset and returns that or a default value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function get($key, $default = null)
    {
        if (isset($this->responseData[$key])) {
            return $this->responseData[$key];
        }

        return $default;
    }

    /**
     * Returns the HTTP status code
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->response->getHttpStatusCode();
    }

    /**
     * Returns the sub-error code
     *
     * @return int
     */
    public function getSubErrorCode()
    {
        return $this->get('error_subcode', -1);
    }

    /**
     * Returns the error type
     *
     * @return string
     */
    public function getErrorType()
    {
        return $this->get('type', '');
    }

    /**
     * Returns the raw response used to create the exception.
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->response->getBody();
    }

    /**
     * Returns the decoded response used to create the exception.
     *
     * @return array
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Returns the response entity used to create the exception.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
