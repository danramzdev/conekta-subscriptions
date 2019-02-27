<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use Aura\Router\RouterContainer;

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'index'
]);
$map->get('checkout', '/plan/checkout', [
    'controller' => 'App\Controllers\PlanController',
    'action' => 'checkout'
]);
$map->post('payment', '/plan/cobro', [
    'controller' => 'App\Controllers\PlanController',
    'action' => 'payment'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route) {
    echo 'No existe esa ruta';
} else {
    $handler = $route->handler;
    $controllerName = $handler['controller'];
    $action = $handler['action'];

    $controller = new $controllerName;
    $response = $controller->$action($request);

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
}