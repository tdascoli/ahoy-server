/*! ko.i18n.js | Knockout i18n module. */

;(function() {
    if (!window.ko) throw new Error('Translation requires knockout.');

    // private observable
    var _bundles = ko.observable({});

    ko.i18n = {
        locale: ko.observable('en'),
        defaultLocale: ko.observable('en'),

        // Sets language bundle under specified locale
        setBundle: function(locale, bundle) {
            var value = _bundles();

            value[locale] = bundle;

            _bundles(value);
        },

        // Sets multiple bundles with the key as locales
        setBundles: function(bundles) {
            var value = _bundles();

            Object.keys(bundles).forEach(function(locale) {
                value[locale] = bundles[locale];
            });

            _bundles(value);
        },

        // Sets default locale
        setDefaultLocale: function(locale) {
            ko.i18n.defaultLocale(locale);
        },

        // Sets a locale, if locale is not in list, takes default locale
        setLocale: function(locale) {
            var locales = _bundles();
            var iso639 = locale.substr(0,2);
            if (locale in locales){
                // exact match
                ko.i18n.locale(locale);
            }
            else if (iso639 in locales) {
                // iso639 language match
                ko.i18n.locale(iso639);
            }
            else {
                // no match, takes default
                ko.i18n.locale(ko.i18n.defaultLocale());
            }
        }
    };

    ko.bindingHandlers.i18n = {
        update: function(elm, valueAccessor, bindings) {
            var value = ko.unwrap(valueAccessor());
            // , bundle = bundles()[ko.i18n.locale()] || {};

            if ( typeof value == "string" ) {
                value = { $: value };
            }

            Object.keys(value).forEach(function(attr) {
                var _value = getBundleValue(value[attr]);

                // todo; check if there are dynamic substitudes in bindings
                if ( bindings.has('i18n-options') ) {
                    var substitudes = bindings.get('i18n-options') || {};

                    // todo; create a computed that substitudes values into bundle value.
                    for ( var key in substitudes ) {
                        if ( substitudes.hasOwnProperty(key) ) {
                            _value = _value.replace('{{' + key + '}}', ko.unwrap(substitudes[key]));
                        }
                    }
                }

                if ( attr == '$' ) {
                    elm.innerHTML = _value;
                }
                else {
                    elm.setAttribute(attr, _value);
                }
            });
        }
    };

    function getBundleValue(key) {
        var locales = ko.i18n.locale(),
            value = _bundles();

        if ( !Array.isArray(locales) ) {
            locales = [locales];
        }

        return locales.reduce(function(result, locale) {
            return result || (value[locale] || {})[key];
        }, undefined) || key;
    }
})();