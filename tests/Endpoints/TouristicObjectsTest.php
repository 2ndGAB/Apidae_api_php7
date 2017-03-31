<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\TouristicObjects;

/**
 * Class TouristicObjectsTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class TouristicObjectsTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(TouristicObjects::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_returns_an_object_by_id()
    {
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
        $result = $client->getObjectById(['id' => 6582]);

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
    public function it_returns_an_object_by_Identifier()
    {
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
        $result = $client->getObjectByIdentifier(['identifier' => 'SITRA_EVE_123467']);

        $this->assertSame('ACTIVITE', $result['type']);
        $this->assertSame(6582, $result['id']);
        $this->assertArraySubset(['libelleFr' => 'Ski de randonnée'], $result['nom']);
        $this->assertArraySubset(['descriptifCourt' => [
            'libelleFr' => 'Ski de randonnée : Initiation : Chalune, Bostan, Les Aravis, le Passon, le Tour Noir, Ovronnaz et ensuite les bains thermaux avant de rentrer... Tarif : 60 € par personne + remontées mécaniques si besoin.'
        ]], $result['presentation']);

    }
}
