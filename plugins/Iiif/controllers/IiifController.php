<?php

use Conlect\ImageIIIF\ImageFactory;
use Noodlehaus\Config;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

class Iiif_IiifController extends Omeka_Controller_AbstractActionController
{
  public function imageAction()
  {
    $config = static::loadConfig();
    $params = $this->getRequest()->getParams();
    $file = static::getPath($params['identifier']);
    if (is_null($file)) {
      return static::send404();
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
    $config = static::loadConfig();
    $params = $this->getRequest()->getParams();
    $file = static::getPath($params['identifier']);
    if (is_null($file)) {
      return static::send404();
    }

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    $info = $factory($config)->load($file)->info($params['identifier']);

    print json_encode($info);
    exit;
  }

  private static function send404()
  {
    throw new Omeka_Controller_Exception_404;
  }

  private static function constructPath($dir, $identifier)
  {
    return FILES_DIR . "/{$dir}/{$identifier}";
  }

  private static function getPath($identifier)
  {
    if (file_exists($candidate = static::constructPath('original', $identifier))) {
      return $candidate;
    }
    $file = get_db()->getTable('File')->find($identifier);
    if (empty($file) || empty($file->filename)) {
      return NULL;
    }
    if (!file_exists($candidate = static::constructPath('original', $file->filename))) {
      return NULL;
    }
    if (preg_match('/\.pdf$/i', $candidate) && $file->has_derivative_image) {
      $candidate = static::constructPath(
          'fullsize',
          $file->getDerivativeFilename()
      );
    }
    return $candidate;
  }

  private static function loadConfig()
  {
    $config = new Config(PLUGIN_DIR . '/Iiif/vendor/conlect/image-iiif/config');
    $config->set('base_url', WEB_ROOT);
    if (extension_loaded('imagick')) {
      $config->set('driver', 'imagick');
    }
    return $config;
  }
}
