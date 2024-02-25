<?php
namespace Drupal\cat_widgets;

use Drupal\api_connector\ApiConnectorService;
use Drupal\cat_widgets\Constants\CatWidgetsConstants;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Contains helper methods for interacting with the cat API.
 */
class CatWidgetsApiService {
  use StringTranslationTrait;

  /**
   * The API connector service.
   *
   * @var \Drupal\api_connector\ApiConnectorService
   */
  protected ApiConnectorService $apiConnector;

  /**
   * Initializes a new instance of the CatWidgetsApiService class with the provided params.
   *
   * @param \Drupal\api_connector\ApiConnectorService $api_connector
   */
  public function __construct(ApiConnectorService $api_connector) {
    $this->apiConnector = $api_connector;
  }

  /**
   * Vote on a given cat id.
   *
   * @param string $id
   *   ID of the cat.
   * @param int $value
   *   Score of the cat.
   *
   * @return AjaxResponse
   *   An Ajax response with the new vote link.
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function vote(string $id, int $value = 1): AjaxResponse {
    $params = [
      'headers' => [
        'Content-Type' => 'application/json',
        'x-api-key' => CatWidgetsConstants::CAT_API_KEY,
      ],
      'json' => [
        'image_id' => $id,
        'value' => strval($value),
      ],
    ];

    $endpoint = CatWidgetsConstants::API_BASE_URL . CatWidgetsConstants::VOTE_ENDPOINT;
    $result = $this->apiConnector->makeHttpRequest('POST', $endpoint, $params);
    $span = $this->getVoteLinks($id, TRUE);
    $output = '<div id="cat-api-message">' . $this->t('Thank You For the Vote!') . '</div>';
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#cat-api-message', $output))
      ->addCommand(new ReplaceCommand('.vote-button-container', $span));

    return $response;
  }

  /**
   * Gets the voting links as html anchor tags.
   *
   * @param string $imageId
   * @param bool $disabled
   *
   * @return string|array
   */
  public function getVoteLinks(string $imageId = '', bool $disabled = FALSE): string|array {
    if ($disabled) {
      return '<span>' . $this->t('You already voted for this cat!') . '</span>';
    }

    $voteUpUrl = $this->generateVoteUrl($imageId);
    $voteDownUrl = $this->generateVoteUrl($imageId, -1);

    return [
      'up' => Link::fromTextAndUrl($this->t('Vote up'), $voteUpUrl)->toString(),
      'down' => Link::fromTextAndUrl($this->t('Vote down'), $voteDownUrl)->toString(),
    ];
  }

  /**
   * Generates a voting link as a Drupal Url object.
   *
   * @param string $imageId
   * @param int $value
   *
   * @return \Drupal\Core\Url
   */
  public function generateVoteUrl(string $imageId, int $value = 1): Url {
    $options = [
      'attributes' => [
        'class' => [
          'use-ajax',
          'vote-link'
        ],
        'id' => 'cat-api-block-vote-link'
      ]
    ];
    $route = 'cat_widgets.vote_callback';
    $params = [
      'id' => $imageId,
      'value' => $value,
    ];

    return Url::fromRoute($route, $params, $options);
  }

  /**
   * Get the breed names and breed_id's
   *
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAllBreeds(): array {
    // Make a http request to fetch all the breeds from the ./breeds endpoint.
    $breedsList = [];
    $endpoint = CatWidgetsConstants::API_BASE_URL . CatWidgetsConstants::BREEDS_ENDPOINT;
    $breeds = $this->apiConnector->makeHttpRequest('GET', $endpoint);
    foreach ($breeds as $breed) {
      $breedsList[$breed['id']] = $breed['name'];
    }
    return $breedsList;
  }

  /**
   * Get Cats filtered by the given breed from the Cat API.
   *
   * @param string $breed
   *   The breed name.
   *
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getCatsByBreed(string $breed): array {
    // Assemble the endpoint.
    $getCatsByBreedEndpoint = CatWidgetsConstants::API_BASE_URL . CatWidgetsConstants::IMAGES_SEARCH_ENDPOINT;
    // Cast the request options array to apply.
    $requestOptions = [
      'headers' => [
        'x-api-key' => CatWidgetsConstants::CAT_API_KEY,
      ],
      'query' => [
        'breed_ids' => $breed,
        'limit' => CatWidgetsConstants::LIMIT,
      ],
    ];

    // Make an HTTP request to the Cat API to retrieve cats matching the specified breed.
    return $this->apiConnector->makeHttpRequest('GET', $getCatsByBreedEndpoint, $requestOptions);
  }
}
