<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Sso;

/**
 * Class SsoTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class SsoTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(Sso::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_returns_an_sso_token()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                    "access_token":"XXXX-XXXX-XXXX-XXXX-XXXX",
                    "token_type":"bearer",
                    "expires_in":40698,
                    "scope":"api_metadonnees",
                    "refresh_token":"XXXX-XXXX-XXXX-XXXX-XXXX"
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getSsoToken([
            'code' => 'sBaz0b',
            'redirect_uri' => 'http://example.com/TODO'
        ]);

        $this->assertSame('XXXX-XXXX-XXXX-XXXX-XXXX', $result['access_token']);
        $this->assertSame('bearer', $result['token_type']);
        $this->assertSame(40698, $result['expires_in']);
        $this->assertSame('api_metadonnees', $result['scope']);
        $this->assertSame('XXXX-XXXX-XXXX-XXXX-XXXX', $result['refresh_token']);
    }

    /**
     * @test
     */
    public function it_refreshes_an_sso_token()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                    "access_token":"XXXX-XXXX-XXXX-XXXX-XXXX",
                    "token_type":"bearer",
                    "expires_in":40698,
                    "scope":"api_metadonnees",
                    "refresh_token":"XXXX-XXXX-XXXX-XXXX-XXXX"
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->refreshSsoToken([
            'code' => 'sBaz0b',
            'redirect_uri' => 'http://example.com/TODO',
            'refresh_token' => 'XXXX-XXXX-XXXX-XXXX-XXXX'
        ]);

        $this->assertSame('XXXX-XXXX-XXXX-XXXX-XXXX', $result['access_token']);
        $this->assertSame('bearer', $result['token_type']);
        $this->assertSame(40698, $result['expires_in']);
        $this->assertSame('api_metadonnees', $result['scope']);
        $this->assertSame('XXXX-XXXX-XXXX-XXXX-XXXX', $result['refresh_token']);
    }
}
