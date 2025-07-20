<?php

namespace Ppl\ProjectDesk\Service;

use Ppl\ProjectDesk\Debugger\Debugger;
use Ppl\ProjectDesk\Mapping\ConfigurationMapping;
use Ppl\ProjectDesk\Service\TranslationService;
use Ppl\ProjectDesk\Repository\TeamRepository;
use Ppl\ProjectDesk\Repository\LicenseRepository;
use Ppl\ProjectDesk\Controller\ConfigController;
use Ppl\ProjectDesk\Helper\DataHelper;

class ConfigService
{
    public function __construct(
        private readonly TranslationService $translationService,
        private readonly TeamRepository $teamRepository,
        private readonly LicenseRepository $licenseRepository,
        private readonly DataHelper $dataHelper,
    ) {}

    /**
     *
     * @return []
     */
    public function getByTab(string $tab): array
    {
        $config = ConfigurationMapping::getByTab($tab);
        $this->translationService->translateConfig($config);
        switch ($tab) {
            case ConfigController::TAB_NAMES[0]: // 'general'
                // Keine spezifische Logik für den Tab 'general'
                break;

            case ConfigController::TAB_NAMES[1]: // 'access'
                break;

            case ConfigController::TAB_NAMES[2]: // 'team'
                $config[ConfigController::TAB_NAMES[2]]['value'] = $this->teamRepository->findAllNames();
                break;

            case ConfigController::TAB_NAMES[3]: // 'assign'
                $config[ConfigController::TAB_NAMES[3]]['value'] = $this->adjustTeamAssignment([
                    'user' => $this->dataHelper->getAllUser(),
                    'team' => $this->teamRepository->findAllActive(),
                    'assign' => $this->dataHelper->getTeamAssignment(),
                ]);
                break;

            case ConfigController::TAB_NAMES[4]: // 'license'
                $config[ConfigController::TAB_NAMES[4]]['value'] = $this->licenseRepository->getLicenseKey();
                break;
            default:
                throw new \UnexpectedValueException("Unknown tab '$tab'");
        }

        return $config;
    }

    private function adjustTeamAssignment(array $data): array
    {
        $return = [];
        $assignments = [];

        foreach ($data['assign'] as $assignment) {
            $assignments[$assignment['uid_foreign']][] = $assignment['uid_local'];
        }

        foreach ($data['team'] as $team) {
            $teamName = $team['name'];
            $teamUid = $team['uid'];
            $return[$teamName]['uid'] = $teamUid;

            foreach ($data['user'] as $user) {
                $userUid = $user['uid'];
                $userTeams = $assignments[$userUid] ?? [];

                if (in_array($teamUid, $userTeams, true)) {
                    $return[$teamName]['assigned'][] = [
                        'uid' => $userUid,
                        'name' => $user['username'],
                    ];
                } elseif (empty($userTeams)) {
                    $return[$teamName]['to_assign'][] = [
                        'uid' => $userUid,
                        'name' => $user['username'],
                    ];
                }
            }
        }

        return $return;
    }

}
