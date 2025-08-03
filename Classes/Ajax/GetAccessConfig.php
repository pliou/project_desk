<?php

namespace Ppl\ProjectDesk\Ajax;

use TYPO3\CMS\Core\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Ppl\ProjectDesk\Helper\AccessDataHelper;

final class GetAccessConfig
{
    public function __construct(
        protected readonly AccessDataHelper $accessDataHelper,
    ) {}

    public function getAction(ServerRequestInterface $request): JsonResponse
    {
        $body = json_decode($request->getBody()->getContents(), true);

        try {
            $config = $this->accessDataHelper->getAccessByTeam($body);
            return new JsonResponse([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
