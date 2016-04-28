<?php

namespace Bit8;

use Bit8\Client\Resource\Factory;
use GuzzleHttp\ClientInterface;


/**
 * Class Client
 * @package Bit8
 */
class Client
{

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    public static $httpDefaultClient = \GuzzleHttp\Client::class;

    /**
     * базовый url API
     * @var null|string
     */
    protected $baseUri = 'http://localhost/';


    /**
     * Client constructor.
     * @param null $baseUri
     * @param ClientInterface|null $httpClient
     */
    public function __construct($baseUri = null, ClientInterface $httpClient = null)
    {

        if ($baseUri !== null)
            $this->baseUri = $baseUri;


        if ($httpClient instanceof ClientInterface) {
            $this->setHttpClient($httpClient);

        } else {
            $this->setHttpClient(new static::$httpDefaultClient(['base_uri' => $this->baseUri]));

        }


    }

    /**
     * Объект API ресурса
     * @param $type
     * @return Client\Resource\ApiAbstract
     */
    public function resource($type)
    {
        return Factory::create($type, $this);
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }


    public function authenticate()
    {


    }


    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }


    /**
     * Путь до файла схемы
     * @param $resourceSchemaName
     * @return string
     */
    public function schemaPath($resourceSchemaName)
    {
        return __DIR__ . '/schemas/' . $resourceSchemaName;
    }


}