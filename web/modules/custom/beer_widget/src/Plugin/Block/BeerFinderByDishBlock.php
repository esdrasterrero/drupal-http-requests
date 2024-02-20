<?php

namespace Drupal\beer_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Beer finder by dish' block.
 *
 * @Block(
 *   id = "beer_finder_by_dish_block",
 *   admin_label = @Translation("Punk API Beer Finder Block"),
 *   category = "Widgets",
 * )
 */
class BeerFinderByDishBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * The temporary storage object.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected PrivateTempStoreFactory $privateTempStore;

  /**
   * Constructs a BeerFinderByDishBlock object.
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
   *   The Drupal temp store.
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
    // If there are any stored results, retrieve it from the tempstore.
    if (!is_null($this->privateTempStore->get('beer_widget')->get('beers'))) {
      $results = $this->privateTempStore->get('beer_widget')->get('beers');
      // Once the results have been retrieved, delete the tempstore.
      $this->privateTempStore->get('beer_widget')->delete('beers');
    }

    return [
      '#theme' => 'beer_widget_finder_results',
      '#form' => $this->formBuilder->getForm('Drupal\beer_widget\Form\BeerFinderByDishForm'),
      '#beers' => $results,
    ];


  }

}
