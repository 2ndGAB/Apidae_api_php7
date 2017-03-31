<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\User;
use Sitra\ApiClient\Middleware\AuthenticationHandler;

/**
 * Class UserTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class UserTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(User::$operations);
        $this->config += [
            'accessTokens' => [
                AuthenticationHandler::SSO_SCOPE => 'TEST',
            ],
        ];
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_gets_an_user_profile()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "id" : 2,
                  "firstName" : "Jane",
                  "lastName" : "Doe",
                  "email" : "jane.doe@example.com"
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getUserProfile();

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/sso/utilisateur/profil", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('', (string) $lastRequest->getBody());
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('GET', $lastRequest->getMethod());

        $this->assertEquals(2, $result['id']);
        $this->assertEquals('Jane', $result['firstName']);
        $this->assertEquals('Doe', $result['lastName']);
        $this->assertEquals('jane.doe@example.com', $result['email']);
    }

    /**
     * @test
     */
    public function it_returns_user_permissions_on_object()
    {
        $client = $this->getTestClient([
            new Response(200),
        ]);

        /** @var Result $result */
        $result = $client->getUserPermissionOnObject(['id' => 123457]);

        /** @var Request $lastRequest */
        $lastRequest = array_pop($this->container)['request'];
        $this->assertEquals("/api/v002/sso/utilisateur/autorisation/objet-touristique/modification/123457", $lastRequest->getUri()->getPath());
        $this->assertEquals('', $lastRequest->getUri()->getQuery());
        $this->assertEquals('', (string) $lastRequest->getBody());
        $this->assertArraySubset([0 => 'Bearer TEST'], $lastRequest->getHeader('Authorization'));
        $this->assertEquals('GET', $lastRequest->getMethod());
    }
}
