<?php

namespace Ppl\ProjectDesk\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Ppl\ProjectDesk\Repository\AbstractRepository;

class TeamRepository extends AbstractRepository
{
    const TABLE = 'tx_project_desk_team';

    public function add(string $name): void
    {
        $qb = $this->getQueryBuilder(static::TABLE);
        $qb
            ->insert(static::TABLE)
            ->values([
                'pid' => 0,
                'crdate' => time(),
                'tstamp' => time(),
                'name' => $name
            ])
            ->executeStatement();
    }

    public function syncWith(array $teamNames): void
    {
        $existingTeams = $this->findAllActive();
        $existingNames = array_column($existingTeams, 'name');
        $namesToAdd = array_diff($teamNames, $existingNames);
        $namesToDelete = array_diff($existingNames, $teamNames);

        foreach ($namesToAdd as $name) {
            $this->add($name);
        }

        $this->removeByNames($namesToDelete);
    }

    public function removeByNames(array $teamNamesToDelete): void
    {
        if (empty($teamNamesToDelete)) {
            return;
        }

        $queryBuilder = $this->getQueryBuilder();

        $uidsToDelete = $queryBuilder
            ->select('uid')
            ->from(static::TABLE)
            ->where(
                $queryBuilder->expr()->in(
                    'name',
                    $queryBuilder->createNamedParameter($teamNamesToDelete, Connection::PARAM_STR_ARRAY)
                ),
                $queryBuilder->expr()->eq('deleted', 0)
            )
            ->executeQuery()
            ->fetchFirstColumn();

        if (empty($uidsToDelete)) {
            return;
        }

        $queryBuilder
            ->update(static::TABLE)
            ->where(
                $queryBuilder->expr()->in('uid', $queryBuilder->createNamedParameter($uidsToDelete, Connection::PARAM_INT_ARRAY))
            )
            ->set('deleted', 1)
            ->executeStatement();

        $queryBuilder = $this->getQueryBuilder(); 
        $queryBuilder
            ->delete('tx_project_desk_team_be_users_mm')
            ->where(
                $queryBuilder->expr()->in('uid_local', $queryBuilder->createNamedParameter($uidsToDelete, Connection::PARAM_INT_ARRAY))
            )
            ->executeStatement();
    }


    public function findAllNames(): array
    {
        $queryBuilder = $this->getQueryBuilder();

        $rows = $queryBuilder
            ->select('name')
            ->from(static::TABLE)
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        return array_column($rows, 'name');
    }

    public function rename(string $oldName, string $newName): void
    {
        $qb = $this->getQueryBuilder(static::TABLE);

        $qb->update(static::TABLE)
            ->where(
                $qb->expr()->eq('name', $qb->createNamedParameter($oldName)),
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0))
            )
            ->set('name', $newName)
            ->set('tstamp', time())
            ->executeStatement();
    }
}
