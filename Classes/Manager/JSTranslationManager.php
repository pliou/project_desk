<?php

declare(strict_types=1);

namespace Ppl\ProjectDesk\Manager;

use TYPO3\CMS\Core\Page\PageRenderer;
use Ppl\ProjectDesk\Service\TranslationService;
use Ppl\ProjectDesk\Mapping\JSTranslationMapping;

final class JSTranslationManager
{
    public function __construct(
        private readonly TranslationService $translationService,
        private readonly PageRenderer $pageRenderer,
    ) {}

    public function addJSTranslations(string $domainKey, string $jsNamespace = 'ProjectDesk') : void
    {
        $translated = [];
        foreach (JSTranslationMapping::JS_DOMAIN[$domainKey] ?? [] as $tKey => $jsKey) {
            $translated[$jsKey] = $this->translationService->translateByDomain($tKey, 'javascript');
        }
        
        $this->pageRenderer->addInlineSetting($jsNamespace, 'translations', $translated);
    }
}