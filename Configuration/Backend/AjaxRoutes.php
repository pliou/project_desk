<?php

use Ppl\ProjectDesk\Ajax\UpdateRepeater;
use Ppl\ProjectDesk\Ajax\TeamAssignment;
use Ppl\ProjectDesk\Mapping\RouteMapping;
use Ppl\ProjectDesk\Ajax\GetAccessConfig;

$routes[RouteMapping::REAPEATER_ROUTE_NAME] = [
    'path' => RouteMapping::REAPEATER_ROUTE,
    'target' => UpdateRepeater::class . '::updateAction',
    'methods' => ['POST'],
];
$routes[RouteMapping::TEAM_ASSIGNMENT_ROUTE_NAME] = [
    'path' => RouteMapping::TEAM_ASSIGNMENT_ROUTE,
    'target' => TeamAssignment::class . '::updateAction',
    'methods' => ['POST'],
];

$routes[RouteMapping::GET_ACCESS_CONFIG_ROUTE_NAME] = [
    'path' => RouteMapping::GET_ACCESS_CONFIG_ROUTE,
    'target' => GetAccessConfig::class . '::getAction',
    'methods' => ['POST'],
];

return $routes;