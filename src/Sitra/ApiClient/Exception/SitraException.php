<?php

namespace Sitra\ApiClient\Exception;

use Exception;
use GuzzleHttp\Command\Exception\CommandException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SitraException
 *
 * @package Sitra\ApiClient\Exception
 */
class SitraException extends \Exception
{
    /** @var \Psr\Http\Message\RequestInterface $request */
    protected $request;

    /** @var null|\Psr\Http\Message\ResponseInterface $response */
    protected $response;

    /**
     * SitraException constructor.
     *
     * @param GuzzleException|CommandException $e
     */
    public function __construct(GuzzleException $e)
    {
        $this->request  = $e->getRequest();
        $this->response = $e->getResponse();
        $simpleMessage  = $e->getMessage();
        $code    = 0;

        if ($this->response) {
            try {
                $decodedJson = \GuzzleHttp\json_decode((string) $this->response->getBody(), true);
                if ($decodedJson && isset($decodedJson['errorType'])) {
                    $simpleMessage = $decodedJson['errorType'].' '.$decodedJson['message'];
                }
            } catch (\InvalidArgumentException $e) {
                // Not Json
            }

            $code = $this->response->getStatusCode();
        }

        $responseDescription = $this->response ? $this->response->getReasonPhrase() : 'none';
        $requestDescription = $this->request ? $this->request->getUri() : 'none';

        $message = sprintf("%s

Request: %s

Response: %s

", $simpleMessage, $requestDescription, $responseDescription);

        parent::__construct($message, $code, $e);
    }
}
