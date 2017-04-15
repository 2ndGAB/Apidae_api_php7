<?php

// Include Composer autoload
include __DIR__."/../vendor/autoload.php";

// Create the client
$client = new \Sitra\ApiClient\SitraServiceClient([
    'apiKey'        => 'XXX',
    'projectId'     => 000,
    'baseUrl'       => 'http://api.sitra-tourisme.com/',
]);

try {
    /*
     * Export
     */

    // Notifications come from a $_POST from Apidae
    $exportNotification = array(
        "statut" => "SUCCESS",
        "reinitialisation" => "false",
        "projetId" => "672",
        "urlConfirmation" => "http://api.sitra-tourisme.com/api/v002/export/confirmation?hash=1292_20161226-0012_wvl2gA",
        "ponctuel" => "true",
        "urlRecuperation" => 'http://export.sitra-tourisme.com/exports/1292_20161226-0012_wvl2gA.zip',
    );

    $exportFiles = $client->getExportFiles(['url' => $exportNotification['urlRecuperation']]);
    foreach ($exportFiles->name('objets_lies_modifies-*') as $file) {
        var_dump($file->getRealpath());

        // If you use XML (Apidae settings)
        //$xml = simplexml_load_string($file->getContents());
        //print_r($xml);

         //If you use JSON
         //$json = \GuzzleHttp\json_decode($file->getContents(), true);
         //print_r($json);
    }

    $confirmation = $client->confirmExport(['hash' => $exportNotification['urlConfirmation']]);
    var_dump($confirmation);

    /*
     * Reference
     */
    $cities = $client->getReferenceCity(['query' => [ 'codesInsee' => ["38534", "69388", "74140"] ]]);
    var_dump(count($cities));

    $elements = $client->getReferenceElement(['query' => [ "elementReferenceIds" => [2, 118, 2338] ]]);
    var_dump(count($elements));

    $elements = $client->getReferenceInternalCriteria(['query' => [ "critereInterneIds" => [ 1068, 2168 ] ]]);
    var_dump(count($elements));

    $elements = $client->getReferenceSelection(['query' => [ "selectionIds" => [  64, 5896 ] ]]);
    var_dump(count($elements));
} catch (\Sitra\ApiClient\Exception\SitraException $e) {
    echo $e->getMessage();
    echo "\n";
    echo $e->getPrevious()->getMessage();
}
