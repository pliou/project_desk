<?php

namespace Ppl\ProjectDesk\Mapping;

use Ppl\ProjectDesk\Controller\ConfigController;
use Ppl\ProjectDesk\Mapping\AssetCollectorMapping;

class AssetMapping
{
    const JS_MODULE = [
        ConfigController::TAB_NAMES[2] => [
            [
               "path" => 'EXT:project_desk/Resources/Public/JavaScript/modules/ProjectDeskLiveRepeater.js',
            ],
        ],        
        ConfigController::TAB_NAMES[3] => [
            [
                "path" => 'EXT:project_desk/Resources/Public/JavaScript/modules/UserTeamAssign.js',
            ],
        ], 
        ConfigController::TAB_NAMES[1] => [
            [
                "path" => 'EXT:project_desk/Resources/Public/JavaScript/modules/TeamTabSwitcher.js',
            ],
        ], 
    ];
}