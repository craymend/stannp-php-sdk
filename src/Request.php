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
    public function __construct($apiKey = '')
    {
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
        $requestOptions[RequestOptions::HEADERS] = $headers;

        // Set data based on the method
        if (($method === 'POST' || $method === 'PUT') && $data !== null) {
            $fileFields = ['front', 'back'];
            
            $hasFile = false;
            foreach ($fileFields as $fileField) {
                if (isset($data[$fileField])) {
                    $hasFile = true;
                    break;
                }
            }

            if ($hasFile) {
                $multipart = [];

                foreach ($data as $key => $value) {
                    if (!in_array($key, $fileFields)) {
                        $multipart[] = ['name' => $key, 'contents' => $value];
                    }
                }

                foreach ($fileFields as $fileField) {
                    if (isset($data[$fileField])) {
                        $multipart[] = [
                            'name' => $fileField,
                            'contents' => fopen($data[$fileField]['path'], 'r'),
                            'filename' => $data[$fileField]['filename']
                        ];
                    }
                }

                $requestOptions[RequestOptions::MULTIPART] = $multipart;
            } else {
                $requestOptions[RequestOptions::FORM_PARAMS] = $data;
            }
        } else if ($method === 'GET' && $data !== null) {
            $requestOptions[RequestOptions::QUERY] = $data;
        }

        // Send request
        try {
            $client = new Client();

            $response = $client->request($method, $url, $requestOptions);
            $data = json_decode($response->getBody(), false);
            $statusCode = $response->getStatusCode();

            return new Response(true, $statusCode, $data, null);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;
            return new Response(false, $statusCode, null, $e->getMessage());
        } catch (\Exception $e) {
            return new Response(false, null, null, $e->getMessage());
        }
    }
}
