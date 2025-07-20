<?php

namespace Ppl\ProjectDesk\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractRepository
{
    protected const TABLE = '';
    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool) {
        if (static::TABLE === '') {
            throw new \LogicException(static::class . ' requires a TABLE constant to be defined.');
        }
        $this->connectionPool = $connectionPool;
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->connectionPool->getQueryBuilderForTable(static::TABLE);
    }

    public function findAll(): array
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from(static::TABLE)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findAllActive($isHidden = false): array
    {
        $qb = $this->getQueryBuilder();

        return $qb
            ->select('*')
            ->from(static::TABLE)
            ->where(
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0))
            )
            ->andWhere(
                $qb->expr()->eq('hidden', $qb->createNamedParameter($isHidden ? 1 : 0))
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findAllDeleted($isHidden = false): array
    {
        $qb = $this->getQueryBuilder();

        return $qb
            ->select('*')
            ->from(static::TABLE)
            ->where(
                $qb->expr()->eq('deleted', $qb->createNamedParameter(1))
            )
            ->andWhere(
                $qb->expr()->eq('hidden', $qb->createNamedParameter($isHidden ? 1 : 0))
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }


    public function findByUid(int $uid): ?array
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from(static::TABLE)
            ->where(
                $this->getQueryBuilder()->expr()->eq('uid', $this->getQueryBuilder()->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAssociative() ?: null;
    }

    public function findByField(string $field, $value): array
    {
        $qb = $this->getQueryBuilder();
        return $qb
            ->select('*')
            ->from(static::TABLE)
            ->where(
                $qb->expr()->eq($field, $qb->createNamedParameter($value))
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
