<?php

namespace Ppl\ProjectDesk\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use Ppl\ProjectDesk\Service\AssetCollectorService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Ppl\ProjectDesk\Mapping\TranslationMapping;
use Ppl\ProjectDesk\Mapping\MenuMapping;

final class TaskController extends ActionController
{
    private const STATUS_OPTIONS = [
        'open',
        'in-progress',
        'done'
    ];

    private const ASSIGNEE_OPTIONS = [
        'Alice',
        'Bob',
        'Charlie'
    ];

    public function __construct(
        private AssetCollectorService $assetCollectorService
    ){}

    /**
     * Display a list of tasks assigned to the current user
     */
    public function myTaskAction(): ResponseInterface
    {
        $this->assetCollectorService->addCssFile('task');
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addCssFile('EXT:project_desk/Resources/Public/Css/task.css');
        $assignedTasks = [
            [
                'id' => 1,
                'title' => 'Review project proposal',
                'status' => [
                    'options' => self::STATUS_OPTIONS,
                    'active'  => 'in-progress'
                ],
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                'assignee' => [
                    'options' => self::ASSIGNEE_OPTIONS,
                    'active'  => 'Alice'
                ],
            ],
            [
                'id' => 2,
                'title' => 'Update documentation',
                'status' => [
                    'options' => self::STATUS_OPTIONS,
                    'active'  => 'done'
                ],
                'description' => 'Ensure all API endpoints are documented.',
                'assignee' => [
                    'options' => self::ASSIGNEE_OPTIONS,
                    'active'  => 'Bob'
                ],
            ],
            [
                'id' => 3,
                'title' => 'Refactor codebase',
                'status' => [
                    'options' => self::STATUS_OPTIONS,
                    'active'  => 'open'
                ],
                'description' => 'Improve the service layer architecture.',
                'assignee' => [
                    'options' => self::ASSIGNEE_OPTIONS,
                    'active'  => 'Charlie'
                ],
            ],
        ];

        $todoTasks = [
            [
                'id' => 4,
                'title' => 'Configure CI pipeline',
                'status' => [
                    'options' => self::STATUS_OPTIONS,
                    'active'  => ''
                ],
                'description' => '',
                'assignee' => [
                    'options' => self::ASSIGNEE_OPTIONS,
                    'active'  => ''
                ],
            ],
            [
                'id' => 5,
                'title' => 'Add unit tests',
                'status' => [
                    'options' => self::STATUS_OPTIONS,
                    'active'  => ''
                ],
                'description' => '',
                'assignee' => [
                    'options' => self::ASSIGNEE_OPTIONS,
                    'active'  => ''
                ],
            ],
        ];
        $openCount = count(array_filter($assignedTasks, fn($t) => $t['status']['active']=== self::STATUS_OPTIONS[0]));
        $progressCount = count(array_filter($assignedTasks, fn($t) => $t['status']['active']=== self::STATUS_OPTIONS[1]));
        $doneCount = count(array_filter($assignedTasks, fn($t) => $t['status']['active']=== self::STATUS_OPTIONS[2]));

        $this->view->assignMultiple([
            'assignedTasks' => $assignedTasks,
            'todoTasks'     => $todoTasks,
            'openCount'     => $openCount,
            'progressCount'     => $progressCount,
            'doneCount'     => $doneCount,
            'td' => TranslationMapping::TRANSLATION_DOMAIN['task'],
            'menu' => MenuMapping::getMenu('my_task'),
        ]);

        return $this->htmlResponse();
    }
}