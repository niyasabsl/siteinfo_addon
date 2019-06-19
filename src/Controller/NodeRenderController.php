<?php

namespace Drupal\siteinfo_addon\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 *
 */
class NodeRenderController extends ControllerBase {

  /**
   * @param $apikey
   * @param $nid
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function renderNodeJson($apikey, $nid) {
    $node = Node::load($nid);
    $siteapikey = \Drupal::config('system.site')->get('siteapikey');
    // Check if node exists and is of type 'page'.
    // Check if submitted site api key matches the one in site information settings.
    if ($node instanceof NodeInterface && $node->getType() == 'page' && $siteapikey == $apikey) {
      $serializer = \Drupal::service('serializer');
      $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
      return new JsonResponse(json_decode($data));
    }
    throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
  }

}
