<?php

namespace Bit8\Client\Resource;
use Bit8\Client\Resource\Converter\JsonDataConverterInterface;

/**
 * Class LocationApi
 * @package Bit8\Client\Resource
 */
class LocationApi extends ApiAbstract
{

    protected static $resourceUrl = 'all';

    protected static $schemaFileName = 'locations.json';


    /**
     * Получить все локации
     * @return mixed
     */
    public function all()
    {
        $response = $this->get(static::$resourceUrl);

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