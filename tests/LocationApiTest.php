<?php


use Bit8\Client;

use Bit8\Api\LocationApi;
use Bit8\Exception\InvalidJsonSchemaException;
use Bit8\Exception\ApiErrorException;

use Webmozart\Json\DecodingFailedException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\RequestException;


class LocationApiTest extends PHPUnit_Framework_TestCase
{

    public $validResponse = '{
   "data": {
       "locations": [
           {
               "name": "Eiffel Tower",
               "coordinates": {
                   "lat": 21.12,
                   "long": 19.56
               }
           },
           {
               "name": "Ostankino",
               "coordinates": {
                   "lat": 2156.12,
                   "long": 1956.56
               }
           }
          
       ]
   },
  "success": true
}';

    public $invalidJsonSchemaResponse = '{}';

    public $invalidJsonResponse = '{';

    public $errorJsonResponse = '{
   "data": {
       "message": "api error",
       "code": 100
   },
   "success": false
}';


    /**
     * @param string $response
     * @return HttpClient
     */
    protected function createHttpClient($response)
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $response)
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handler]);
        return $httpClient;
    }

    /**
     * @param $httpClient
     * @return Client
     */
    protected function createClient($httpClient)
    {
        $api = new Client('http://localhost', $httpClient);
        return $api;
    }

    public function testResourceFactory()
    {
        $api = new Client();

        $location = $api->api('location');

        $this->assertInstanceOf(LocationApi::class, $location);

        $this->setExpectedException('InvalidArgumentException');
        $location = $api->api('invalid');


    }


    /**
     * Тест на получение валидных данных от API
     */
    public function testGetLocations()
    {


        $httpClient = $this->createHttpClient($this->validResponse);

        $api = $this->createClient($httpClient);

        $locations = $api->api('location')->get();


        $this->assertTrue(is_array($locations));
        $this->assertCount(2, $locations);


    }


    /**
     * Тест на получение валидных данных от API с применением конвертера данных
     *
     */
    public function testGetLocationsUseConverter()
    {


        $httpClient = $this->createHttpClient($this->validResponse);

        $api = $this->createClient($httpClient);

        $dataConverter = Bit8\Api\Factory::createConverter('location');
        $locations = $api->api('location')->useDataConverter($dataConverter)->get();


        $this->assertInstanceOf(\Bit8\Location\LocationCollection::class, $locations);
        $this->assertCount(2, $locations);

        $location1 = $locations[0];

        $this->assertInstanceOf(\Bit8\Location\Location::class, $location1);
        $this->assertCount(2, $locations);
        $this->assertContains('Eiffel Tower', $location1->getName());


    }

    /**
     * Тест на невалидный формат json. например, если тело ответа пришло не полностью
     */
    public function testInvalidJsonFormat()
    {


        $httpClient = $this->createHttpClient($this->invalidJsonResponse);

        $api = $this->createClient($httpClient);


        $this->setExpectedException(DecodingFailedException::class);

        $locations = $api->api('location')->get();


    }


    /**
     * тест на пустой ответ
     */
    public function testEmptyJsonFormat()
    {


        $httpClient = $this->createHttpClient('');

        $api = $this->createClient($httpClient);


        $this->setExpectedException(DecodingFailedException::class);

        $locations = $api->api('location')->get();


    }

    /**
     * Тест на невалидную схему json
     * Схемы описываются в src/schemas
     */
    public function testInvalidJsonSchema()
    {


        $httpClient = $this->createHttpClient($this->invalidJsonSchemaResponse);

        $api = $this->createClient($httpClient);


        $this->setExpectedException(InvalidJsonSchemaException::class);

        $locations = $api->api('location')->get();


    }

    /**
     * Тест на получение ответа с ошибкой
     */
    public function testErrorResponseSchema()
    {


        $httpClient = $this->createHttpClient($this->errorJsonResponse);

        $api = $this->createClient($httpClient);


        $this->setExpectedException(ApiErrorException::class);

        $locations = $api->api('location')->get();


    }


    public function testErrorExceptionResponseSchema()
    {

        $httpClient = $this->createHttpClient($this->errorJsonResponse);

        $api = $this->createClient($httpClient);


        try {
            $locations = $api->api('location')->get();

        } catch (Exception $e) {
            $this->assertInstanceOf(ApiErrorException::class, $e);
            $this->assertEquals($e->getCode(), 100);
            $this->assertContains($e->getMessage(), 'api error');
        }
    }


    /**
     * тесты на ошибки сети
     *
     */
    public function testHttp404Error()
    {
        $mock = new MockHandler([
            new Response(404)
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handler]);

        $api = $this->createClient($httpClient);

        $this->setExpectedException(RequestException::class);

        $locations = $api->api('location')->get();
    }


}