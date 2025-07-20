<?php

namespace Ppl\ProjectDesk\Mapping;

final class ConfigurationMapping
{
    private const SCHEMA = [
        'default' => [
            'maxItems' => [
                'label'       => 'Maximale Einträge',
                'type'        => 'number',
                'description' => 'Wie viele Einträge maximal erlaubt sind',
                'value'       => 10,
                'min'         => 1,
                'max'         => 100,
            ],
            'description' => [
                'label'       => 'Beschreibung',
                'type'        => 'textarea',
                'description' => 'Freitext zur Konfiguration',
                'value'       => '',
                'rows'        => 3,
            ],
            'mode' => [
                'label'       => 'Betriebsmodus',
                'type'        => 'radio',
                'description' => 'Standard- oder Expertenmodus?',
                'value'       => 'standard',
                'options'     => [
                    ['value' => 'standard', 'label' => 'Standard'],
                    ['value' => 'expert',   'label' => 'Experten'],
                ],
            ],
            'features' => [
                'label'       => 'Aktive Features',
                'type'        => 'checkboxes',
                'description' => 'Wähle die aktivierten Features',
                'value'       => ['logging'],
                'options'     => [
                    ['value' => 'logging',      'label' => 'Logging'],
                    ['value' => 'notifications','label' => 'Notifications'],
                    ['value' => 'analytics',    'label' => 'Analytics'],
                ],
            ],
            'startDate' => [
                'label'       => 'Startdatum',
                'type'        => 'date',
                'description' => 'Wann soll das Feature beginnen?',
            ],
            'teams' => [
                'label'       => 'Teams',
                'type'        => 'repeater',
                'description' => 'Lege hier eine oder mehrere Teams an',
                'value'       => [],
                'placeholder' => 'Team-Name eingeben…',
            ],
        ],
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
                'type'        => 'assign',
                'description' => 'assign_description',
                'value'       => [],
            ],
        ],
        'access' => [
            'assign' => [
                'label'       => 'Aktive Features',
                'type'        => 'checkboxes',
                'description' => 'Wähle die aktivierten Features',
                'value'       => ['logging'],
                'options'     => [
                    ['value' => 'logging',      'label' => 'Logging'],
                    ['value' => 'notifications','label' => 'Notifications'],
                    ['value' => 'analytics',    'label' => 'Analytics'],
                ],
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
        $schema = static::SCHEMA[$tab] ?? static::SCHEMA['default'];
        $schema['startDate']['value'] = date('Y-m-d');

        return $schema;
    }
}
