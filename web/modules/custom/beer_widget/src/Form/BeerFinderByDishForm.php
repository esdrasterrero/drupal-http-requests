<?php

namespace Drupal\beer_widget\Form;

use Drupal\api_connector\ApiConnectorService;
use Drupal\beer_widget\Constants\BeerWidgetConstants;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStore;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Beer Finder form.
 */
class BeerFinderByDishForm extends FormBase {

  /**
   * The API connector service.
   *
   * @var \Drupal\api_connector\ApiConnectorService
   */
  protected ApiConnectorService $apiConnector;

  /**
   * The temporary storage object.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected PrivateTempStoreFactory $privateTempStore;

  /**
   * Constructs a BeerFinderByDishForm object.
   *
   * @param \Drupal\api_connector\ApiConnectorService $api_connector
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $private_tempstore
   */

  public function __construct(ApiConnectorService $api_connector, PrivateTempStoreFactory $private_tempstore) {

    $this->apiConnector = $api_connector;
    $this->privateTempStore = $private_tempstore;
  }

  /**
   * Creates an instance of the plugin.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('api_connector'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'beer_finder_by_dish_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['dish'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter a dish'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find Beer'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $dish = $form_state->getValue('dish');
    if (empty($dish)) {
      $form_state->setErrorByName('dish', $this->t('Dish field cannot be empty.'));
    }
  }

  /**
   * {@inheritdoc}
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $beerByDish = $this->getBeerByDish(
      $form_state->getValue('dish')
    );
    // Store the results.
    if (!empty($beerByDish)) {
      // Store the response using Drupal's temporary storage.
      $this->privateTempStore->get('beer_widget')->set('beers', $beerByDish);
    }
  }

  /**
   * Get beers by the specified dish from the Punk API.
   *
   * @param string $dish
   *    The dish to filter results by.
   *
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getBeerByDish(string $dish): array {
    // Assemble the endpoint.
    $getBeersEndpoint = BeerWidgetConstants::API_BASE_URL . BeerWidgetConstants::BEERS_ENDPOINT;
    // Cast the request options array to apply.
    $requestOptions = [
      'query' => [
        'food' => $dish,
        'per_page' => BeerWidgetConstants::PER_PAGE,
      ],
    ];
    // Make an HTTP request to the PUNK API to retrieve beers matching the specified dish.
    return $this->apiConnector->makeHttpRequest('GET', $getBeersEndpoint, $requestOptions);
  }

}
