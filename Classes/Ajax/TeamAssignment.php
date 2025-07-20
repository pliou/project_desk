<?php

namespace Ppl\ProjectDesk\Ajax;

use TYPO3\CMS\Core\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class TeamAssignment extends AbstractAjaxController
{
    public function updateAction(ServerRequestInterface $request): JsonResponse
    {
        try {
            $body = json_decode($request->getBody()->getContents(), true);

            if (!is_array($body)) {
                throw new \InvalidArgumentException('Invalid JSON body');
            }

            if (!isset($body['action'], $body['teamUid'], $body['userId'])) {
                throw new \InvalidArgumentException('Missing required parameters: action, teamUid, or userId');
            }

            switch ($body['action']) {
                case 'add':
                    $this->dataHelper->addTeamAssignment($body['teamUid'], $body['userId']);
                    break;
                case 'delete':
                    $this->dataHelper->removeTeamAssignment($body['teamUid'], $body['userId']);
                    break;
                default:
                    throw new \InvalidArgumentException('Unsupported action: ' . $body['action']);
            }

            return new JsonResponse(['success' => true]);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Internal Server Error', 'details' => $e->getMessage()], 500);
        }
    }
}
