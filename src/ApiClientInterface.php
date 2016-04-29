<?php

namespace Bit8;

use Bit8\Api\ApiAbstract;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;


/**
 * Class Client
 * @package Bit8
 */
interface ApiClientInterface
{
    /**
     *
     * @param $type
     * @return ApiAbstract
     */
    public function api($type);

    /**
     * @return ClientInterface
     */
    public function getHttpClient();

    /**
     * @param ClientInterface $httpClient
     * @return ClientInterface
     */
    public function setHttpClient(ClientInterface $httpClient);

    /**
     *
     * @return ClientInterface
     */
    public function authenticate();

    /**
     * @return string
     */
    public function getBaseUri();

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri);

    /**
     * Путь до файла схемы
     * @param $resourceSchemaName
     * @return string
     */
    public function schemaPath($resourceSchemaName);

    /**
     * Send a GET request.
     * @param $path
     * @return ResponseInterface
     */
    public function get($path);

    /**
     * Send a POST request.
     * @param $path
     * @param null $body
     * @return ResponseInterface
     */
    public function post($path, $body = null);

    /**
     * Send a PATH request.
     * @param $path
     * @param null $body
     * @return ResponseInterface
     */
    public function patch($path, $body = null);

    /**
     * Send a DELETE request.
     * @param $path
     * @param null $body
     * @return ResponseInterface
     */
    public function delete($path, $body = null);

    /**
     * Send a PUT request.
     * @param $path
     * @param $body
     * @return ResponseInterface
     */
    public function put($path, $body);
}