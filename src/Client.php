<?php

namespace Bit8;

use Bit8\Client\Resource\Factory;
use GuzzleHttp\ClientInterface;

use Bit8\Exception\InvalidArgumentException;

class Client
{

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    protected $baseUri = 'http://localhost/{version}/';

    const API_VERSION = '1.1';

    public function __construct($baseUri = null, ClientInterface $httpClient = null)
    {

        if($baseUri !== null)
            $this->baseUri = $baseUri;

        if($httpClient === null)
        {
            $this->setHttpClient(new \GuzzleHttp\Client(['base_uri'=> $this->baseUri]));
        } else {
            $this->setHttpClient($httpClient);
        }


    }

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
        // Sign all requests with the OauthPlugin
  /*      $this->getHttpClient()->addSubscriber(new Guzzle\Plugin\Oauth\OauthPlugin(array(
            'consumer_key'  => '***',
            'consumer_secret' => '***',
            'token'       => '***',
            'token_secret'  => '***'
        )));*/

    }

    public function get()
    {
        $response = $this->getHttpClient()->get('http://httpbin.org/get');
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


}