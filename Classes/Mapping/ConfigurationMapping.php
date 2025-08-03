<?php

namespace Ppl\ProjectDesk\Mapping;

final class ConfigurationMapping
{
    const TASK_PERMISSIONS = [
        ['value' => 'create_new_task', 'label' => 'allow_to_create_new_task'],
        ['value' => 'delete_task',     'label' => 'allow_to_delete_task'],
        ['value' => 'assign_self',     'label' => 'allow_to_assign_self'],
        ['value' => 'assign_others',   'label' => 'allow_to_assign_others'],
    ];

    const PHASE_PERMISSIONS = [
        ['value' => 'move_task_between_phases', 'label' => 'allow_to_move_task_phase'],
        ['value' => 'create_phase',             'label' => 'allow_to_create_phase'],
        ['value' => 'delete_phase',             'label' => 'allow_to_delete_phase'],
        ['value' => 'merge_board',              'label' => 'allow_to_merge_boards'],
        ['value' => 'edit_board_structure',     'label' => 'allow_to_edit_board_structure'],
    ];

    const SHOW_BOARD_IN_FRONTEND = [
        ['value' => 'show_board_in_frontend', 'label' => 'show_board_in_frontend'],
    ];
    const ALLOW_ARCHIVING = [
        ['value' => 'allow_archiving', 'label' => 'allow_archiving'],
    ];

    public const TYPE_NUMBER        = 'number';
    public const TYPE_TEXTAREA      = 'textarea';
    public const TYPE_HIDDEN_INPUT  = 'hidden_input';
    public const TYPE_TEXT          = 'text';
    public const TYPE_DATE          = 'date';
    public const TYPE_RADIO         = 'radio';
    public const TYPE_CHECKBOXES    = 'checkboxes';
    public const TYPE_MULTI_SELECT  = 'multi-select';
    public const TYPE_REPEATER      = 'repeater';
    public const TYPE_ASSIGN        = 'assign';

    const SCHEMA = [
        'teams' => [
            'teams' => [
                'label'       => 'teams_label',
                'type'        => 'repeater',
                'description' => 'teams_description',
                'value'       => [],
                'placeholder' => 'teams_placeholder',
            ],
        ],
        'license' => [
            'license' => [
                'label'       => 'license_label',
                'type'        => 'text',
                'description' => 'license_description',
                'value'       => '',
                'placeholder' => 'license_placeholder',
            ],
        ],
        'assign' => [
            'assign' => [
                'label'       => 'assign_label',
                'type'        => self::TYPE_ASSIGN,
                'description' => 'assign_description',
                'value'       => [],
            ],
        ],
        'access' => [
            'team' => [
                'type'  => self::TYPE_HIDDEN_INPUT,
                'name'  => 'active_team',
                'value' => 'Admins',
            ],
            'task_permissions' => [
                'type'        => self::TYPE_CHECKBOXES,
                'label'       => 'task_permissions',
                'description'=> 'task_permissions_description',
                'value'       => [],
                'options'     => self::TASK_PERMISSIONS,
            ],
            'phase_permissions' => [
                'type'        => self::TYPE_CHECKBOXES,
                'label'       => 'phase_permissions',
                'description'=> 'phase_permissions_description',
                'value'       => [],
                'options'     => self::PHASE_PERMISSIONS,
            ],
        ],
        'general' => [
            'show_board_in_frontend' => [
                'type'        => self::TYPE_CHECKBOXES,
                'label'       => 'show_board_in_frontend_label',
                'description'=> 'show_board_in_frontend_description',
                'value'       => [],
                'options'     => self::SHOW_BOARD_IN_FRONTEND,
            ],
            'allow_archiving' => [
                'type'        => self::TYPE_CHECKBOXES,
                'label'       => 'allow_archiving_label',
                'description'=> 'allow_archiving_description',
                'value'       => [],
                'options'     => self::ALLOW_ARCHIVING,
            ],
        ],
    ];

    /**
     * 
     *
     * @return array<string,mixed>
     */
    public static function getByTab($tab): array
    {
        $schema = static::SCHEMA[$tab] ?? [];
        if (!empty($schema['startDate'])) {
            $schema['startDate']['value'] = date('Y-m-d');
        }

        return $schema;
    }
}
