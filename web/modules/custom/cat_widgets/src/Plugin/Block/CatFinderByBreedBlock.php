<?php

namespace Drupal\cat_widgets\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Cat finder by breed' block.
 *
 * @Block(
 *   id = "cat_finder_by_breed_block",
 *   admin_label = @Translation("Cat API finder block"),
 *   category = "Widgets",
 * )
 */
class CatFinderByBreedBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a CatFinderByBreedBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $private_tempstore
   *   The drupal temps store.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, PrivateTempStoreFactory $private_tempstore) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->formBuilder = $form_builder;
    $this->privateTempStore = $private_tempstore;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function build(): array {
    $results = NULL;
    $breedInfo = NULL;
    // If there are any stored results, retrieve it from the tempstore.
    if (!is_null($this->privateTempStore->get('cat_widgets')->get('cats'))) {
      $results = $this->privateTempStore->get('cat_widgets')->get('cats');
      // Extract the breed info from the results.
      if (isset($results[0])) {
        $breedInfo = array_key_exists('breeds', $results[0]) ? $results[0]['breeds'][0] : NULL;
      }
      // Once the results have been retrieved, delete the tempstore.
      $this->privateTempStore->get('cat_widgets')->delete('cats');
    }
    return [
      '#theme' => 'cat_widgets_finder_results',
      '#form' => $this->formBuilder->getForm('Drupal\cat_widgets\Form\CatFinderByBreedForm'),
      '#cats' => $results,
      '#breed_info' => $breedInfo,
    ];

  }


}
