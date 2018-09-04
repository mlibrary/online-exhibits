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

    $factory = new \Conlect\ImageIIIF\ImageFactory;

    $file = $factory($config)->load($file)
      ->withParameters($params)
      ->stream();
    if (isset($config->get('mime')[$params['format']])) {
      header('Content-Type: ' . $config->get('mime')[$params['format']]);
    }
    print $file;
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

  private static function constructPath($dir, $identifier)
  {
    return FILES_DIR . "/{$dir}/{$identifier}";
  }

  private function getPath($identifier)
  {
    $candidate = static::constructPath('original', $identifier);
    if (file_exists($candidate)) {
      return $candidate;
    }
    $candidate = static::constructPath('fullsize', $identifier);
    if (file_exists($candidate)) {
      return $candidate;
    }

    $file = get_db()->getTable('File')->find($identifier);
    if (empty($file) || empty($file->filename)) {
      return NULL;
    }

    if ($file->has_derivative_image) {
      $candidate = static::constructPath(
          'fullsize',
          $file->getDerivativeFilename()
      );
      if (file_exists($candidate)) {
        return $candidate;
      }
    } else {
      $candidate = static::constructPath('original', $file->filename);
      if (file_exists($candidate)) {
        return $candidate;
      }
    }
    return NULL;
  }

  private function loadConfig()
  {
    $config = new Config(PLUGIN_DIR . '/Iiif/vendor/conlect/image-iiif/config');
    $config->set('base_url', WEB_ROOT);
    return $config;
  }
}
