<?php


namespace Bit8\Client\Resource;


use Bit8\Client;

/**
 * Class Factory
 * @package Bit8\Client\Resource
 */
class Factory
{


    /**
     * @param $type
     * @param Client $client
     * @return ApiAbstract
     */
    public static function create($type, Client $client)
    {


        $type = __NAMESPACE__ . '\\' . ucwords($type) . "Api";
        if (class_exists($type)) {
            $resource = new $type($client);
            return $resource;
        } else {
            throw new \InvalidArgumentException("Invalid resource api type given.");
        }
    }

    public static function createConverter($type)
    {
        $type = __NAMESPACE__ . '\\Converter\\' . ucwords($type) . "JsonConverter";
        if (class_exists($type)) {
            $resource = new $type();
            return $resource;
        } else {
            throw new \InvalidArgumentException("Invalid Converter type given.");
        }
    }

}