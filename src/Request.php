<?php

namespace Craymend\Stannp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Perform API calls
 */
final class Request
{
    const BASE_URL = 'https://api-us1.stannp.com';
    
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * @return null
     */
    public function __construct($apiKey=''){
        $this->apiKey = $apiKey;
        $this->baseUrl = self::BASE_URL;

        return null;
    }

    /**
	 * @return Response
	 */
    public function get($path, array $params)
    {
        $url = $this->baseUrl . $path;
        return $this->sendRequest('GET', $url, $params);
    }

    /**
	 * @return Response
	 */
    public function post($path, array $body)
    {
        $url = $this->baseUrl . $path;
        return $this->sendRequest('POST', $url, $body);
    }

    /**
	 * @return Response
	 */
    public function put($path, array $body)
    {
        $url = $this->baseUrl . $path;
        return $this->sendRequest('PUT', $url, $body);
    }

    /**
	 * @return Response
	 */
    public function delete($path)
    {
        $url = $this->baseUrl . $path;
        return $this->sendRequest('DELETE', $url);
    }

    /**
     * Reliable test/example of aruguments and endpoint.
     * Jumpstart the use of the API from here.
     * 
	 * @return Response
	 */
    public function testEndpoint()
    {
        $uri = '/v1/users/me';

        return $this->get($uri, []);
    }

    /**
     * @return Response
     */
    private function sendRequest($method, $url, array $data = null)
    {
        $requestOptions = [];
        $headers = [];

        // Encode API key as username with an empty password
        $headers['Authorization'] = 'Basic ' . base64_encode($this->apiKey . ':'); 
        $headers['content-type'] = 'application/x-www-form-urlencoded';

        // if ($method === 'POST' && null !== $data) {
        //     $headers['content-type'] = 'application/x-www-form-urlencoded';
        // }else if ($method === 'PUT' && null !== $data) {
        //     $headers['content-type'] = 'application/json';
        // }

        $requestOptions[RequestOptions::HEADERS] = $headers;

        // set data
        if ($method === 'POST' && null !== $data) {
            $requestOptions[RequestOptions::FORM_PARAMS] = $data;
        }else if($method === 'GET' && null !== $data){
            $requestOptions[RequestOptions::QUERY] = $data;
        } else if($method === 'PUT' && null !== $data) {
            $requestOptions[RequestOptions::JSON] = $data;
        }

        // echo 'Guzzle request options: <br><br>';
        // echo json_encode($requestOptions);
        // echo '<br><br>';

        // send request
        try {
            $client = new Client();

            $response = $client->request($method, $url, $requestOptions);

            $data = (array) json_decode($response->getBody(), true);

            return new Response(true, $data);
        }catch (\Exception $e) {
            $errors['errors'] = [$e->getMessage()];

            return new Response(false, [], $errors);
        }
    }
}