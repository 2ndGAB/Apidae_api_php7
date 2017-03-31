<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Search;

/**
 * Class SearchTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class SearchTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(Search::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_search_an_object()
    {
        $this->markTestIncomplete();
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "type" : "ACTIVITE",
                  "id" : 6582,
                  "nom" : {
                    "libelleFr" : "Ski de randonnée"
                  },
                  "presentation" : {
                    "descriptifCourt" : {
                      "libelleFr" : "Ski de randonnée : Initiation : Chalune, Bostan, Les Aravis, le Passon, le Tour Noir, Ovronnaz et ensuite les bains thermaux avant de rentrer... Tarif : 60 € par personne + remontées mécaniques si besoin."
                    }
                  }
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchObject(['id' => 6582]);

        $this->assertSame('ACTIVITE', $result['type']);
        $this->assertSame(6582, $result['id']);
        $this->assertArraySubset(['libelleFr' => 'Ski de randonnée'], $result['nom']);
        $this->assertArraySubset(['descriptifCourt' => [
            'libelleFr' => 'Ski de randonnée : Initiation : Chalune, Bostan, Les Aravis, le Passon, le Tour Noir, Ovronnaz et ensuite les bains thermaux avant de rentrer... Tarif : 60 € par personne + remontées mécaniques si besoin.'
        ]], $result['presentation']);

    }

    /**
     * @test
     */
    public function it_search_object_identifier()
    {
        $this->markTestIncomplete();
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "type" : "ACTIVITE",
                  "id" : 6582,
                  "nom" : {
                    "libelleFr" : "Ski de randonnée"
                  },
                  "presentation" : {
                    "descriptifCourt" : {
                      "libelleFr" : "Ski de randonnée : Initiation : Chalune, Bostan, Les Aravis, le Passon, le Tour Noir, Ovronnaz et ensuite les bains thermaux avant de rentrer... Tarif : 60 € par personne + remontées mécaniques si besoin."
                    }
                  }
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchObjectIdentifier(['identifier' => 'SITRA_EVE_123467']);

        $this->assertSame('ACTIVITE', $result['type']);
        $this->assertSame(6582, $result['id']);
        $this->assertArraySubset(['libelleFr' => 'Ski de randonnée'], $result['nom']);
        $this->assertArraySubset(['descriptifCourt' => [
            'libelleFr' => 'Ski de randonnée : Initiation : Chalune, Bostan, Les Aravis, le Passon, le Tour Noir, Ovronnaz et ensuite les bains thermaux avant de rentrer... Tarif : 60 € par personne + remontées mécaniques si besoin.'
        ]], $result['presentation']);

    }
}
