<?php

namespace Sitra\Tests\Endpoints;

use Exception;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Sitra\ApiClient\SitraServiceClient;
use Sitra\Tests\Base;

/**
 * Class BaseEndpointTestCase
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class BaseEndpointTestCase extends Base
{
    /** @var array $config */
    protected $config = [
        'base_uri'       => 'http://api.sitra-tourisme.com/',
    ];


    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->config += [
            'http_errors' => false,
            'timeout' => 10.0,
            'base_uri' => $this->description->getBaseUri(),
            'apiKey'        => 'XXX',
            'projectId'     => 000,
        ];
    }


    /**
     * Import and apply api description from json file.
     *
     * @param array $operations
     * @return Description
     */
    public function importDescription(array $operations) : Description
    {
        $data = $this->getRawDescriptionFromFile($operations);

        return new Description($data);
    }


    /**
     * Reads the description file, checks the content and return an array
     *
     * @param array $operations
     * @return array
     * @throws Exception
     */
    protected function getRawDescriptionFromFile(array $operations) : array
    {
        $descriptionData = [
            'baseUri' => $this->config['base_uri'],
            'operations' => $operations,
            'models' => [
                'getResponse' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json',
                    ],
                ],
            ],
        ];

        if (empty($descriptionData['baseUri'])) {
            throw new Exception('Api description does not contain a baseUrl.');
        }

        if (empty($descriptionData['operations'])) {
            throw new Exception('Api description does not contain any operation definition.');
        }

        return $descriptionData;
    }


    /**
     * @param array $responses
     * @return SitraServiceClient
     */
    protected function getTestClient(array $responses = [])
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push(Middleware::history($this->container));

        $this->config += [
            'handler' => $handlerStack,
        ];

        return new SitraServiceClient($this->config, $this->description);
    }

}
