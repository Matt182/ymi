<?php
namespace ymi;

use ymi\model\UserModel;

use ymi\view\Viewer;

use ymi\middleware\Router;
use ymi\middleware\Request;
use ymi\middleware\Registry;

require_once 'vendor/autoload.php';
require_once 'config/config.php';

try {
    $registry = new Registry();
    $registry->set('userModel', UserModel::getInstance());
    $registry->set('viewer', Viewer::getInstance());
    $registry->set('request', new Request());
    $registry->set('router', new Router($registry->get('request')));

    $router = $registry->get('router');

    $control = $router->getController();
    $control = new $control($registry);
    $response = $control->{$router->getMethod()}();
    $registry->get('viewer')->render($response['template'], $response);
} catch (\Exception $e) {
    print($e->getCode().' '.$e->getMessage());
}



 ?>
