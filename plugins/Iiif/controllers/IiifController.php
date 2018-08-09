<?php

use Conlect\ImageIIIF\ImageFactory;
use Noodlehaus\Config;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

class Iiif_IiifController extends Omeka_Controller_AbstractActionController
{
  function imageAction()
  {
    $config = $this->loadConfig();
    $params = $this->getRequest()->getParams();
    $file = getcwd() . '/files/original/' . $params['identifier'];
    list($params['quality'], $params['format']) =
      explode('.', $params['quality_format'], 2);

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    $file = $factory()->load($file)
      ->withParameters($params)
      ->stream();
    if (isset($config['mime'][$params['format']])) {
      header('Content-Type: ' . $config['mime'][$params['format']]);
    }
    print $file;
    exit;
  }

  function infoAction()
  {
    $params = $this->getRequest()->getParams();
    $file = getcwd() . '/files/original/' . $params['identifier'];

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    $info = $factory()->load($file)->info($params['identifier']);

    print json_encode($info);
    exit;
  }

  function loadConfig() {
    return include(getcwd() . '/plugins/Iiif/vendor/conlect/image-iiif/config/iiif.php');
  }
}
