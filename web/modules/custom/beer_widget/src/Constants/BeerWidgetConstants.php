<?php

namespace Drupal\beer_widget\Constants;

/**
 * Holds the constants used by the beer_widget module.
 */
class BeerWidgetConstants {

  /**
   * The Punk API base URL (Root Endpoint).
   *
   * @var string
   */
  const API_BASE_URL = 'https://api.punkapi.com/v2';

  /**
   * The Get Beers endpoint.
   *
   * @var string
   */
  const BEERS_ENDPOINT = '/beers';

  /**
   * The Get Random Beer endpoint
   *
   * @var string
   */
  const RANDOM_BEER_ENDPOINT = '/beers/random';

  /**
   * The default amount of results to get.
   *
   * @var string
   */
  const PER_PAGE = 3;
}
