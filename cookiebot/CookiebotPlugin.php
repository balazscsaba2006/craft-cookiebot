<?php

namespace Craft;

/**
 * CookiebotPlugin class
 *
 * @package   Craft
 * @author    Balazs Csaba <csaba.balazs@humandirect.eu>
 * @copyright 2018 Human Direct
 */
class CookiebotPlugin extends BasePlugin
{
    /**
     * @return null|string
     */
    public function getName(): string
    {
        return Craft::t('Cookiebot');
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '1.1.0';
    }

    /**
     * @return string
     */
    public function getDeveloper(): string
    {
        return 'Balazs Csaba';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl(): string
    {
        return 'https://www.humandirect.eu';
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getSettingsHtml(): string
    {
        return craft()->templates->render('cookiebot/settings', [
            'settings' => $this->getSettings(),
        ]);
    }

    /**
     * @return array
     */
    protected function defineSettings(): array
    {
        return [
            'domainGroupID' => [AttributeType::String, 'default' => null],
            'defaultPreferences' => [AttributeType::Bool, 'default' => false, 'required' => false],
            'defaultStatistics' => [AttributeType::Bool, 'default' => false, 'required' => false],
            'defaultMarketing' => [AttributeType::Bool, 'default' => false, 'required' => false],
        ];
    }
}
