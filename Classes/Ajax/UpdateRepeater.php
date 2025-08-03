<?php

namespace Ppl\ProjectDesk\Ajax;

use Ppl\ProjectDesk\Helper\AccessDataHelper;
use Ppl\ProjectDesk\Repository\TeamRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateRepeater
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly AccessDataHelper $accessDataHelper,
    ) {}

    public function updateAction(ServerRequestInterface $request): JsonResponse
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $action = $body['action'] ?? '';
        $field = $body['field'] ?? '';
        $value = trim($body['value'] ?? '');

        if (!in_array($action, ['add', 'remove', 'change'], true) || !$field || !$value) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        try {
            switch ($action) {
                case 'add':
                    $this->teamRepository->add($value);
                    break;

                case 'remove':
                    $this->teamRepository->removeByNames([$value]);
                    $this->accessDataHelper->deleteAccessByTeam($value);
                    break;

                case 'change':
                    $oldValue = trim($body['oldValue'] ?? '');
                    if (!$oldValue) {
                        return new JsonResponse(['error' => 'Missing old value'], 400);
                    }
                    $this->teamRepository->rename($oldValue, $value);
                    break;
            }

            return new JsonResponse(['success' => true]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
