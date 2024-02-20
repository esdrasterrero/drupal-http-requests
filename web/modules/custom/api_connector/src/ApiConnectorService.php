<?php

namespace Drupal\api_connector;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Handles API connector methods.
 */
class ApiConnectorService {
  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected Client $httpClient;

  /**
   * Initializes a new instance of the ApiConnectorService class with the
   * provided params.
   *
   * @param \GuzzleHttp\Client $http_client
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Make a http request and return the response.
   *
   * @param string $method
   *  The http method.
   * @param string $url
   *  The endpoint URL.
   * @param array $options
   *  The request options.
   * @param bool $convertToAssociativeArray
   *  The option to convert the response into an associative array.
   *
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function makeHttpRequest(string $method, string $url, array $options = [], bool $convertToAssociativeArray = TRUE): mixed {
    $response = $this->httpClient->request($method, $url, $options);

    return json_decode($response->getBody()->getContents(), $convertToAssociativeArray);
  }
}
