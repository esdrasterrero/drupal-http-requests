<?php

namespace Drupal\api_connector;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

/**
 * Handles API connector methods.
 */
class ApiConnectorService {
  use StringTranslationTrait;
  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected Client $httpClient;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * The drupal logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;

  /**
   * Initializes a new instance of the ApiConnectorService class with the
   * provided params.
   *
   * @param \GuzzleHttp\Client $http_client
   *   The http client.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger interface.
   */
  public function __construct(Client $http_client, MessengerInterface $messenger, LoggerInterface $logger) {
    $this->httpClient = $http_client;
    $this->messenger = $messenger;
    $this->logger = $logger;
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
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function makeHttpRequest(string $method, string $url, array $options = [], bool $convertToAssociativeArray = TRUE): array {
    try {
      $response = $this->httpClient->request($method, $url, $options);

      return json_decode($response->getBody()->getContents(), $convertToAssociativeArray);
    } catch (\Exception $exception) {
      $this->logger->error($exception->getMessage());
      $this->messenger->addMessage($this->t('Could not connect to the API.'));

      return [];
    }
  }
}
