<?php

declare(strict_types=1);

namespace Ppl\ProjectDesk\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[UpgradeWizard('assignAdminsToTeam')]
final class AssignAdminsToTeamUpdate implements UpgradeWizardInterface
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {}

    public function getIdentifier(): string
    {
        return 'assignAdminsToTeam';
    }

    public function getTitle(): string
    {
        return 'Assign admin users to Admins team';
    }

    public function getDescription(): string
    {
        return 'Finds all be_users with admin = 1 and adds them to the Admins team relation table.';
    }

    public function updateNecessary(): bool
    {
        $connection = $this->connectionPool->getConnectionForTable('tx_project_desk_team');
        $uid = $connection->select(
            ['uid'],
            'tx_project_desk_team',
            ['name' => 'Admins', 'deleted' => 0, 'hidden' => 0]
        )->fetchOne();

        return $uid !== false;
    }

    public function executeUpdate(): bool
    {
        $teamUid = $this->connectionPool
            ->getConnectionForTable('tx_project_desk_team')
            ->select(['uid'], 'tx_project_desk_team', ['name' => 'Admins', 'deleted' => 0, 'hidden' => 0])
            ->fetchOne();

        if (!$teamUid) {
            return false;
        }

        $adminUsers = $this->connectionPool
            ->getConnectionForTable('be_users')
            ->select(['uid'], 'be_users', ['admin' => 1, 'deleted' => 0, 'disable' => 0])
            ->fetchAllAssociative();

        $mmConnection = $this->connectionPool->getConnectionForTable('tx_project_desk_team_be_users_mm');

        foreach ($adminUsers as $user) {
            $mmConnection->insert(
                'tx_project_desk_team_be_users_mm',
                [
                    'uid_local' => $teamUid,
                    'uid_foreign' => (int)$user['uid'],
                ]
            );
        }

        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
