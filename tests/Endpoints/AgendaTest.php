<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Agenda;

/**
 * Class AgendaTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class AgendaTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(Agenda::$operations);
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_search_agenda()
    {
        $this->markTestIncomplete('Finish the test');
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "query" : {
                    "searchFields" : "NOM_DESCRIPTION_CRITERES",
                    "dateDebut" : "2015-03-05",
                    "first" : 0,
                    "count" : 2,
                    "order" : "DATE_OUVERTURE",
                    "asc" : true,
                    "responseFields" : [ "@minimal", "presentation" ],
                    "apiKey" : "dNiOo2xy",
                    "projetId" : 672
                  },
                  "numFound" : 30547,
                  "objetsTouristiques" : [ {
                    "type" : "FETE_ET_MANIFESTATION",
                    "id" : 439520,
                    "nom" : {
                      "libelleFr" : "First Tracks 1800"
                    },
                    "presentation" : {
                      "descriptifCourt" : {
                        "libelleFr" : "La 1ère trace du matin au lever du soleil, ça vous tente ?\r\nPartagez un petit déjeuner et soyez les premiers à parcourir les pistes fraîchement damées, accompagnés par les professionnels du domaine skiable. 10€ avec un forfait de ski en cours de validité."
                      },
                      "descriptifDetaille" : {
                        "libelleFr" : "Tous les jeudis matin, vous serez accueillis par nos équipes au départ d’une remontée mécanique à 7h45 précises pour un accès privilégié avant l\'ouverture du domaine.\r\nAu sommet, vous vivrez un moment magique dans la lumière du soleil levant. Après une présentation du domaine skiable et des principaux sommets alpins, les pisteurs vous accompagneront pour la première trace de la journée et un petit-déjeuner.\r\nRégénérant et idéal pour bien commencer la journée !\r\nTarif : 10€ avec un forfait de ski en cours de validité.\r\nRenseignements et inscriptions dans les points de vente des forfaits de ski ou au chalet d\'information Col de la Chal."
                      }
                    }
                  }, {
                    "type" : "FETE_ET_MANIFESTATION",
                    "id" : 613928,
                    "nom" : {
                      "libelleFr" : "Rencontres de l\'Avenir Professionnel"
                    },
                    "presentation" : {
                      "descriptifCourt" : {
                        "libelleFr" : "C\'est l’événement incontournable de l’orientation scolaire sur le Bassin d’Annecy. De nombreux professionnels de tous horizons (environ 200) viendront présenter leur métier à plus de 3100 jeunes du Grand Annecy."
                      },
                      "typologiesPromoSitra" : [ {
                        "elementReferenceType" : "TypologiePromoSitra",
                        "id" : 1669,
                        "libelleFr" : "Indoor (intérieur)",
                        "ordre" : 5,
                        "description" : "Concerne tous les objets ACT, LOI, EVE, ... qui se déroulent en intérieur"
                      }, {
                        "elementReferenceType" : "TypologiePromoSitra",
                        "id" : 1670,
                        "libelleFr" : "Recommandé par mauvais temps",
                        "ordre" : 18,
                        "description" : "Equipement couvert ou pratique sans danger pour les vacanciers même s\'il pleut\r\nPeut concerner \r\nPCU : musées ...\r\nACT : rafting ...\r\nEVE : concert dans une salle ou sous une halle"
                      } ]
                    }
                  } ],
                  "formatVersion" : "v002"
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchAgenda([
            'query' => [
                "searchQuery" => "vélo"
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"searchQuery":"v\u00e9lo","count":20,"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));
    }

    /**
     * @test
     */
    public function it_search_agenda_identifier()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "query" : {
                    "searchFields" : "NOM_DESCRIPTION_CRITERES",
                    "dateDebut" : "2015-03-05",
                    "first" : 0,
                    "count" : 2,
                    "order" : "DATE_OUVERTURE",
                    "asc" : true,
                    "responseFields" : [ "@minimal", "presentation" ],
                    "apiKey" : "dNiOo2xy",
                    "projetId" : 672
                  },
                  "numFound" : 30547,
                  "objetTouristiqueIds" : [ 439520, 613928 ],
                  "formatVersion" : "v002"
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchAgendaIdentifier([
            'query' => [
                "searchQuery" => "vélo"
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"searchQuery":"v\u00e9lo","count":20,"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
              'searchFields' => 'NOM_DESCRIPTION_CRITERES',
              'dateDebut' => '2015-03-05',
              'first' => 0,
              'count' => 2,
              'order' => 'DATE_OUVERTURE',
              'asc' => true,
              'responseFields' => [ '@minimal', 'presentation' ],
              'apiKey' => 'dNiOo2xy',
              'projetId' => 672,
        ], $result['query']);

        $this->assertArraySubset([439520, 613928], $result['objetTouristiqueIds']);
        $this->assertEquals(30547, $result['numFound']);
        $this->assertEquals('v002', $result['formatVersion']);
    }

    /**
     * @test
     */
    public function it_search_detailed_agenda()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "query" : {
                    "searchFields" : "NOM_DESCRIPTION_CRITERES",
                    "dateDebut" : "2015-03-05",
                    "first" : 0,
                    "count" : 2,
                    "order" : "IDENTIFIANT",
                    "asc" : true,
                    "responseFields" : [ "@minimal", "presentation" ],
                    "apiKey" : "dNiOo2xy",
                    "projetId" : 672
                  },
                  "numFound" : 2962433,
                  "objetsTouristiques" : {
                    "2015-03-05" : [ {
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
                    }, {
                      "type" : "ACTIVITE",
                      "id" : 6695,
                      "nom" : {
                        "libelleFr" : "Balade en traîneau à chiens"
                      },
                      "presentation" : {
                        "descriptifCourt" : {
                          "libelleFr" : "Une balade contemplative, pour le plaisir de la glisse. Confortablement installé dans un grand traîneau tiré par un groupe de chiens, promenade en lisière du Parc national de la Vanoise."
                        },
                        "descriptifDetaille" : {
                          "libelleFr" : "Avec Emmanuel comme musher, venez vous évader au cœur du Site Nordique, installés confortablement dans un grand traîneau privatif de 3 personnes (2  adultes et 1 enfant). Les chiens vous emmèneront en limite du Parc National de la Vanoise et si la météo le permet, vous observerez avec un peu de chance, à la longue vue : des chamois, des bouquetins ainsi que le gypaète barbu."
                        },
                        "typologiesPromoSitra" : [ {
                          "elementReferenceType" : "TypologiePromoSitra",
                          "id" : 1668,
                          "libelleFr" : "Fiche de présentation générale",
                          "ordre" : 2
                        } ]
                      }
                    } ]
                  }
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchDetailedAgenda([
            'query' => [
                "searchQuery" => "vélo"
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"searchQuery":"v\u00e9lo","count":20,"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
              'searchFields' => 'NOM_DESCRIPTION_CRITERES',
              'dateDebut' => '2015-03-05',
              'first' => 0,
              'count' => 2,
              'order' => 'IDENTIFIANT',
              'asc' => true,
              'responseFields' => [ '@minimal', 'presentation' ],
              'apiKey' => 'dNiOo2xy',
              'projetId' => 672,
        ], $result['query']);

        $this->assertEquals(2962433, $result['numFound']);
    }

    /**
     * @test
     */
    public function it_search_detailed_agenda_identifier()
    {
        $client = $this->getTestClient([
            new Response(
                200,
                [],
                '{
                  "query" : {
                    "searchFields" : "NOM_DESCRIPTION_CRITERES",
                    "dateDebut" : "2015-03-05",
                    "first" : 0,
                    "count" : 2,
                    "order" : "IDENTIFIANT",
                    "asc" : true,
                    "responseFields" : [ "@minimal", "presentation" ],
                    "apiKey" : "dNiOo2xy",
                    "projetId" : 672
                  },
                  "numFound" : 2962432,
                  "objetTouristiqueIds" : {
                    "2015-03-05" : [ 6582, 6695 ]
                  }
                }'
            ),
        ]);

        /** @var Result $result */
        $result = $client->searchDetailedAgendaIdentifier([
            'query' => [
                "searchQuery" => "vélo"
            ]
        ]);

        $lastRequest = array_pop($this->container)['request'];
        $this->assertSame('query={"searchQuery":"v\u00e9lo","count":20,"apiKey":"XXX","projetId":0}', urldecode((string) $lastRequest->getBody()));

        $this->assertArraySubset([
              'searchFields' => 'NOM_DESCRIPTION_CRITERES',
              'dateDebut' => '2015-03-05',
              'first' => 0,
              'count' => 2,
              'order' => 'IDENTIFIANT',
              'asc' => true,
              'responseFields' => [ '@minimal', 'presentation' ],
              'apiKey' => 'dNiOo2xy',
              'projetId' => 672,
        ], $result['query']);

        $this->assertArraySubset(['2015-03-05' => [6582, 6695]], $result['objetTouristiqueIds']);
        $this->assertEquals(2962432, $result['numFound']);
    }
}
