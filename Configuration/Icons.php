<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

return [
    'project-desk-icon-config' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:project_desk/Resources/Public/Icons/config.png',
        'size' => 'default',
    ],
    'project-desk-icon-desk' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:project_desk/Resources/Public/Icons/desk.png',
        'size' => 'default',
    ],
    'project-desk-icon-app' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:project_desk/Resources/Public/Icons/Extension.png',
        'size' => 'default',
    ],
    'project-desk-icon-project' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:project_desk/Resources/Public/Icons/project.png',
        'size' => 'default',
    ],
];