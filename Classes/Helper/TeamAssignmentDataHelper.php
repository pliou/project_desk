<?php

namespace Ppl\ProjectDesk\Helper;

use Ppl\ProjectDesk\Helper\AbstractDataHelper;

class TeamAssignmentDataHelper extends AbstractDataHelper
{
    const TABLE = 'tx_project_desk_team_be_users_mm';

    public function getTeamAssignment(): array
    {
        $data = [];
        $sql = '
            SELECT *
            FROM tx_project_desk_team_be_users_mm 
        ';

        $stmt = $this->getConnection()->executeQuery($sql);

        return $stmt->fetchAllAssociative();
    }

    public function addTeamAssignment(int $teamUid, int $userId): void
    {
        if ($this->findTeamAssignment($teamUid, $userId)) {
            return;
        }

        $this->getConnection()->insert(
            'tx_project_desk_team_be_users_mm',
            [
                'uid_local' => $teamUid,
                'uid_foreign' => $userId
            ]
        );
    }

    public function findTeamAssignment(int $teamUid, int $userId): bool
    {
        $sql = '
            SELECT 1
            FROM tx_project_desk_team_be_users_mm
            WHERE uid_local = :teamUid
            AND uid_foreign = :userId
            LIMIT 1
        ';

        $stmt = $this->getConnection()->executeQuery($sql, [
            'teamUid' => $teamUid,
            'userId' => $userId
        ]);

        return (bool) $stmt->fetchOne();
    }

    public function removeTeamAssignment(int $teamUid, int $userId): void
    {
        $this->getConnection()->delete(
            'tx_project_desk_team_be_users_mm',
            [
                'uid_local' => $teamUid,
                'uid_foreign' => $userId
            ]
        );
    }
}
