<?php

namespace Ppl\ProjectDesk\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class BoardController extends ActionController
{
    /**
     * Display a list of tasks assigned to the current user
     */
    public function boardAction(ServerRequestInterface $request): ResponseInterface
    {
        // Dummy data: assigned tasks
        $assignedTasks = [
            ['id' => 1, 'title' => 'Review project proposal', 'status' => 'in-progress'],
            ['id' => 2, 'title' => 'Update documentation', 'status' => 'open'],
            ['id' => 3, 'title' => 'Refactor codebase', 'status' => 'open'],
        ];

        // Dummy data: sidebar TODOs
        $todoTasks = [
            ['id' => 4, 'title' => 'Configure CI pipeline'],
            ['id' => 5, 'title' => 'Add unit tests'],
        ];

        $this->view->assignMultiple([
            'assignedTasks' => $assignedTasks,
            'todoTasks' => $todoTasks,
        ]);

        return $this->htmlResponse();
    }
}
