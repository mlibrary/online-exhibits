<?php

use Conlect\ImageIIIF\ImageFactory;
use Noodlehaus\Config;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

class Iiif_IiifController extends Omeka_Controller_AbstractActionController
{
  public function imageAction()
  {
    $config = $this->loadConfig();
    $params = $this->getRequest()->getParams();
    $file = $this->getPath($params['identifier']);
    if (is_null($file)) {
      return $this->send404();
    }

    list($params['quality'], $params['format']) =
      explode('.', $params['quality_format'], 2);

    if (isset($config->get('mime')[$params['format']])) {
      header('Content-Type: ' . $config->get('mime')[$params['format']]);
    }

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    print $factory($config)->load($file)
      ->withParameters($params)
      ->stream($params['format']);
    exit;
  }

  public function infoAction()
  {
    $config = $this->loadConfig();
    $params = $this->getRequest()->getParams();
    $file = $this->getPath($params['identifier']);
    if (is_null($file)) {
      return $this->send404();
    }

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    $info = $factory($config)->load($file)->info($params['identifier']);

    print json_encode($info);
    exit;
  }

  private function send404()
  {
    throw new Omeka_Controller_Exception_404;
  }

  private function constructPath($identifier)
  {
    if (preg_match('/\.pdf$/i', $identifier) &&
        file_exists($file = FILES_DIR . '/fullsize/' . $identifier)) {
      return $file;
    }
    return FILES_DIR . '/original/' . $identifier;
  }

  private function getPath($identifier)
  {
    if (file_exists($file = $this->constructPath($identifier))) {
      return $file;
    }
    $file = get_db()->getTable('File')->find($identifier);
    if (empty($file) || empty($file->filename)) {
      return NULL;
    }
    if (!file_exists($file = $this->constructPath($file->filename))) {
      return NULL;
    }
    return $file;
  }

  private function loadConfig()
  {
    $config = new Config(PLUGIN_DIR . '/Iiif/vendor/conlect/image-iiif/config');
    $config->set('base_url', WEB_ROOT);
    if (extension_loaded('imagick')) {
      $config->set('driver', 'imagick');
    }
    return $config;
  }
}
