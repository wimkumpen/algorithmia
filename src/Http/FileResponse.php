<?php
namespace Algorithmia\Http;

use Algorithmia\Exceptions\ResponseException;
use Algorithmia\Exceptions\SDKException;

/**
 * Class FileResponse
 *
 * @package Algorithmia\Http
 */
class FileResponse extends Response
{
    /**
     * @var bool
     */
    private $fileExist = null;

    /**
     * @return null|string
     */
    public function getFile()
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function fileExist()
    {
        return $this->fileExist;
    }

    /**
     * Convert the raw response into an array if possible.
     */
    public function decodeBody()
    {
        $this->fileExist = parent::getRequest()->getMethod() === 'HEAD' && parent::getHttpStatusCode() === 200 ? true : null;

        if ($this->isError()) {
            $this->makeException();
        }
    }
}
