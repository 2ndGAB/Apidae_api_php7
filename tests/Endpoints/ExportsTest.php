<?php namespace Sitra\Tests\Endpoints;

use GuzzleHttp\Command\Result;
use GuzzleHttp\Psr7\Response;
use Sitra\ApiClient\Description\Exports;

/**
 * Class ExportsTest
 *
 * @package Sitra\Tests\Endpoints
 * @author Stefan Kowalke <blueduck@mailbox.org>
 */
class ExportsTest extends BaseEndpointTestCase
{

    /**
     * Sets the test up
     */
    public function setUp()
    {
        $this->description = $this->importDescription(Exports::$operations);
        parent::setUp();
    }

    /**
     * @test
     * @TODO: Test the export
     */
    public function it_confirms_export()
    {
        $this->markTestIncomplete('Finish the test');
        $exportNotification = array(
            "statut" => "SUCCESS",
            "reinitialisation" => "false",
            "projetId" => "672",
            "urlConfirmation" => "http://api.sitra-tourisme.com/api/v002/export/confirmation?hash=672_20150106-1344_V4BjvT",
            "ponctuel" => "true",
            "urlRecuperation" => "http://export.sitra-tourisme.com/exports/672_20150106-1344_V4BjvT.zip",
        );

        $client = $this->getTestClient([
            new Response(200),
            new Response(200),
        ]);


        $exportFiles = $client->getExportFiles(['url' => $exportNotification['urlRecuperation']]);
        foreach ($exportFiles->name('objets_lies_modifies-14*') as $file) {
             $json = \GuzzleHttp\json_decode($file->getContents(), true);
        }

        /** @var Result $result */
        $confirmation = $client->confirmExport(['hash' => $exportNotification['urlConfirmation']]);

    }

}
