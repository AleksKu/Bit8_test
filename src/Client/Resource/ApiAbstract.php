<?php

namespace Bit8\Client\Resource;


use Bit8\Client;

abstract class ApiAbstract
{

    protected $resourceUrl;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    
}