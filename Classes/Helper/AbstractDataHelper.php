<?php

namespace Ppl\ProjectDesk\Helper;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Ppl\ProjectDesk\Mapping\ConfigurationMapping;

abstract class AbstractDataHelper
{
    const TABLE = '';
    protected const JSON_FIELD_TYPES = ['checkboxes', 'multi-select'];

    protected ConnectionPool $connectionPool;
    protected Connection $connection;

    public function __construct(ConnectionPool $connectionPool)
    {
        if (static::TABLE === '') {
            throw new \LogicException(static::class . ' requires a TABLE constant.');
        }
        $this->connectionPool = $connectionPool;
    }

    public function getConnection(): Connection
    {
        if (!isset($this->connection)) {
            $this->connection = $this->connectionPool->getConnectionForTable(static::TABLE);
        }
        
        return $this->connection;
    }

    protected function getAllKeysFromSchema(string $mode): array
    {
        return array_keys(ConfigurationMapping::SCHEMA[$mode] ?? []);
    }

    protected function getJsonKeysFromSchema(string $mode): array
    {
        $schema = ConfigurationMapping::SCHEMA[$mode] ?? [];
        return array_keys(array_filter($schema, fn($field) => in_array($field['type'], self::JSON_FIELD_TYPES, true)));
    }

    protected function fetchRowByField(string $field, string $value): ?array
    {
        $row = $this->getConnection()->fetchAssociative(
            sprintf('SELECT * FROM %s WHERE %s = :value AND deleted = 0', static::TABLE, $field),
            ['value' => $value]
        );

        return $row ?: null;
    }

    protected function decodeJsonFields(array $row, array $jsonKeys): array
    {
        foreach ($jsonKeys as $key) {
            if (isset($row[$key])) {
                $decoded = json_decode($row[$key], true);
                $row[$key] = is_array($decoded) ? $decoded : [];
            }
        }

        return $row;
    }

    /**
     * Füllt eine Config-Struktur (Schema) mit Werten aus einer DB-Zeile.
     */
        /**
     * Füllt eine Config-Definition mit Werten aus der DB-Zeile.
     *
     * @param array $configSchema    Konfigurations-Array aus ConfigurationMapping::SCHEMA
     * @param array $row             DB-Zeile mit rohen Werten
     * @param string $mode           Schema-Modus ('access' oder 'general')
     * @return array                 Config-Array mit ausgefüllten 'value'-Feldern
     */
    protected function populateConfigValues(array $configSchema, array $row, string $mode): array
    {
        $jsonKeys = $this->getJsonKeysFromSchema($mode);
        $result = $configSchema;
        foreach ($result as $key => &$field) {
            if (!isset($row[$key])) {
                continue;
            }
            $value = $row[$key];
            if (in_array($key, $jsonKeys, true)) {
                $decoded = json_decode($value, true);
                $field['value'] = is_array($decoded) ? $decoded : [];
            } else {
                $field['value'] = $value;
            }
        }

        return $result;
    }

    protected function upsertRow(array $identifier, array $fields): void
    {
        $key = key($identifier);
        $value = reset($identifier);
        $existing = $this->getConnection()->fetchOne(
            sprintf('SELECT uid FROM %s WHERE %s = :value AND deleted = 0', static::TABLE, $key),
            ['value' => $value]
        );
        if ($existing) {
            $this->getConnection()->update(static::TABLE, $fields, ['uid' => $existing]);
        } else {
            $fields['crdate'] = time();
            $fields['pid'] = 0;
            $this->getConnection()->insert(static::TABLE, $fields);
        }
    }
}