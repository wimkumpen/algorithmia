<?php
namespace Algorithmia;

use Algorithmia\Exceptions\SDKException;
use Algorithmia\HttpClients\HttpClientsFactory;

/**
 * Class Connector
 *
 * @package Algorithmia
 */
class Connector extends Algorithmia
{
    /**
     * @param string $connector
     * @param string $path
     * @param string $marker
     * @param bool $acl
     * @param array $headers
     *
     * @return Http\DirectoryResponse
     */
    public function getDir($connector = "data", $path = ".my", $marker = "", $acl = false, $headers = [])
    {
        $acl = $acl ? "true" : "false";

        $this->request = $this->request(
            'GET',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path . "?acl=" . $acl . "&marker=" . $marker,
            [],
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    /**
     * @param $connector
     * @param $path
     * @param array $post
     * @param array $headers
     *
     * @return Http\DirectoryResponse
     */
    public function createDir($connector, $path, array $post = [], array $headers = [])
    {
        $this->request = $this->request(
            'POST',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            $post,
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    /**
     * @param $connector
     * @param $path
     * @param array $post
     * @param array $headers
     *
     * @return Http\DirectoryResponse
     */
    public function updateDir($connector, $path, array $post = [], array $headers = [])
    {
        $this->request = $this->request(
            'PATCH',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            $post,
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    /**
     * @param $connector
     * @param $path
     * @param bool $force
     * @param array $headers
     *
     * @return Http\DirectoryResponse
     */
    public function deleteDir($connector, $path, $force = false, array $headers = [])
    {
        $force = $force ? "true" : "false";

        $this->request = $this->request(
            'DELETE',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path . '?force=' . $force,
            [],
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    /**
     * @param $connector
     * @param $path
     * @param array $headers
     *
     * @return Http\FileResponse
     */
    public function getFile($connector, $path, array $headers = [])
    {
        $this->request = $this->request(
            'GET',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            array(),
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    public function fileExist($connector, $path, array $headers = [])
    {
        $this->request = $this->request(
            'HEAD',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            array(),
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    public function uploadFile($connector, $path, array $content = [], array $headers = [])
    {
        $this->request = $this->request(
            'PUT',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            $content,
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }

    public function deleteFile($connector, $path, array $headers = [])
    {
        $this->request = $this->request(
            'DELETE',
            '/' . self::VERSION . '/connector/' . $connector . '/' . $path,
            array(),
            $headers
        );

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }
}
