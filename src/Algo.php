<?php
namespace Algorithmia;

use Algorithmia\Exceptions\SDKException;
use Algorithmia\HttpClients\HttpClientsFactory;

/**
 * Class Algo
 *
 * @package Algorithmia
 */
class Algo extends Algorithmia
{
    /**
     * http://docs.algorithmia.com/?shell#call-an-algorithm
     *
     * @param string                    $path
     * @param array|\CURLFile|null      $post
     * @param array                     $headers
     *
     * @return Algo
     */
    public function algo($path, $post = [], array $headers = [])
    {
        $this->request = $this->request(
            'POST',
            '/' . self::VERSION . '/algo/' . $path,
            $post,
            $headers
        );

        return $this;
    }

    /**
     * http://docs.algorithmia.com/?shell#query-parameters
     *
     * @param int $timeout (default 300 seconds, maximum 3000 seconds)
     * @param bool $stdout (Indicates algorithm stdout should be returned in the response metadata (ignored unless you are the algorithm owner))
     * @param string $output (empty = results, raw = returns the result without JSON-RPC wrapper, void = doesn't wait for an answer, only useful sometimes where outputs to a data://)
     *
     * @return Response
     */
    public function call($timeout = 300, $stdout = false, $output = "")
    {
        $url = $this->request->getUrl();
        $url = $url . "timeout=" . $timeout . "&stdout=" . (int)$stdout . "&output=" . $output;

        $this->request->setEndpoint($url);

        return $this->lastResponse = $this->client->sendRequest($this->request);
    }
}
