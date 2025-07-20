<?php

declare(strict_types=1);

use Ppl\ProjectDesk\Controller\ConfigController;
use Ppl\ProjectDesk\Controller\ManagerController;
use Ppl\ProjectDesk\Mapping\RouteMapping;

return [
    'project' => [
        'parent' => null,
        'position' => ['after' => 'web'],
        'access' => 'user,group',
        'iconIdentifier' => 'project-desk-icon-desk',
        'extensionName' => 'ProjectDesk',
        'labels' => [
            'title' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:mlang_tabs_roottab_project',
        ],
    ],

    'project_desk_module_config' => [
        'parent' => 'project',
        'access' => 'admin',
        'position' => ['bottom' => 'tools'],
        'iconIdentifier' => 'project-desk-icon-config',
        'path' => RouteMapping::CONFIG_ROUTE,
        'labels' => [
            'title' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:mlang_tabs_tab_config',
            'description' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:mlang_tabs_tab_config_descr',
        ],
        'extensionName' => 'ProjectDesk',
        'routes' => [
            '_default' => [
                'target' => ConfigController::class . '::mainAction',
            ],
        ],
    ],

    'project_project_desk_app' => [
        'parent' => 'project',
        'access' => 'user',
        'iconIdentifier' => 'project-desk-icon-app',
        'path' => RouteMapping::ROUTE,
        'labels' => [
            'title' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:mlang_tabs_tab_manager',
            'description' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:mlang_tabs_tab_manager_descr',
        ],
        'extensionName' => 'ProjectDesk',
        'controllerActions' => [
            ManagerController::class => ['main'],
        ],
    ],
];
