<?php

namespace Bit8\Client\Resource;


use Bit8\Exception\InvalidJsonSchemaException;

class LocationApi extends ApiAbstract
{


    public function all()
    {
        $response = $this->get('all');

        return $this->parse($response);
    }


    protected function parseData(\stdClass $data)
    {
        return $data->locations;
    }

    protected function validateDataSchema(\stdClass $data)
    {
        if (!property_exists($data, 'locations')) {
            throw new InvalidJsonSchemaException('xxx');
        }

        $locations = $data->locations;

        if (!is_array($locations))
            throw new InvalidJsonSchemaException('xxx');
    }


}