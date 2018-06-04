<?php
namespace Algorithmia\Http;

use Algorithmia\Exceptions\SDKException;
use Algorithmia\Http\Response;

/**
 * Class DataResponse
 *
 * @package Algorithmia\Http
 */
class DataResponse extends Response
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @var mixed
     */
    private $metadata;

    /**
     * @var null|string
     */
    private $requestId;

    /**
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function getMetadata($key = null) {
        if (!is_null($key) && isset($this->metadata[$key])) {
            return $this->metadata[$key];
        }
        return $this->metadata;
    }

    /**
     *
     * Only available when output request is void
     *
     * @return mixed
     *
     * @throws SDKException
     */
    public function getRequestId()
    {
        if ($this->requestId) {
            return $this->requestId;
        } else {
            throw new SDKException('Request id is only available in void requests (http://docs.algorithmia.com/?shell#query-parameters)');
        }
    }

    /**
     * Convert the raw response into an array if possible.
     */
    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);

        $this->result = $this->decodedBody['result'];
        $this->metadata = $this->decodedBody['metadata'];

        if (isset($this->decodedBody['async']) && $this->decodedBody['async'] === 'void') {
            $this->requestId = $this->decodedBody['request_id'];
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }
}
