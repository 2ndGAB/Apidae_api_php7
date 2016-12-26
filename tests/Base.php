<?php

namespace Sitra\Tests;

use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\SitraServiceClient;

/**
 * Class Base
 *
 * @package Sitra\Tests
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
abstract class Base extends \PHPUnit_Framework_TestCase
{
    protected $defaultOptions = [
        'apiKey' => 'XXX',
        'projectId' => 'XXX',
    ];

    protected $defaultExpected = 'apiKey=XXX&projetId=XXX';

    /** @var \Sitra\ApiClient\SitraServiceClient */
    protected $client;

    /** @var array $config */
    protected $config = [];

    /** @var Description $description */
    protected $description;

    /** @var array $container */
    protected $container = [];



    /**
     * @param int $requestCount
     * @param array $config
     * @return SitraServiceClient
     */
    protected function getClient(int $requestCount = 1, array $config = []) : SitraServiceClient
    {
        $mock = new MockHandler();
        for ($i = 1; $i <= $requestCount; $i++) {
            $mock->append(new Response(200));
        }
        $handlerStack = HandlerStack::create($mock);

        $handlerStack->push(Middleware::history($this->container));

        $config = $config ?: $this->defaultOptions;
        $config['handler'] = $handlerStack;

        return new SitraServiceClient($config);
    }
}
