<?php

namespace Drupal\cat_widgets\Form;

use Drupal\api_connector\ApiConnectorService;
use Drupal\cat_widgets\CatWidgetsApiService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CatFinderByBreedForm extends FormBase {

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
   * The temporary storage object.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected PrivateTempStoreFactory $privateTempStore;

  /**
   * Constructs a BeerFinderByDishForm object.
   *
   * @param \Drupal\api_connector\ApiConnectorService $api_connector
   * @param \Drupal\cat_widgets\CatWidgetsApiService $cat_widgets_api
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $private_tempstore
   */

  public function __construct(ApiConnectorService $api_connector, CatWidgetsApiService $cat_widgets_api, PrivateTempStoreFactory $private_tempstore) {

    $this->apiConnector = $api_connector;
    $this->catWidgetsApi = $cat_widgets_api;
    $this->privateTempStore = $private_tempstore;
  }

  /**
   * Creates an instance of the plugin.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('api_connector'),
      $container->get('cat_widgets.api'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cat_finder_by_breed_form';
  }

  /**
   * {@inheritdoc}
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $options = $this->catWidgetsApi->getAllBreeds();

    $form['breed'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter by breed'),
      '#options' => $options,
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search cats'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $catsByBreed = $this->catWidgetsApi->getCatsByBreed(
      $form_state->getValue('breed')
    );
    if (!empty($catsByBreed)) {
      // Store the response using Drupal's temporary storage.
      $this->privateTempStore->get('cat_widgets')->set('cats', $catsByBreed);
    }
  }

}
