<?php
declare(strict_types=1);

namespace Ppl\ProjectDesk\Service;

use TYPO3\CMS\Core\Page\AssetCollector;

final class AssetCollectorService extends AssetCollector
{
    const CSS_ASSET_PATH = [
        'task' => 'EXT:project_desk/Resources/Public/Css/pd_task.css',
        'config' => 'EXT:project_desk/Resources/Public/Css/pdmodule.css',
    ];
    public function addCssFile($key){
        if(isset(self::CSS_ASSET_PATH[$key])){
            $this->addStyleSheet($key, self::CSS_ASSET_PATH[$key]);
        }
    }
}
