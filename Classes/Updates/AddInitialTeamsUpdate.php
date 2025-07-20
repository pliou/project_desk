<?php

declare(strict_types=1);

namespace Ppl\ProjectDesk\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;

#[UpgradeWizard('addInitialTeams')]
final class AddInitialTeamsUpdate implements UpgradeWizardInterface
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {}

    public function getIdentifier(): string
    {
        return 'addInitialTeams';
    }

    public function getTitle(): string
    {
        return 'Add default team: Admins';
    }

    public function getDescription(): string
    {
        return 'Adds initial Admins group to tx_project_desk_team if missing.';
    }

    public function updateNecessary(): bool
    {
        $connection = $this->connectionPool->getConnectionForTable('tx_project_desk_team');
        $count = $connection->count('*', 'tx_project_desk_team', ['name' => 'Admins']);
        return $count === 0;
    }

    public function executeUpdate(): bool
    {
        $connection = $this->connectionPool->getConnectionForTable('tx_project_desk_team');
        $connection->insert('tx_project_desk_team', [
            'pid' => 0,
            'tstamp' => time(),
            'crdate' => time(),
            'deleted' => 0,
            'hidden' => 0,
            'name' => 'Admins',
        ]);
        return true;
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
