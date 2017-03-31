<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Metadata;
use Sitra\ApiClient\Middleware\AuthenticationHandler;

/**
 * Class MetadataTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class MetadataTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->client = $this->getClient(6, [
            'apiKey' => 'XXX',
            'projectId' => 'XXX',
            'accessTokens' => [
                AuthenticationHandler::META_SCOPE => 'TEST',
            ],
        ]);

        $this->description = $this->importDescription(Metadata::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_gets_metadata()
    {
        $this->client->getMetadata(['referenceId' => 123457, 'nodeId' => 'jolicode']);

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('', (string) $lastRequest->getBody());
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('GET', $lastRequest->getMethod());

    }

    /**
     * @test
     */
    public function it_deletes_metadata()
    {
        $this->client->deleteMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'targetType' => 'membre',
            'targetId' => 21
        ]);
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode/membre/21", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('', (string) $lastRequest->getBody());
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('DELETE', $lastRequest->getMethod());
    }

    /**
     * @test
     */
    public function it_puts_metadata()
    {
        $this->client->putMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'metadata' => [
                'general' => '{"infoGenerale":"FooBar"}',
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('metadata=general={"infoGenerale":"FooBar"}', urldecode((string) $lastRequest->getBody()));
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('PUT', $lastRequest->getMethod());

        $this->client->putMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'metadata' => [
                'membres.membre_21' => '{"Foo":"Bar"}',
            ]
        ]);
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('metadata=membres.membre_21={"Foo":"Bar"}', urldecode((string) $lastRequest->getBody()));
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('PUT', $lastRequest->getMethod());

        $this->client->putMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'metadata' => [
                'node' => json_encode([
                    'general' => json_encode(['toto' => true, 'foo' => 'bar']),
                    'membres' => ([
                        ['targetId' => 111, 'jsonData' => json_encode(['foo' => 'barbar'])]
                    ]),
                ])
            ]
        ]);
        $expected = 'metadata=node={"general":"{\"toto\":true,\"foo\":\"bar\"}","membres":[{"targetId":111,"jsonData":"{\"foo\":\"barbar\"}"}]}';
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals($expected, urldecode((string) $lastRequest->getBody()));
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('PUT', $lastRequest->getMethod());

        $this->client->putMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'metadata' => [
                'membres' => '[{"targetId": 21, "jsonData": "{ \"foo\": \"bar\", \"bar\": 691 }" }, { "targetId": 12, "jsonData": "{ \"bar\": \"foo\" }" } ]'
            ]
        ]);
        $expected = 'metadata=membres=[{"targetId": 21, "jsonData": "{ \"foo\": \"bar\", \"bar\": 691 }" }, { "targetId": 12, "jsonData": "{ \"bar\": \"foo\" }" } ]';
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/metadata/123457/jolicode", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals($expected, urldecode((string) $lastRequest->getBody()));
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('PUT', $lastRequest->getMethod());

    }

    public function wrongJson()
    {
        return [
            [['test' => '{"infoGenerale":"FooBar"}',],],
            [['Membre' => '[{"foo\" }" } ]',],],
            [['membres.club' => '{"Foo":"Bar"}',],],
            [['membres.coucou_1' => '{"Foo":"Bar"}',],],
            [['membres.membre_1' => false,],],
        ];
    }


    /**
     * @expectedException \Sitra\ApiClient\Exception\InvalidMetadataFormatException
     * @dataProvider wrongJson
     * @param $data
     */
    public function testWrongData($data)
    {
        $client = $this->getClient(1, [
            'apiKey' => 'XXX',
            'projectId' => 'XXX',
            'accessTokens' => array(
                AuthenticationHandler::META_SCOPE => 'TEST'
            ),
        ]);

        $client->putMetadata([
            'referenceId' => 123457,
            'nodeId' => 'jolicode',
            'metadata' => $data
        ]);
    }
}
