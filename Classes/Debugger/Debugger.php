<?php
namespace Ppl\ProjectDesk\Debugger;
class Debugger
{
    public static function debug($var){
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($var);
    }
}
