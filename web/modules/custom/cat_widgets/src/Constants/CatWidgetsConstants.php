<?php

namespace Drupal\cat_widgets\Constants;

/**
 * Holds the constants used by the beer_widget module.
 */
class CatWidgetsConstants {

  /**
   * The Punk API base URL (Root Endpoint).
   *
   * @var string
   */
  const API_BASE_URL = 'https://api.thecatapi.com/v1';

  /**
   * The get random image endpoint.
   *
   * @var string
   */
  const IMAGES_SEARCH_ENDPOINT = '/images/search';

  /**
   * The Get Random Beer endpoint
   *
   * @var string
   */
  const VOTE_ENDPOINT = '/votes';

  /**
   * The API key.
   *
   * @var string
   */
  const CAT_API_KEY = 'live_dQlZtLTOxYyBn2B7KDMOI4H3e9zqup89tY1u1Q0au6dM2YIyrkUooyysF8jiLQWV';

  /**
   * The breeds endpoint.
   *
   * @var string
   */
  const BREEDS_ENDPOINT = '/breeds';

  /**
   * The default amount of results to get.
   *
   * @var string
   */
  const LIMIT = 3;
}
