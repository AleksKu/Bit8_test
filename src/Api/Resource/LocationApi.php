<?php

namespace Bit8\Api\Resource;
use Bit8\Api\Resource\Converter\JsonDataConverterInterface;

/**
 * Class LocationApi
 * @package Bit8\Api\Resource
 */
class LocationApi extends ApiAbstract
{

    protected static $resourceUrl = 'all';

    protected static $schemaFileName = 'locations.json';


    /**
     * Получить все локации
     * @return mixed
     */
    public function get()
    {
        $response = $this->client->get(static::$resourceUrl);

        return $this->parse($response);
    }


    /**
     * @param \stdClass $data
     * @return array|mixed
     */
    protected function parseData(\stdClass $data)
    {
        if ($this->dataConverter instanceof JsonDataConverterInterface) {
            return $this->dataConverter->fromJson($data->locations);
        }


        return $data->locations;
    }


}