<?php


use Bit8\Client;

use Bit8\Client\Resource\LocationApi;
use Bit8\Exception\InvalidJsonSchemaException;
use Bit8\Exception\ApiErrorException;

use Webmozart\Json\DecodingFailedException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\ClientException;


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

    public function testResourceFactory()

    {
        $api = new Client();

        $location = $api->resource('location');

        $this->assertInstanceOf(LocationApi::class, $location);

        $this->setExpectedException('InvalidArgumentException');
        $location = $api->resource('invalid');


    }


    public function testGetLocations()
    {


        $httpClient = $this->createHttpClient($this->validResponse);

        $api = new Client('http://localhost', $httpClient);

        $locations = $api->resource('location')->all();


        $this->assertTrue(is_array($locations));
        $this->assertCount(2, $locations);


    }


    public function testGetLocationsUseConverter()
    {


        $httpClient = $this->createHttpClient($this->validResponse);

        $api = new Client('http://localhost', $httpClient);

        $dataConverter = Client\Resource\Factory::createConverter('location');
        $locations = $api->resource('location')->useDataConverter($dataConverter)->all();


        $this->assertInstanceOf(\Bit8\Location\LocationCollection::class, $locations);
        $this->assertCount(2, $locations);

        $location1 = $locations[0];

        $this->assertInstanceOf(\Bit8\Location\Location::class, $location1);
        $this->assertCount(2, $locations);
        $this->assertContains('Eiffel Tower', $location1->getName());


    }

    public function testInvalidJsonFormat()
    {


        $httpClient = $this->createHttpClient($this->invalidJsonResponse);

        $api = new Client('http://localhost', $httpClient);


        $this->setExpectedException(DecodingFailedException::class);

        $locations = $api->resource('location')->all();


    }


    public function testEmptyJsonFormat()
    {


        $httpClient = $this->createHttpClient('');

        $api = new Client('http://localhost', $httpClient);


        $this->setExpectedException(DecodingFailedException::class);

        $locations = $api->resource('location')->all();


    }

    public function testInvalidJsonSchema()
    {


        $httpClient = $this->createHttpClient($this->invalidJsonSchemaResponse);

        $api = new Client('http://localhost', $httpClient);


        $this->setExpectedException(InvalidJsonSchemaException::class);

        $locations = $api->resource('location')->all();


    }

    public function testErrorResponseSchema()
    {


        $httpClient = $this->createHttpClient($this->errorJsonResponse);

        $api = new Client('http://localhost', $httpClient);


        $this->setExpectedException(ApiErrorException::class);

        $locations = $api->resource('location')->all();


    }

    public function testErrorExceptionResponseSchema()
    {

        $httpClient = $this->createHttpClient($this->errorJsonResponse);

        $api = new Client('http://localhost', $httpClient);


        try {
            $locations = $api->resource('location')->all();

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

        $api = new Client('http://localhost', $httpClient);

        $this->setExpectedException(ClientException::class);

        $locations = $api->resource('location')->all();
    }


}