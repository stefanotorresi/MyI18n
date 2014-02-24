MyI18n
===
[![Latest Stable Version](https://poser.pugx.org/stefanotorresi/my-i18n/v/stable.png)](https://packagist.org/packages/stefanotorresi/my-i18n)
[![Latest Unstable Version](https://poser.pugx.org/stefanotorresi/my-i18n/v/unstable.png)](https://packagist.org/packages/stefanotorresi/my-i18n)
[![Build Status](https://travis-ci.org/stefanotorresi/MyI18n.png?branch=master)](https://travis-ci.org/stefanotorresi/MyI18n)
[![Code Coverage](https://scrutinizer-ci.com/g/stefanotorresi/MyI18n/badges/coverage.png?s=48215f77594611dcb53ff4d59b4cba13b989dac4)](https://scrutinizer-ci.com/g/stefanotorresi/MyI18n/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/stefanotorresi/MyI18n/badges/quality-score.png?s=cd9b225a535e126fc40f6326eee08df85efef536)](https://scrutinizer-ci.com/g/stefanotorresi/MyI18n/)


MyI18n is a Zend Framework 2 module that provides some internationalization features for zf2 applications.
It's meant to be used along with [MyBackend](https://github.com/stefanotorresi/MyBackend).

Features
---

- Locale management via [MyBackend](https://github.com/stefanotorresi/MyBackend) UI
- Locale detection via different sorts of handlers
- A Navigation for language selection
- Doctrine [Translatable](https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md) extension wiring

TO-DO
---

- Lotsa unit testing
- Better route handler
- Detection handler management via UI
- `Zend\I18n\Translator\Translator` messages and domains management via UI

Disclaimer
---

This module is under heavy development.

Credits
---

The detection listeners are largely inspired by [Matt Cockayne](https://github.com/zucchi)'s [ZucchiLocale](https://github.com/zucchi/ZucchiLocale).
