<?php

namespace Ppl\ProjectDesk\Helper;

use Ppl\ProjectDesk\Helper\AbstractDataHelper;

class AccessDataHelper extends AbstractDataHelper
{
    const TABLE = 'tx_project_desk_access_config_by_team';

    public function saveAccess(array $data): void
    {
        $configData = $data['config'] ?? [];
        $team = $configData['active_team'] ?? null;
        if (!$team) {
            throw new \InvalidArgumentException('active_team is required.');
        }

        $jsonKeys = $this->getJsonKeysFromSchema('access');
        $fields = ['tstamp' => time(), 'team' => $team];

        foreach ($jsonKeys as $key) {
            $value = $configData[$key] ?? [];
            $fields[$key] = json_encode(is_array($value) ? $value : []);
        }

        foreach ($configData as $key => $value) {
            if ($key === 'active_team' || in_array($key, $jsonKeys, true)) {
                continue;
            }
            $fields[$key] = $value;
        }

        $this->upsertRow(['team' => $team], $fields);
    }

    public function populateConfig($config): array
    {
        $team = $config['team']['value'] ?? null;
        if (!$team) {
            throw new \InvalidArgumentException('No team selected in config.');
        }

        $row = $this->fetchRowByField('team', $team);

        if (!$row) {
            return $config;
        }
        
        return $this->populateConfigValues($config, $row, 'access');
    }

    // public function getAccessConfigByTeam(array $config): array
    // {
    //     $team = $config['team']['value'] ?? null;
    //     if (!$team) {
    //         throw new \InvalidArgumentException('No team selected in config.');
    //     }

    //     $row = $this->fetchRowByField('team', $team);
    //     if (!$row) {
    //         return $config;
    //     }

    //     $jsonKeys = $this->getJsonKeysFromSchema('access');
    //     $row = $this->decodeJsonFields($row, $jsonKeys);

    //     foreach ($config as $key => &$field) {
    //         if ($key === 'team') {
    //             continue;
    //         }
    //         if (array_key_exists($key, $row)) {
    //             $field['value'] = $row[$key];
    //         }
    //     }

    //     return $config;
    // }

    public function getAccessByTeam(array $data): array
    {
        $team = $data['team'] ?? null;
        if (!$team) {
            throw new \InvalidArgumentException('No team provided in data.');
        }

        $row = $this->fetchRowByField('team', $team);
        if (!$row) {
            return [];
        }

        $jsonKeys = $this->getJsonKeysFromSchema('access');
        
        return $this->decodeJsonFields($row, $jsonKeys);
    }

    public function deleteAccessByTeam(string $team): void
    {
        if (!$team) {
            throw new \InvalidArgumentException('Team identifier must not be empty.');
        }

        $this->getConnection()->update(
            'tx_project_desk_access_config_by_team',
            ['deleted' => 1, 'tstamp' => time()],
            ['team' => $team, 'deleted' => 0]
        );
    }
}
