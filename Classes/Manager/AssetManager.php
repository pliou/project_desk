<?php

namespace Ppl\ProjectDesk\Manager;

use \TYPO3\CMS\Core\Page\AssetCollector;
use Ppl\ProjectDesk\Mapping\AssetMapping;

class AssetManager extends AssetCollector
{
    public function addByTab(array &$array, string $tab): void
    {
        $array["js_module"] = AssetMapping::JS_MODULE[$tab] ?? [];
    }
}
