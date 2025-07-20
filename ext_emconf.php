<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'project_desk',
    'description' => 'Editorial project and task management tool for TYPO3',
    'category' => 'module',
    'author' => 'Pawel Pliusnin',
    'author_email' => 'pliousnin@gmail.com',
    'state' => 'beta',
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-13.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Ppl\\ProjectDesk\\' => 'Classes/',
        ],
    ],
];
