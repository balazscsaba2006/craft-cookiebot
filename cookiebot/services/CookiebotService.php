<?php

namespace Craft;

/**
 * Class CookiebotService
 *
 * @package   Craft
 * @author    Balazs Csaba <csaba.balazs@humandirect.eu>
 * @copyright 2018 Human Direct
 */
class CookiebotService extends BaseApplicationComponent
{
    private const COOKIE_NAME = 'CookieConsent';

    /**
     * @var \stdClass|null
     */
    private $cookieConsent;

    /**
     * @return bool
     */
    public function requiresConsent(): bool
    {
        return $this->isCookieSet() && '-1' !== $_COOKIE[self::COOKIE_NAME];
    }

    /**
     * @return bool
     */
    public function hasConsent(): bool
    {
        if (!$this->requiresConsent()) {
            return true;
        }

        return $this->isCookieSet() && '0' !== $_COOKIE[self::COOKIE_NAME];
    }

    /**
     * @return bool
     */
    public function hasPreferencesConsent(): bool
    {
        if (!$this->requiresConsent()) {
            return true;
        }

        return $this->decodeCookie()->preferences;
    }

    /**
     * @return bool
     */
    public function hasStatisticsConsent(): bool
    {
        if (!$this->requiresConsent()) {
            return true;
        }

        return $this->decodeCookie()->statistics;
    }

    /**
     * @return bool
     */
    public function hasMarketingConsent(): bool
    {
        if (!$this->requiresConsent()) {
            return true;
        }

        return $this->decodeCookie()->marketing;
    }

    /**
     * Renders CookieBot dialog script
     *
     * @param string|null $culture
     *
     * @return string
     * @throws Exception
     */
    public function renderDialogScript(string $culture = null): string
    {
        return $this->renderScript('dialog', $culture);
    }

    /**
     * Renders CookieBot declaration script
     *
     * @param string|null $culture
     *
     * @return string
     * @throws Exception
     */
    public function renderDeclarationScript(string $culture = null): string
    {
        return $this->renderScript('declaration', $culture);
    }

    /**
     * @return bool
     */
    private function isCookieSet(): bool
    {
        return isset($_COOKIE[self::COOKIE_NAME]);
    }

    /**
     * @return \stdClass
     */
    private function decodeCookie(): \stdClass
    {
        // already decoded cookie consent, return it
        if (null !== $this->cookieConsent) {
            return $this->cookieConsent;
        }

        // no cookie has been set, probably the first visit or a custom closable consent template used
        if (!$this->isCookieSet()) {
            $settings = craft()->plugins->getPlugin('cookiebot')->getSettings();
            $this->cookieConsent = $this->createConsentObject(
                $settings->defaultPreferences,
                $settings->defaultStatistics,
                $settings->defaultMarketing
            );

            return $this->cookieConsent;
        }

        if (!$this->requiresConsent()) {
            $this->cookieConsent = $this->createConsentObject(true, true, true);

            return $this->cookieConsent;
        }

        // the user has not accepted cookies
        if (!$this->hasConsent()) {
            $this->cookieConsent = $this->createConsentObject();

            return $this->cookieConsent;
        }

        $json = preg_replace(
            '/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/',
            ':"$1"$2',
            preg_replace(
                '/([{\[,])\s*([a-zA-Z0-9_]+?):/',
                '$1"$2":',
                str_replace("'", '"', stripslashes($_COOKIE[self::COOKIE_NAME]))
            )
        );
        $decoded = json_decode($json);

        $this->cookieConsent = $this->createConsentObject(
            filter_var($decoded->preferences, FILTER_VALIDATE_BOOLEAN),
            filter_var($decoded->statistics, FILTER_VALIDATE_BOOLEAN),
            filter_var($decoded->marketing, FILTER_VALIDATE_BOOLEAN)
        );

        return $this->cookieConsent;
    }

    /**
     * @param bool $preferences
     * @param bool $statistics
     * @param bool $marketing
     *
     * @return \stdClass
     */
    private function createConsentObject(bool $preferences = false, bool $statistics = false, bool $marketing = false): \stdClass
    {
        return (object) [
            'preferences' => $preferences,
            'statistics' => $statistics,
            'marketing' => $marketing,
        ];
    }

    /**
     * Renders CookieBot dialog script
     *
     * @param string      $type
     * @param string|null $culture
     *
     * @return string
     * @throws Exception
     */
    private function renderScript(string $type, string $culture = null): string
    {
        if (!$this->requiresConsent()) {
            return '';
        }

        $settings = craft()->plugins->getPlugin('cookiebot')->getSettings();
        $vars['domainGroupID'] = $settings->domainGroupID;
        $vars['culture'] = $culture;

        $oldPath = craft()->templates->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'cookiebot/templates';

        craft()->templates->setTemplatesPath($newPath);
        $html = craft()->templates->render('scripts/'.$type, $vars);
        craft()->templates->setTemplatesPath($oldPath);

        return $html;
    }
}
