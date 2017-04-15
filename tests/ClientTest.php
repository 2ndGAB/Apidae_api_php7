<?php

namespace Sitra\Tests;

use GuzzleHttp\Command\CommandInterface;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\ResultInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MongoDB\Driver\Exception\InvalidArgumentException;
use Sitra\ApiClient\SitraServiceClient;

/**
 * Class ClientTest
 *
 * @package Sitra\Tests
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class ClientTest extends Base
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $path = __DIR__ . '/Assets/description.json';
        $json = json_decode(file_get_contents($path), true);
        $this->description = new Description($json);

        $handlerStack = HandlerStack::create(new CurlHandler());
        $config['handler'] = $handlerStack;

        $this->client = new SitraServiceClient($config, $this->description);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No operation found named HelloWorld
     */
    public function get_command_throws_invalid_argument_exception_when_operation_is_not_valid()
    {
        $this->client->getCommand('HelloWorld');
    }

    /**
     * @test
     * @expectedException \GuzzleHttp\Command\Exception\CommandException
     * @expectedExceptionMessage Validation errors: [foo] is a required string: Provides the foo endpoint
     */
    public function get_command_throws_exception_when_parameter_is_not_valid_for_operation()
    {
        $this->client->Testing(['HelloWorld']);
    }

    /**
     * @test
     */
    public function get_command_returns_new_command()
    {
        $command = $this->client->getCommand('Testing', ['foo' => 'bar']);
        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    /**
     * @test
     */
    public function execute_client_returns_valid_result_when_status_code_is_200()
    {
        $mock = new MockHandler([
            new Response(200, [], '{
              "TestingResponse": {
                "status": {
                  "code": 200,
                  "content": "OK"
                }
              }
            }')
        ]);
        $stack = HandlerStack::create($mock);
        $this->config['handler'] = $stack;
        $this->client = new SitraServiceClient($this->config, $this->description);
        $command = $this->client->getCommand('Testing', ['foo' => 'bar']);
        $result = $this->client->execute($command);
        $this->assertInstanceOf(ResultInterface::class, $result);
    }

    /**
     * @test
     * @expectedException \Sitra\ApiClient\Exception\SitraException
     */
    public function execute_client_throws_server_exception_when_status_code_is_500()
    {
        $mock = new MockHandler([new Response(500)]);
        $stack = HandlerStack::create($mock);
        $this->config['handler'] = $stack;
        $this->client = new SitraServiceClient($this->config, $this->description);
        $command = $this->client->getCommand('Testing', ['foo' => 'bar']);
        $this->client->execute($command);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function execute_client_throws_exception_when_unknown_error_occurs()
    {
        // Provoke an OutOfBoundException to simulate an unkown exception for the client
        $mock = new MockHandler([]);
        $stack = HandlerStack::create($mock);
        $this->config['handler'] = $stack;
        $this->client = new SitraServiceClient($this->config, $this->description);
        $command = $this->client->getCommand('Testing', ['foo' => 'bar']);
        $this->client->execute($command);
    }

    /**
     * @test
     */
    public function test_client()
    {
        $client = $this->getClient(3);

        // Make a request
        $client->getObjectById(['id' => 1]);

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];

        $this->assertEquals("/api/v002/objet-touristique/get-by-id/1", $lastRequest->getUri()->getPath());
        $this->assertEquals($this->defaultExpected, $lastRequest->getUri()->getQuery());


        // Make a request
        $client->getObjectById(['id' => 888]);

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];

        $this->assertEquals("/api/v002/objet-touristique/get-by-id/888", $lastRequest->getUri()->getPath());
        $this->assertEquals($this->defaultExpected, $lastRequest->getUri()->getQuery());

        // Make a request
        $client->getObjectByIdentifier(['identifier' => 'toto']);

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/objet-touristique/get-by-identifier/toto", $lastRequest->getUri()->getPath());
        $this->assertEquals($this->defaultExpected, $lastRequest->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function test_sso_url()
    {
        $client = $this->getClient(
            1,
            [
                'ssoRedirectUrl' => 'http://example.com/TODO',
                'ssoClientId'    => 'XXX',
                'ssoSecret'      => 'XXX',
            ]);

        $this->assertSame(
            'http://base.sitra-tourisme.com/oauth/authorize?response_type=code&client_id=XXX&redirect_uri=http://example.com/TODO&scope=sso',
            urldecode($client->getSsoUrl())
        );
    }
}
