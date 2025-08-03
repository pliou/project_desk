<?php

namespace Ppl\ProjectDesk\Helper;

use Ppl\ProjectDesk\Debugger\Debugger;
use Ppl\ProjectDesk\Helper\AbstractDataHelper;
use Ppl\ProjectDesk\Mapping\ConfigurationMapping;

class GeneralDataHelper extends AbstractDataHelper
{
    const TABLE = 'tx_project_desk_general_config';

    public function getGeneralConfig(): array
    {
        $row = $this->fetchRowByField('uid', '1');

        return $this->decodeJsonFields($row, $this->getJsonKeysFromSchema('general'));
    }

    public function populateConfig($config): array
    {
        $row = $this->fetchRowByField('uid', '1');

        if (!$row) {
            return $config;
        }
        
        return $this->populateConfigValues($config, $row, 'general');
    }

    public function saveGeneralConfig(array $data): void
    {
        $data = $data['config'] ?? [];
        $schema = ConfigurationMapping::SCHEMA['general'];
        $jsonKeys = $this->getJsonKeysFromSchema('general');
        $fields = ['tstamp' => time()];

        foreach ($schema as $key => $definition) {
            $value = $data[$key] ?? $definition['value'];
            $fields[$key] = in_array($key, $jsonKeys, true)
                ? json_encode(is_array($value) ? $value : [])
                : $value;
        }

        $this->upsertRow(['uid' => '1'], $fields);
    }
}