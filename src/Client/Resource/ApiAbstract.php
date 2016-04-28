<?php

namespace Bit8\Client\Resource;

use Psr\Http\Message\ResponseInterface;
use Webmozart\Json\JsonDecoder;
use Webmozart\Json\JsonValidator;

use Bit8\Exception\InvalidJsonSchemaException;
use Bit8\Exception\ApiErrorException;
use Bit8\Client;
use Bit8\Client\Resource\Converter\JsonDataConverterInterface;


/**
 * Class ApiAbstract
 * @package Bit8\Client\Resource
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


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * GET запрос
     * @param $path
     * @return ResponseInterface
     */
    public final function get($path)
    {
        $response = $this->client->getHttpClient()->request('GET', $path);
        return $response;
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
     * Парсинг ответа
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

    /**
     * Парсинг данных из ответа
     * @param \stdClass $data
     * @return mixed
     */
    protected abstract function parseData(\stdClass $data);


    /**
     * Валидация json схемы
     * @param $responseObj
     * @throws InvalidJsonSchemaException
     */
    protected function validateSchema($responseObj)
    {

        $validator = new JsonValidator();
        $errors = $validator->validate($responseObj, $this->client->schemaPath(static::$schemaFileName));


        if (count($errors) > 0)
            throw new InvalidJsonSchemaException(implode(',,, ', $errors));


        if ($responseObj->success == false)
            $this->parseError($responseObj);


    }

    /**
     * Обработка ошибок Api
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