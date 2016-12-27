<?php

namespace Sitra\ApiClient\Description;

use GuzzleHttp\Query;
use Sitra\ApiClient\Exception\InvalidMetadataFormatException;
use Sitra\ApiClient\Middleware\AuthenticationHandler;

class Metadata
{
    const ALLOWED_KEY_REGEX = '/^(membre(s|$)(\.membre_\d+)?)$|^(projet(s|$)(\.projet_\d+)?)$|^general$|^node$/';

    public static $operations = array(
        // @see http://dev.apidae-tourisme.com/fr/documentation-technique/v2/metadonnees
        'getMetadata' => [
            'httpMethod' => 'GET',
            'uri' => '/api/v002/metadata/{referenceId}/{nodeId}{/targetType}{/targetId}',
            'responseModel' => 'getResponse',
            'parameters' => [
                'referenceId' => [
                    'type' => 'integer',
                    'location' => 'uri',
                    'required' => true,
                ],
                'nodeId' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true,
                ],
                'targetType' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => false,
                ],
                'targetId' => [
                    'type' => 'integer',
                    'location' => 'uri',
                    'required' => false,
                ],
            ],
            'data' => [
                'scope' => AuthenticationHandler::META_SCOPE,
            ],
        ],
        'deleteMetadata' => [
            'httpMethod' => 'DELETE',
            'uri' => '/api/v002/metadata/{referenceId}/{nodeId}{/targetType}{/targetId}',
            'responseModel' => 'getResponse',
            'parameters' => [
                'referenceId' => [
                    'type' => 'integer',
                    'location' => 'uri',
                    'required' => true,
                ],
                'nodeId' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true,
                ],
                'targetType' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => false,
                ],
                'targetId' => [
                    'type' => 'integer',
                    'location' => 'uri',
                    'required' => false,
                ],
            ],
            'data' => [
                'scope' => AuthenticationHandler::META_SCOPE,
            ],
        ],
        'putMetadata' => [
            'httpMethod' => 'PUT',
            'uri' => '/api/v002/metadata/{referenceId}/{nodeId}{/targetType}{/targetId}',
            'responseModel' => 'getResponse',
            'parameters' => [
                'referenceId' => [
                    'type' => 'integer',
                    'location' => 'uri',
                    'required' => true,
                ],
                'nodeId' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true,
                ],
                'metadata' => [
                    'required' => true,
                    'location' => 'body',
                    'filters' => [
                        '\Sitra\ApiClient\Description\Metadata::validateMetadata',
                    ],
                ],
            ],
            'data' => [
                'scope' => AuthenticationHandler::META_SCOPE,
            ],
        ],
    );


    /**
     * @param $metadata
     * @return string
     */
    public static function validateMetadata($metadata) : string
    {
        if (empty($metadata)) {
            throw new InvalidMetadataFormatException();
        }

        if (is_array($metadata)) {
            foreach ($metadata as $name => $data) {
                if (preg_match(self::ALLOWED_KEY_REGEX, $name) === 0) {
                    throw new InvalidMetadataFormatException();
                }

                if (is_array($data)) {
                    $metadata[$name] = json_encode($data);
                } elseif (empty($data)) {
                    throw new InvalidMetadataFormatException();
                }
            }
        }

        return \GuzzleHttp\Psr7\build_query($metadata);
    }
}
