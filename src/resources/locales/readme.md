## Localization

For FE localization in project used plugin
[vue-i18n](https://kazupon.github.io/vue-i18n/)  
For BE supported default `laravel l10n`.

### Locales files structure

#### BE

The folder `src/resources/lang` contains `.php` files with locales.

#### FE

Locales files connect to templates by
[single file components](https://kazupon.github.io/vue-i18n/guide/sfc.html#basic-usage).  
The folder `src/resources/locales` contains `.json` files with locales and
repeats the folder `src/resources/js` structure.

### Special locales

The file `src/resources/locales/special-locales.json` contains special
localization words, which contains in other locales files.  
For example - word `compliance` localising like`certification`.

### Configuration

Initial configuration for locales are in `service.env`.

-   locale by default - `LOCALE_DEFAULT`;
-   fallback locale - `LOCALE_DEFAULT`;
-   supported locales - `LOCALE_SUPPORTED` (field format - `en|ru|ua|ja`);

Example for this variables you can see in `service.example.env`
