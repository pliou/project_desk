<?php

namespace Ppl\ProjectDesk\Helper;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class DataHelper
{
       /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    public function setConnection(string $table): void
    {
        $this->connection = $this->connectionPool->getConnectionForTable($table);;
    }

    public function getAllUser(): array
    {
        $this->setConnection('be_user');
        $data = [];
        $sql = '
            SELECT *
            FROM be_users 
            WHERE 
                deleted = 0 
                AND disable = 0
                AND username != "_cli_"
        ';
        $stmt = $this->connection->executeQuery($sql);

        return $stmt->fetchAllAssociative();
    }

        public function getTeamAssignment(): array
    {
        $this->setConnection('tx_project_desk_team_be_users_mm');
        $data = [];
        $sql = '
            SELECT *
            FROM tx_project_desk_team_be_users_mm 
        ';

        $stmt = $this->connection->executeQuery($sql);

        return $stmt->fetchAllAssociative();
    }

    public function addTeamAssignment(int $teamUid, int $userId): void
    {
        $this->setConnection('tx_project_desk_team_be_users_mm');

        // Dubletten vermeiden
        if ($this->findTeamAssignment($teamUid, $userId)) {
            return;
        }

        $this->connection->insert(
            'tx_project_desk_team_be_users_mm',
            [
                'uid_local' => $teamUid,
                'uid_foreign' => $userId
            ]
        );
    }

    public function findTeamAssignment(int $teamUid, int $userId): bool
    {
        $this->setConnection('tx_project_desk_team_be_users_mm');

        $sql = '
            SELECT 1
            FROM tx_project_desk_team_be_users_mm
            WHERE uid_local = :teamUid
            AND uid_foreign = :userId
            LIMIT 1
        ';
        $stmt = $this->connection->executeQuery($sql, [
            'teamUid' => $teamUid,
            'userId' => $userId
        ]);

        return (bool)$stmt->fetchOne();
    }

    public function removeTeamAssignment(int $teamUid, int $userId): void
    {
        $this->setConnection('tx_project_desk_team_be_users_mm');

        $this->connection->delete(
            'tx_project_desk_team_be_users_mm',
            [
                'uid_local' => $teamUid,
                'uid_foreign' => $userId
            ]
        );
    }
}
