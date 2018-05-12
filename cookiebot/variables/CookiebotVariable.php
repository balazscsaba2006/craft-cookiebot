<?php

namespace Craft;

/**
 * CookiebotVariable class
 *
 * @package   Craft
 * @author    Balazs Csaba <csaba.balazs@humandirect.eu>
 * @copyright 2018 Human Direct
 */
class CookiebotVariable
{
    /**
     * @return bool
     */
    public function hasConsent(): bool
    {
        return craft()->cookiebot->hasConsent();
    }

    /**
     * @return bool
     */
    public function hasPreferencesConsent(): bool
    {
        return craft()->cookiebot->hasPreferencesConsent();
    }

    /**
     * @return bool
     */
    public function hasStatisticsConsent(): bool
    {
        return craft()->cookiebot->hasStatisticsConsent();
    }

    /**
     * @return bool
     */
    public function hasMarketingConsent(): bool
    {
        return craft()->cookiebot->hasMarketingConsent();
    }

    /**
     * Renders CookieBot dialog script
     *
     * @param string|null $culture
     *
     * @return string
     */
    public function dialogScript(string $culture = null): string
    {
        return craft()->cookiebot->renderDialogScript($culture);
    }

    /**
     * Renders CookieBot declaration script
     *
     * @param string|null $culture
     *
     * @return string
     */
    public function declarationScript(string $culture = null): string
    {
        return craft()->cookiebot->renderDeclarationScript($culture);
    }
}
