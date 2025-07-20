<?php

namespace Ppl\ProjectDesk\Repository;

class LicenseRepository extends AbstractRepository
{
    const TABLE = 'tx_project_desk_license';
    protected const IDENTIFIER = 'default_license';

    public function saveKey(string $key): void
    {
        $qb = $this->getQueryBuilder();
        $existing = $this->getLicenseRow();

        if ($existing) {
            $qb->update(static::TABLE)
                ->where(
                    $qb->expr()->eq('identifier', $qb->createNamedParameter(self::IDENTIFIER))
                )
                ->set('license_key', $key)
                ->set('tstamp', time())
                ->executeStatement();
        } else {
            $qb->insert(static::TABLE)
                ->values([
                    'identifier' => self::IDENTIFIER,
                    'license_key' => $key,
                    'license_type' => '',
                    'tstamp' => time(),
                    'crdate' => time(),
                    'pid' => 0,
                ])
                ->executeStatement();
        }
    }

    public function setType(string $type): void
    {
        $qb = $this->getQueryBuilder();

        $qb->update(static::TABLE)
            ->where(
                $qb->expr()->eq('identifier', $qb->createNamedParameter(self::IDENTIFIER))
            )
            ->set('license_type', $type)
            ->set('tstamp', time())
            ->executeStatement();
    }

    public function getLicenseKey(): ?string
    {
        $qb = $this->getQueryBuilder();

        $row = $qb->select('*')
            ->from(static::TABLE)
            ->where(
                $qb->expr()->eq('identifier', $qb->createNamedParameter(self::IDENTIFIER)),
                $qb->expr()->eq('deleted', 0)
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return $row['license_key'] ?? null;
    }

        public function getLicenseRow(): ?array
    {
        $qb = $this->getQueryBuilder();

        return $qb->select('*')
            ->from(static::TABLE)
            ->where(
                $qb->expr()->eq('identifier', $qb->createNamedParameter(self::IDENTIFIER)),
                $qb->expr()->eq('deleted', 0)
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative() ?? null;
    }
}
