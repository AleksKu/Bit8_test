<?php
/**
 * Created by PhpStorm.
 * User: newage
 * Date: 28.04.16
 * Time: 18:39
 */
namespace Bit8\Api;

use Bit8\Api\Converter\JsonDataConverterInterface;
use Bit8\Exception\ApiErrorException;
use Bit8\Exception\InvalidJsonSchemaException;
use Psr\Http\Message\ResponseInterface;


/**
 * Class ApiAbstract
 * @package Bit8\Api
 */
interface ApiInterface
{
    /**
     * @param JsonDataConverterInterface $converter
     * @return $this
     */
    public function useDataConverter(JsonDataConverterInterface $converter);

    /**
     * Send a GET request
     * @return mixed
     */
    public function get();

    /**
     * parse json response
     * @param ResponseInterface $response
     * @throws InvalidJsonSchemaException
     * @throws \Webmozart\Json\ValidationFailedException
     * @return mixed
     */
    public function parse(ResponseInterface $response);

    /**
     * parse json data
     * @param \stdClass $data
     * @return mixed
     */
    public function parseData(\stdClass $data);

    /**
     * validate json schema
     * @param $responseObj
     * @throws InvalidJsonSchemaException
     */
    public function validateSchema(\stdClass $responseObj);

    /**
     * parse error response
     * @param $responseObj
     * @throws ApiErrorException
     * @throws InvalidJsonSchemaException
     */
    public function parseError(\stdClass $responseObj);
}