<?php

namespace Ppl\ProjectDesk\Controller;

use Ppl\ProjectDesk\Debugger\Debugger;
use Ppl\ProjectDesk\Service\ConfigService;
use Ppl\ProjectDesk\Service\AssignmentService;
use Ppl\ProjectDesk\Service\FlashMessageService;
use Ppl\ProjectDesk\Mapping\RouteMapping;
use Ppl\ProjectDesk\Mapping\AssetCollectorMapping;
use Ppl\ProjectDesk\Mapping\TranslationMapping;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use Ppl\ProjectDesk\Repository\TeamRepository;
use Ppl\ProjectDesk\Repository\LicenseRepository;
use TYPO3\CMS\Core\Http\RedirectResponse;
use Ppl\ProjectDesk\Manager\JSTranslationManager;
use Ppl\ProjectDesk\Service\TranslationService;
use Ppl\ProjectDesk\Manager\AssetManager;


final class ConfigController
{
    // Index is important here, as it defines the tab in the UI
    const TAB_NAMES = [
        0 => 'general', // 0
        1 => 'access', // 1
        2 => 'teams', // 2 
        3 => 'assign', // 3
        4 => 'license', // 4
    ];
    
    // These labels are used in the backend module to display the tabs, you can change the order here
    const TABS = [
        self::TAB_NAMES[0] => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:config',
        self::TAB_NAMES[2] => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:teams',
        self::TAB_NAMES[1] => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:access',
        self::TAB_NAMES[3] => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:assign',
        self::TAB_NAMES[4] => 'LLL:EXT:project_desk/Resources/Private/Language/Config/config.xlf:license',
    ];

    const NEEDS_SAVE = [
        self::TAB_NAMES[0] => true, // 'general'
        self::TAB_NAMES[1] => true, // 'access'
        self::TAB_NAMES[2] => false, // 'teams'
        self::TAB_NAMES[3] => false, // 'assign'
        self::TAB_NAMES[4] => true, // 'license'
    ];

    public function __construct(
        private readonly ConfigService $configService,
        private readonly AssignmentService $assignmentService,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly UriBuilder $uriBuilder,
        private readonly TeamRepository $teamRepository,
        private readonly FlashMessageService $flashMessageService,
        private readonly LicenseRepository $licenseRepository,
        private readonly TranslationService $translationService,
        private readonly JSTranslationManager $jSTranslationManager,
        private readonly AssetManager $assetManager,
    ) {}

    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(self::TAB_NAMES[0], $request);
    }

    public function generalAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(self::TAB_NAMES[0], $request);
    }

    public function accessAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(self::TAB_NAMES[1], $request);
    }

    public function teamsAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->jSTranslationManager->addJSTranslations('repeater');
        return $this->render(self::TAB_NAMES[2], $request);
    }

    public function assignAction(ServerRequestInterface $request): ResponseInterface
    {
        // $this->assetCollectorManager->addStyleSheetByIdentifier(AssetCollectorMapping::USER_TEAM_ASSIGN_CSS_IDENTIFIER);
        return $this->render(self::TAB_NAMES[3], $request);
    }

    public function licenseAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(self::TAB_NAMES[4], $request);
    }


    private function render(string $tabKey, ServerRequestInterface $request): ResponseInterface
    {
        if (!in_array($tabKey, self::TAB_NAMES, true)) {
            $tabKey = self::TAB_NAMES[0];
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($request);
        $moduleTemplate->setTitle('Projectdesk Configuration');
        

        $this->assignUri($moduleTemplate);

        $templateVariables = [
            'activeTab' => $tabKey,
            'config'    => $this->configService->getByTab($tabKey),
            'error'    => $this->flashMessageService->getFlashError($tabKey),
            'info'    => $this->flashMessageService->getFlashInfo($tabKey),
            'td' => TranslationMapping::TRANSLATION_DOMAIN['config'],
            'td_form' => TranslationMapping::TRANSLATION_DOMAIN['form'],
            'needsSave' => self::NEEDS_SAVE[$tabKey] ?? false,
            'team_tab' => $tabKey === ConfigController::TAB_NAMES[1] ? $this->teamRepository->findAllNames() : [],
        ];

        Debugger::debug($templateVariables);
        // bind @ html
        $this->assetManager->addByTab($templateVariables, $tabKey);

        $moduleTemplate->assignMultiple($templateVariables);

        return $moduleTemplate->renderResponse('Config/Main');
    }

    private function assignUri($template) {
        $tabUrls = [];
        $saveUrls = [];
        foreach (self::TAB_NAMES as $tabKey) {
            $tabUrls[$tabKey] = (string) $this->uriBuilder
                ->buildUriFromRoute(RouteMapping::EXTENSION_ROUTE_PREFIX . $tabKey);
            $saveUrls[$tabKey] = (string) $this->uriBuilder
                ->buildUriFromRoute(RouteMapping::EXTENSION_ROUTE_PREFIX . $tabKey . '_save');
        }

        $template->assignMultiple([
            'tabs'      => self::TABS,
            'tabUrls'   => $tabUrls,
            'saveUrls'  => $saveUrls,
        ]);
    }

    public function saveGeneralAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->saveTab(self::TAB_NAMES[0], $request);
    }

    public function saveAccessAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->saveTab(self::TAB_NAMES[1], $request);
    }

    public function saveTeamsAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->saveTab(self::TAB_NAMES[2], $request);
    }

    public function saveAssignAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->saveTab(self::TAB_NAMES[3], $request);
    }

    public function saveLicenseAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->saveTab(self::TAB_NAMES[4], $request);
    }

    private function saveTab(string $tab, ServerRequestInterface $request): ResponseInterface
    {
        try {
            $translatedTab = $this->translationService->translateByDomain($tab, 'config');
            $infoMessage = $this->translationService
                ->translateByDomain(
                    'beginning_info_message',
                    'config',
                    'project_desk',
                    [$translatedTab]
                );
            $data = $request->getParsedBody();
            switch ($tab) {
                case self::TAB_NAMES[0]: // 'general'
                    // No specific logic for 'general' tab, just a placeholder
                    break;
                case self::TAB_NAMES[1]: // 'access'
                    // $this->assignmentService->syncWith($data['config'][$tab] ?? []);
                    break;

                case self::TAB_NAMES[2]: // 'team'
                    // $this->teamRepository->syncWith($data['config'][$tab] ?? []);
                    break;
                case self::TAB_NAMES[3]: // 'assign'
                    $this->assignmentService->syncWith($data['config'][$tab] ?? []);
                    break;

                case self::TAB_NAMES[4]: // 'license'
                    $this->licenseRepository->saveKey($data['config'][$tab] ?? '');
                    break;
                default:
                    throw new \UnexpectedValueException("Unknown tab '$tab'");
            }
        } catch (\Exception $e) {
            $errorMessage = $this->translationService
                ->translateByDomain(
                    'begining_error_message',
                    'config',
                    'project_desk',
                    [$translatedTab]
                ) . $e->getMessage();
        }

        return $this->backLink($tab, $infoMessage, $errorMessage ?? null);
    }

    private function backLink(string $tab, string $infoMessage, ?string $errorMessage = null): ResponseInterface
    {
        $uri = $this->uriBuilder->buildUriFromRoute(RouteMapping::EXTENSION_ROUTE_PREFIX . $tab);
        
        if ($errorMessage) {
            $this->flashMessageService->setFlashError($tab, $errorMessage);
        }else {
            $this->flashMessageService->setFlashInfo($tab, $infoMessage);
        }

        return new RedirectResponse((string) $uri);
    }
    
}