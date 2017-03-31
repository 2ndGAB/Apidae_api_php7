<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Reference;

/**
 * Class ReferenceTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class ReferenceTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(Reference::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_returns_a_reference_city()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '[ {
                  "id" : 1278,
                  "code" : "03043",
                  "nom" : "Broût-Vernet",
                  "pays" : {
                    "elementReferenceType" : "Pays",
                    "id" : 532,
                    "libelleFr" : "France",
                    "libelleEn" : "France",
                    "libelleEs" : "Francia",
                    "libelleIt" : "Francia",
                    "libelleDe" : "FRANKREICH",
                    "libelleNl" : "FRANKRIJK",
                    "ordre" : 78
                  },
                  "codePostal" : "03110"
                }, {
                  "id" : 30717,
                  "code" : "74140",
                  "nom" : "Habère-Poche",
                  "pays" : {
                    "elementReferenceType" : "Pays",
                    "id" : 532,
                    "libelleFr" : "France",
                    "libelleEn" : "France",
                    "libelleEs" : "Francia",
                    "libelleIt" : "Francia",
                    "libelleDe" : "FRANKREICH",
                    "libelleNl" : "FRANKRIJK",
                    "ordre" : 78
                  },
                  "codePostal" : "74420"
                } ]'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getReferenceCity([
            'query' => [
                'codesInsee' => ["38534", "69388", "74140"]
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"codesInsee":["38534","69388","74140"],"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
            'id' => 1278,
            'code' => '03043',
            'nom' => 'Broût-Vernet',
            'pays' => [
                'elementReferenceType' => 'Pays',
                'id' => 532,
                'libelleFr' => 'France',
                'libelleEn' => 'France',
                'libelleEs' => 'Francia',
                'libelleIt' => 'Francia',
                'libelleDe' => 'FRANKREICH',
                'libelleNl' => 'FRANKRIJK',
                'ordre' => 78,
            ],
            'codePostal' => '03110',
        ], $result[0]);

        $this->assertArraySubset([
            'id' => 30717,
            'code' => '74140',
            'nom' => 'Habère-Poche',
            'pays' => [
                'elementReferenceType' => 'Pays',
                'id' => 532,
                'libelleFr' => 'France',
                'libelleEn' => 'France',
                'libelleEs' => 'Francia',
                'libelleIt' => 'Francia',
                'libelleDe' => 'FRANKREICH',
                'libelleNl' => 'FRANKRIJK',
                'ordre' => 78,
            ],
            'codePostal' => '74420',
        ], $result[1]);
    }

    /**
     * @test
     */
    public function it_returns_a_reference_element()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '[ {
                  "elementReferenceType" : "FeteEtManifestationTheme",
                  "id" : 2338,
                  "libelleFr" : "Cyclotourisme",
                  "libelleEn" : "Cycle tourism",
                  "libelleEs" : "Cicloturismo",
                  "libelleIt" : "Cicloturismo",
                  "libelleDe" : "Radtourismus",
                  "libelleNl" : "Fietstoerisme",
                  "ordre" : 103,
                  "description" : "Idée de loisirs, accessibilité à différents niveaux (découverte, initiation...)",
                  "familleCritere" : {
                    "elementReferenceType" : "FamilleCritere",
                    "id" : 105,
                    "libelleFr" : "Sport",
                    "libelleEn" : "Sport",
                    "libelleEs" : "Deporte",
                    "libelleIt" : "Sport",
                    "libelleDe" : "Sport",
                    "libelleNl" : "Sport",
                    "ordre" : 66
                  },
                  "parent" : {
                    "elementReferenceType" : "FeteEtManifestationTheme",
                    "id" : 2256,
                    "libelleFr" : "Sports cyclistes",
                    "libelleEn" : "Cycle sports",
                    "libelleEs" : "Deporte ciclista",
                    "libelleIt" : "Sport ciclistici",
                    "libelleDe" : "Radsport",
                    "libelleNl" : "Wielersport",
                    "ordre" : 101
                  }
                } ]'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getReferenceElement([
            'query' => [
                "elementReferenceIds" => [2, 118, 2338]
            ]
        ]);


        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"elementReferenceIds":[2,118,2338],"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));
        $this->assertArraySubset([
            'elementReferenceType' => 'FeteEtManifestationTheme',
            'id' => 2338,
            'libelleFr' => 'Cyclotourisme',
            'libelleEn' => 'Cycle tourism',
            'libelleEs' => 'Cicloturismo',
            'libelleIt' => 'Cicloturismo',
            'libelleDe' => 'Radtourismus',
            'libelleNl' => 'Fietstoerisme',
            'ordre' => 103,
            'description' => 'Idée de loisirs, accessibilité à différents niveaux (découverte, initiation...)',
            'familleCritere' => [
                'elementReferenceType' => 'FamilleCritere',
                'id' => 105,
                'libelleFr' => 'Sport',
                'libelleEn' => 'Sport',
                'libelleEs' => 'Deporte',
                'libelleIt' => 'Sport',
                'libelleDe' => 'Sport',
                'libelleNl' => 'Sport',
                'ordre' => 66,
            ],
            'parent' => [
                'elementReferenceType' => 'FeteEtManifestationTheme',
                'id' => 2256,
                'libelleFr' => 'Sports cyclistes',
                'libelleEn' => 'Cycle sports',
                'libelleEs' => 'Deporte ciclista',
                'libelleIt' => 'Sport ciclistici',
                'libelleDe' => 'Radsport',
                'libelleNl' => 'Wielersport',
                'ordre' => 101,
            ],
        ], $result[0]);
    }

    /**
     * @test
     */
    public function it_returns_a_reference_internal_criteria()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '[ {
                  "id" : 1068,
                  "libelle" : "exemple critère 1"
                }, {
                  "id" : 2168,
                  "libelle" : "exemple critère 2",
                  "commentaire" : "commentaire pertinent à propos du critère 2"
                } ]'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getReferenceInternalCriteria([
            'query' => [
                "critereInterneIds" => [ 1068, 2168 ]
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"critereInterneIds":[1068,2168],"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
            'id' => 1068,
            'libelle' => 'exemple critère 1',
        ], $result[0]);
        $this->assertArraySubset([
            'id' => 2168,
            'libelle' => 'exemple critère 2',
            'commentaire' => 'commentaire pertinent à propos du critère 2',
        ], $result[1]);
    }

    /**
     * @test
     */
    public function it_returns_a_reference_selection()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '[ {
                  "id" : 3759,
                  "nom" : "Festivals"
                }, {
                  "id" : 3760,
                  "nom" : "Randonnées depuis St Christophe en Oisans"
                } ]'
            ),
        ]);

        /** @var Result $result */
        $result = $client->getReferenceSelection([
            'query' => [
                "selectionIds" => [  64, 5896 ]
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"selectionIds":[64,5896],"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
            'id' => 3759,
            'nom' => 'Festivals',
        ], $result[0]);
        $this->assertArraySubset([
            'id' => 3760,
            'nom' => 'Randonnées depuis St Christophe en Oisans',
        ], $result[1]);
    }
}
