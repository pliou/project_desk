<?php
declare(strict_types=1);

namespace Ppl\ProjectDesk\Service;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Ppl\ProjectDesk\Mapping\TranslationMapping;

final class TranslationService
{
    public function translateConfig(array &$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->translateConfig($value);
            } elseif (in_array($key, TranslationMapping::MUST_TRANSLATE)) {
                $value = $this->translateByDomain(
                    $value,
                    'config',
                    'project_desk',
                    $array[$value] ?? []
                );
            }
        }
    }

    public function translate(string $key, string $extensionName = 'project_desk', array $arguments = []): string
    {
        $translated = LocalizationUtility::translate($key, $extensionName, $arguments);

        return $translated !== null ? $translated : $key;
    }

    public function translateByDomain(string $key, string $domain, string $extensionName = 'project_desk', array $arguments = []): string
    {
        $translated = LocalizationUtility::translate(
            (TranslationMapping::TRANSLATION_DOMAIN[$domain] ?? '') . $key, 
            $extensionName, 
            $arguments
        );

        return $translated !== null ? $translated : $key;
    }
}
