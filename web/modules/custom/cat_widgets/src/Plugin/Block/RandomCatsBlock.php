<?php

namespace Drupal\cat_widgets\Plugin\Block;

use Drupal\api_connector\ApiConnectorService;
use Drupal\cat_widgets\CatWidgetsApiService;
use Drupal\cat_widgets\Constants\CatWidgetsConstants;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Provides a 'Cat API Random Cat' block.
*
* @Block(
*   id = "random_cat_block",
*   admin_label = @Translation("Cat API Random Cat Block"),
*   category = "Widgets",
* )
*/
class RandomCatsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The API connector service.
   *
   * @var \Drupal\api_connector\ApiConnectorService
   */
  protected ApiConnectorService $apiConnector;

  /**
   * The Cat Widgets API service.
   *
   * @var \Drupal\cat_widgets\CatWidgetsApiService
   */
  protected CatWidgetsApiService $catWidgetsApi;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ApiConnectorService $api_connector, CatWidgetsApiService $cat_widgets_Api) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->apiConnector = $api_connector;
    $this->catWidgetsApi = $cat_widgets_Api;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('api_connector'),
      $container->get('cat_widgets.api'),
    );
  }

  /**
   * {@inheritdoc}
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function build(): array {
    $cat = NULL;
    $voteButtons = NULL;

    // Assemble the endpoint.
    $endpoint = CatWidgetsConstants::API_BASE_URL . CatWidgetsConstants::IMAGES_SEARCH_ENDPOINT;
    // Make a HTTP request to the Cat API to retrieve a random cat.
    $response = $this->apiConnector->makeHttpRequest('GET', $endpoint);
    if (is_array($response)) {
      $cat = $response[0];
      // Assemble the voting links.
      $voteButtons = $this->catWidgetsApi->getVoteLinks($cat['id']);
    }

    // Return a render array for the block content.
    return [
      '#theme' => 'cat_widgets_random_cats_block',
      '#cat' => $cat,
      '#votes' => $voteButtons,
    ];
  }

}
