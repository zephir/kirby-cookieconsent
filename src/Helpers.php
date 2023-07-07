<?php

namespace Zephir\Cookieconsent;

class Helpers
{

    public static function getCookieconsentTranslation($key)
    {
        return t($key, null, option('languages') ? null : option('zephir.cookieconsent.defaultLocale'));
    }

    public static function getInitJs()
    {
        $options = Options::get(kirby()->option('zephir.cookieconsent.type'));
        return "
            var cc = initCookieConsent();
            cc.run(Object.assign(
                " . json_encode($options) . ",
                {
                    onAccept: function (cookie) {
                        const event = new CustomEvent('cookieConsentAccept', { detail: { cookie } });
                        window.dispatchEvent(event);
                    },
                    onChange: function (cookie, changed_categories) {
                        const event = new CustomEvent('cookieConsentChanged', { detail: { cookie, changed_categories } });
                        window.dispatchEvent(event);
                    },
                    onFirstAction: function (user_preferences, cookie) {
                        const event = new CustomEvent('cookieConsentFirstAction', { detail: { cookie, user_preferences } });
                        window.dispatchEvent(event);
                    }
                }
            ));
        ";
    }

}