<?php

namespace Ppl\ProjectDesk\Service;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class FlashMessageService
{
    const ACTIVE_TEAM = 'active_team';
    const ERROR = 'error';
    const INFO = 'info';

    protected BackendUserAuthentication $backendUser;
    protected string $sessionKey = 'project_desk_flash_messages';

    public function __construct()
    {
        $this->backendUser = $GLOBALS['BE_USER'];
    }

    public function setFlashError(string $action, string $message): void
    {
        $this->setFlashMessage($action, self::ERROR, $message);
    }

    public function setFlashInfo(string $action, string $message): void
    {
        $this->setFlashMessage($action, self::INFO, $message);
    }

    public function getFlashError(string $action): ?string
    {
        return $this->getFlashMessage($action, self::ERROR);
    }

    public function getFlashInfo(string $action): ?string
    {
        return $this->getFlashMessage($action, self::INFO);
    }

    public function setFlashMessage(string $action, string $type, string $message): void
    {
        $userId = $this->getUserId();
        $flashMessages = $this->getAllFlashMessages();

        if (!isset($flashMessages[$userId])) {
            $flashMessages[$userId] = [];
        }
        if (!isset($flashMessages[$userId][$action])) {
            $flashMessages[$userId][$action] = [];
        }

        $flashMessages[$userId][$action][$type] = $message;
        $this->saveAllFlashMessages($flashMessages);
    }

    public function getFlashMessage(string $action, string $type): ?string
    {
        $userId = $this->getUserId();
        $flashMessages = $this->getAllFlashMessages();

        if (isset($flashMessages[$userId][$action][$type])) {
            $message = $flashMessages[$userId][$action][$type];

            unset($flashMessages[$userId][$action][$type]);

            if (empty($flashMessages[$userId][$action])) {
                unset($flashMessages[$userId][$action]);
            }

            $this->saveAllFlashMessages($flashMessages);

            return $message;
        }

        return null;
    }

    protected function getUserId(): string
    {
        return (string)($this->backendUser->user['uid'] ?? 'anonymous');
    }

    /**
     *
     * @return array
     */
    protected function getAllFlashMessages(): array
    {
        return $this->backendUser->getSessionData($this->sessionKey) ?? [];
    }

    /**
     *
     * @param array $messages
     * @return void
     */
    protected function saveAllFlashMessages(array $messages): void
    {
        $this->backendUser->setAndSaveSessionData($this->sessionKey, $messages);
    }
}
