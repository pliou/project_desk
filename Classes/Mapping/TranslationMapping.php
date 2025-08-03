<?php

namespace Ppl\ProjectDesk\Mapping;

class TranslationMapping
{
   const MUST_TRANSLATE = [
        'label',
        'description',
        'placeholder',
    ];

    const TRANSLATION_DOMAIN = [
        'config' => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:',
        'desk' => 'LLL:EXT:project_desk/Resources/Private/Language/Desk/desk.xlf:',
        'form' => 'LLL:EXT:project_desk/Resources/Private/Language/Form/form.xlf:',
        'tabs' => 'LLL:EXT:project_desk/Resources/Private/Language/Tabs/tabs.xlf:',
        'javascript' => 'LLL:EXT:project_desk/Resources/Private/Language/JS/js.xlf:',
        'js' => 'LLL:EXT:project_desk/Resources/Private/Language/JS/js.xlf:',
        'task' => 'LLL:EXT:project_desk/Resources/Private/Language/Task/task.xlf:',
        'menu' => 'LLL:EXT:project_desk/Resources/Private/Language/Menu/menu.xlf:',
    ];
}
   

