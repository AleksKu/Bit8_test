<?php

namespace Bit8\Client\Resource;

use Psr\Http\Message\ResponseInterface;
use Webmozart\Json\JsonDecoder;
use Bit8\Exception\InvalidJsonSchemaException;
use Bit8\Exception\ApiErrorException;
use Bit8\Client;

abstract class ApiAbstract
{

    protected $resourceUrl;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function get($path)
    {
        $response = $this->client->getHttpClient()->request('GET', $path);
        return $response;
    }


    /**
     * @param ResponseInterface $response
     * @throws InvalidJsonSchemaException
     * @throws \Webmozart\Json\ValidationFailedException
     */
    protected function parse(ResponseInterface $response)
    {
        $decoder = new JsonDecoder();

        $responseObj = $decoder->decode($response->getBody()->getContents());

        $this->validateSchema($responseObj);

        return $this->parseData($responseObj->data);


    }
    
    protected abstract function parseData(\stdClass $data);
    
    protected abstract function validateDataSchema(\stdClass $data);

    /**
     * @param $responseObj
     * @throws InvalidJsonSchemaException
     */
    protected function validateSchema($responseObj)
    {
        if (!property_exists($responseObj, 'success') || !property_exists($responseObj, 'data'))
            throw new InvalidJsonSchemaException('xxx');


        if ($responseObj->success == false)
            $this->parseError($responseObj);


        $data = $responseObj->data;

       $this->validateDataSchema($data);


    }

    /**
     * @param $responseObj
     * @throws ApiErrorException
     * @throws InvalidJsonSchemaException
     */
    public function parseError($responseObj)
    {

        $data = $responseObj->data;
        if (!property_exists($data, 'message') || !property_exists($data, 'code'))
            throw new InvalidJsonSchemaException('xxx');

        throw new ApiErrorException($data->message, $data->code);
    }


    
}