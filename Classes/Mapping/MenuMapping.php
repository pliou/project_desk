<?php

namespace Ppl\ProjectDesk\Mapping;

use Ppl\ProjectDesk\Service\TranslationService;

final class MenuMapping
{
    const MENU = [
        "my_task"  => "",
        "new_task"  => "",
        "board"  => "",
    ];

    static function getMenu(?string $active = null): array
    {
        $menu = [];
        foreach (self::MENU as $key => $value) {
            if ($active === $key) {
                $menu['active'] = TranslationService::tByD($key, 'menu');
            } else {
                $menu['navi'][$key] = TranslationService::tByD($key, 'menu');
            }
        }

        return $menu;
    }
}
