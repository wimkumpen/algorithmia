<?php
namespace Algorithmia\Http;

use Algorithmia\Exceptions\ResponseException;
use Algorithmia\Exceptions\SDKException;

/**
 * Class DirectoryResponse
 *
 * @package Algorithmia\Http
 */
class DirectoryResponse extends Response
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $folders = [];

    /**
     * @var array
     */
    private $acl = [];

    /**
     * @var string
     */
    private $marker = "";

    /**
     * @var mixed
     */
    private $deleted = false;

    /**
     * @var mixed
     */
    private $errorDeleted = false;

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getDirectories()
    {
        return $this->folders;
    }

    /**
     * @return array
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return string
     */
    public function getMarker()
    {
        return $this->marker;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @return mixed
     */
    public function getErrorDeleted()
    {
        return $this->errorDeleted;
    }

    /**
     * Convert the raw response into an array if possible.
     */
    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);

        if (isset($this->decodedBody['files'])) {
            $this->files = $this->decodedBody['files'];
        }

        if (isset($this->decodedBody['folders'])) {
            $this->folders = $this->decodedBody['folders'];
        }

        if (isset($this->decodedBody['acl'])) {
            $this->acl = $this->decodedBody['acl'];
        }

        if (isset($this->decodedBody['marker'])) {
            $this->marker = $this->decodedBody['marker'];
        }

        if (isset($this->decodedBody['result']) && isset($this->decodedBody['result']['deleted'])) {
            $this->deleted = $this->errorDeleted['result']['deleted'];
        }

        if (isset($this->decodedBody['error']) && isset($this->decodedBody['error']['deleted'])) {
            $this->errorDeleted = $this->errorDeleted['error']['deleted'];
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }
}
