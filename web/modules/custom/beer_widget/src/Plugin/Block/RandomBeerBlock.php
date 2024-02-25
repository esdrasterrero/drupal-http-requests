<?php

namespace Drupal\beer_widget\Plugin\Block;

use Drupal\api_connector\ApiConnectorService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\beer_widget\Constants\BeerWidgetConstants;

/**
* Provides a 'Punk API Random Beer' block.
*
* @Block(
*   id = "random_beer_block",
*   admin_label = @Translation("Punk API Random Beer Block"),
*   category = "Widgets",
* )
*/
class RandomBeerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The API connector service.
   *
   * @var \Drupal\api_connector\ApiConnectorService
   */
  protected ApiConnectorService $apiConnector;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ApiConnectorService $api_connector) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->apiConnector = $api_connector;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('api_connector')
    );
  }

  /**
   * {@inheritdoc}
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function build(): array {
    $beer = NULL;

    // Assemble the endpoint.
    $endpoint = BeerWidgetConstants::API_BASE_URL . BeerWidgetConstants::RANDOM_BEER_ENDPOINT;
    // Make an HTTP request to the PUNK API to retrieve a random beer.
    $response = $this->apiConnector->makeHttpRequest('GET', $endpoint );
    if (!empty($response)) {
      $beer = $response[0];
    }

    // Return a render array for the block content.
    return [
      '#theme' => 'beer_widget_random_beer_block',
      '#beer' => $beer,
    ];
  }
}
