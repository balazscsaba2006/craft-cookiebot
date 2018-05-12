# CookieBot for Craft2

CookieBot integration into Craft CMS 2.

## Install

- Add the `cookiebot` directory into your `craft/plugins` directory.
- Navigate to `Settings -> Plugins` and click the "Install" button.
- Navigate to `Settings -> Plugins` and click on settings for Cookiebot
 
## Usage
Can be used to render dialog and declaration script on Twig templates
### Render dialog script:
```twig
{{ craft.cookiebot.dialogScript()|raw }}
```

### Render declaration script:
```twig
{{ craft.cookiebot.dialogScript()|raw }}
```

### Render dialog/declaration script in a certain language:
```twig
{% set locale = craft.i18n.getCurrentLocale() %}
{{ craft.cookiebot.dialogScript(locale.id)|raw }}
```


Can be used to check for consent on certain cookie categories like: Preferences, Statistics and Marketing.
### Checking for any consent:
```twig
{% if craft.cookiebot.hasConsent %}
    {# ... #}
{% endif %}
```

### Checking for consent on a specific category:
Preferences:
```twig
{% if craft.cookiebot.hasPreferencesConsent %}
    {# ... #}
{% endif %}
```

Statistics:
```twig
{% if craft.cookiebot.hasStatisticsConsent %}
    {# ... #}
{% endif %}
```

Marketing:
```twig
{% if craft.cookiebot.hasMarketingConsent %}
    {# ... #}
{% endif %}
```