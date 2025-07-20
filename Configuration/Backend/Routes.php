<?php

use Ppl\ProjectDesk\Controller\ConfigController;
use Ppl\ProjectDesk\Mapping\RouteMapping;

$routes = [];
foreach (ConfigController::TAB_NAMES as $key) {
    $routes[RouteMapping::EXTENSION_ROUTE_PREFIX. $key] = [
        'path'   => RouteMapping::CONFIG_ROUTE . $key,
        'target' => ConfigController::class . '::' . $key . 'Action',
        'methods' => ['GET'],
    ];
    $routes[RouteMapping::EXTENSION_ROUTE_PREFIX . $key . '_save'] = [
        'path'    => RouteMapping::CONFIG_ROUTE . $key . '/save',
        'target'  => ConfigController::class . '::save' . ucfirst($key) . 'Action',
        'methods' => ['POST'],
    ];
}

return $routes;