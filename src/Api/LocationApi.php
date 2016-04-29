<?php

namespace Bit8\Api;
use Bit8\Api\Converter\JsonDataConverterInterface;

/**
 * Class LocationApi
 * @package Bit8\Api
 */
class LocationApi extends ApiAbstract
{

    protected  $resourceUrl = 'locations';

    protected static $schemaFileName = 'locations.json';


    /**
     * get all Locations
     * @return mixed
     */
    public function get()
    {
        $response = $this->client->get($this->resourceUrl);

        return $this->parse($response);
    }


    /**
     * @param \stdClass $data
     * @return array|mixed
     */
    public function parseData(\stdClass $data)
    {
        if ($this->dataConverter instanceof JsonDataConverterInterface) {
            return $this->dataConverter->fromJson($data->locations);
        }


        return $data->locations;
    }


}