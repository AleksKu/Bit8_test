<?php

namespace Bit8\Api\Resource;

use Psr\Http\Message\ResponseInterface;
use Webmozart\Json\JsonDecoder;
use Webmozart\Json\JsonValidator;

use Bit8\Exception\InvalidJsonSchemaException;
use Bit8\Exception\ApiErrorException;
use Bit8\Client;
use Bit8\Api\Resource\Converter\JsonDataConverterInterface;


/**
 * Class ApiAbstract
 * @package Bit8\Api\Resource
 */
abstract class ApiAbstract
{


    /**
     * @var string
     */
    protected static $resourceUrl;

    /**
     * @var string
     */
    protected static $schemaFileName;

    /**
     * @var string
     */
    protected static $errorSchemaFileName = 'error.json';

    /**
     *
     * @var null|JsonDataConverterInterface
     */
    protected $dataConverter = null;


    /**
     * ApiAbstract constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @param JsonDataConverterInterface $converter
     * @return $this
     */
    public function useDataConverter(JsonDataConverterInterface $converter)
    {
        $this->dataConverter = $converter;
        return $this;

    }

    /**
     * Send a GET request
     * @return mixed
     */
    abstract public function get();


    /**
     * parse json response
     * @param ResponseInterface $response
     * @throws InvalidJsonSchemaException
     * @throws \Webmozart\Json\ValidationFailedException
     * @return mixed
     */
    protected function parse(ResponseInterface $response)
    {
        $decoder = new JsonDecoder();

        $responseObj = $decoder->decode($response->getBody()->getContents());

        $this->validateSchema($responseObj);

        return $this->parseData($responseObj->data);


    }

    /**
     * parse json data
     * @param \stdClass $data
     * @return mixed
     */
    protected abstract function parseData(\stdClass $data);


    /**
     * validate json schema
     * @param $responseObj
     * @throws InvalidJsonSchemaException
     */
    protected function validateSchema(\stdClass $responseObj)
    {

        $validator = new JsonValidator();
        $errors = $validator->validate($responseObj, $this->client->schemaPath(static::$schemaFileName));


        if (count($errors) > 0)
            throw new InvalidJsonSchemaException(implode(',,, ', $errors));


        if ($responseObj->success == false)
            $this->parseError($responseObj);


    }

    /**
     * parse error response
     * @param $responseObj
     * @throws ApiErrorException
     * @throws InvalidJsonSchemaException
     */
    public function parseError(\stdClass $responseObj)
    {

        $validator = new JsonValidator();
        $errors = $validator->validate($responseObj, $this->client->schemaPath(static::$errorSchemaFileName));


        if (count($errors) > 0)
            throw new InvalidJsonSchemaException(implode(',,, ', $errors));


        $data = $responseObj->data;

        throw new ApiErrorException($data->message, $data->code);
    }


}