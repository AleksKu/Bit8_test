<?php

namespace Bit8\Client\Resource\Converter;

interface JsonDataConverterInterface
{


    /**
     * Converts JSON to an implementation-specific data structure.
     *
     * @param mixed $jsonData The JSON data. Use a {@link JsonDecoder} to
     *                        convert a JSON string to this data structure.
     * @param array $options Additional implementation-specific conversion options.
     *
     * @return mixed The converted data.
     *
     */
    public function fromJson($jsonData, array $options = array());
}