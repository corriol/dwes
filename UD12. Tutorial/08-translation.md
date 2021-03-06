---
layout: default
title: 8. Translation
nav_order: 8
parent: 12. Tutorial de Symfony
has_children: false
nav_exclude: true
---

# Translation
{: .no_toc }

## Table of contents
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Installation

First, run this command to install the translator before using it:

```console
composer require symfony/translation
```

## Configuration

The previous command creates an initial config file where you can define the default locale of the application and the directory where the translation files are located:

```yaml
# config/packages/translation.yaml
framework:
    default_locale: 'en'
    translator:
        default_path: '%kernel.project_dir%/translations'
```

The locale used in translations is the one stored on the request. This is typically set via a `_locale` attribute on your routes (see [The Locale and the URL](https://symfony.com/doc/current/translation/locale.html)).

## How translation works

Translation of text is done through the translator service (Translator). To translate a block of text (called a **message**), use the `trans()` method. Suppose, for example, that you're translating a simple message from inside a controller:

```php
// ...
use Symfony\Contracts\Translation\TranslatorInterface;

public function index(TranslatorInterface $translator)
{
    $translated = $translator->trans('Symfony is great');

    // ...
}
```
When this code is executed, Symfony will attempt to translate the message "Symfony is great" based on the locale of the user. For this to work, you need to tell Symfony how to translate the message via a "translation resource", which is usually a file that contains a collection of translations for a given locale. This "dictionary" of translations can be created in several different formats:

```yaml
# translations/messages.fr.yaml
Symfony is great: J'aime Symfony
```

Now, if the language of the user's locale is French (e.g. fr_FR or fr_BE), the message will be translated into _J'aime Symfony_. You can also translate the message inside your templates.

To translate text in Twig templates Symfony implements several ways: tag and filter.

{% raw %}
```ruby
{% trans %}Symfony is great!{% endtrans %}
```
{% endraw %}

{% raw %}
```ruby
{{ 'menu.login' | trans }}
```
{% endraw %}

### The Translation Process

To actually translate the message, Symfony uses the following process when using the trans() method:

1. The locale of the current user, which is stored on the request is determined.
2. If the message is located in the catalog, the translation is returned. If not, the translator returns the original message.

### Message Format

Sometimes, a message containing a variable needs to be translated:
```php
// ...
$translated = $translator->trans('Logged as '.$username);
```

However, creating a translation for this string is impossible since the translator will try to look up the message including the variable portions (e.g. "Logged as admin" or "Logged as mperez").

Or there are translations that may or may not be plural, based on some variable:

```
There is one search result.
There are 5 search results.
```
To manage these situations Symfony follows the ICU MessageFormat syntax by using PHP's `MessageFormatter` class. Read more about this in How to Translate Messages using the [ICU MessageFormat](https://symfony.com/doc/current/translation/message_format.html).

Below, some examples:

```yaml
# messsages+int-icu.ca.yaml
logged as {username}: 'connectat com  {username}'
```

{% raw %}
```ruby
# _header.html.twig
{{ 'logged as {username}' | trans({'{username}': app.user.username}) }}
```
{% endraw %}

#### Pluralization

```yaml
# messages+intl-icu.en.yaml
search.results: "{count, plural,
      =0    {There are no apples}
      one   {There is one apple...}
      other {There are # apples!}
  }"
```

{% raw %}
```ruby
# Twig sample file
{{ 'search.results'|trans({'count': 2}) }}
{{ 'search.results'|trans({'count': 1}) }}
{{ 'search.results'|trans({'count': 0}) }}
```
{% endraw %}

The previous code snippet will show:

```
There are 2 apples! 
There is one apples...
There are no apples 
```

{:.alert .alert-info}
<div markdown="1">
### ICU Format php library
{: .nocount}

To use the ICU format library you need to install the `php-intl` library, the `symfony/intl` bundle and the `symfony/polyfill-intl-messageformatter` component.
</div>


More information: [Translation in templates](https://symfony.com/doc/current/translation/templates.html)


## Extracting Translation Contents and Updating Catalogs Automatically 

The most time-consuming tasks when translating an application is to extract all the template contents to be translated and to keep all the translation files in sync. Symfony includes a command called `translation:update` that helps you with these tasks:

To show a list of messages:

```bash
php bin/console translation:update --dump-messages --output-format=yaml en
```

To generate message files:

```bash
php bin/console translation:update --force --output-format=yaml en
```

To show help:
```bash
php bin/console translation:update --help
```

### Translation Resource/File Names and Locations

Symfony looks for message files (i.e. translations) in the following default locations:

 * the translations/ directory (at the root of the project);
 * the Resources/translations/ directory inside of any bundle.

The locations are listed here with the highest priority first. That is, you can override the translation messages of a bundle in the first directory.

The filename of the translation files is also important: each message file must be named according to the following path: `domain.locale.loader`:

* **domain**: An optional way to organize messages into groups.
* **locale**: The locale that the translations are for (e.g. en_GB, en, etc);
* **loader**: How Symfony should load and parse the file (e.g. xlf, php, yaml, etc).

The loader can be the name of any registered loader. By default, Symfony provides many loaders.

The choice of which loader to use is entirely up to you and is a matter of taste. The **recommended option is to use YAML for simple projects** and use XLIFF if you're generating translations with specialized programs or teams.

## Translating Link manager menu

### Applying filters in Twig template

{% raw %}
```html
<li class="nav-item">
    <a class="nav-link" href="{{ url('link_index') }}">{{ 'menu.links' | trans }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('user_index') }}">{{ 'menu.users' | trans }}</a>
</li>
<li class="nav-item">
    {% if is_granted('IS_AUTHENTICATED_FULLY') %}
    <a class="nav-link" href="{{ url('logout') }}"><i class="fa fa-sign-out-alt"> </i>{{ 'menu.logout' | trans }}</a>
    {% else %}
    <a class="nav-link" href="{{ url('login') }}"><i class="fa fa-sign-in-alt"></i> {{ 'menu.login' | trans }}</a>
    {% endif %}
</li>
```
{% endraw %}

### Extracting messages
Excuting this command Symfony generates the message files.

```bash
php bin/console translation:update --force --output-format=yaml en
```

The output will be generated in `translations/messages.en.yaml`:

```yaml
menu.links: __menu.links
menu.users: __menu.users
menu.logout: __menu.logout
menu.login: __menu.login
```

Now, you can translate the strings.

## Using Real or Keyword Messages

As we learned when talked about localization there are two different philosophies when creating messages to be translated:

```php
$translator->trans('Symfony is great');

$translator->trans('symfony.great');
```

In the first method, messages are written in the language of the default locale (English in this case). That message is then used as the "id" when creating translations.

In the second method, messages are actually "keywords" that convey the idea of the message. The keyword message is then used as the "id" for any translations. In this case, translations must be made for the default locale (i.e. to translate symfony.great to Symfony is great).

The second method is handy because the message key won't need to be changed in every translation file if you decide that the message should actually read "Symfony is really great" in the default locale.

The choice of which method to use is entirely up to you, but the "keyword" format is often recommended for multi-language applications, whereas for shared bundles that contain translation resources we recommend the real message, so your application can choose to disable the translator layer and you will see a readable message.

## Forms and translation

If you're using the Twig integration with one of the default form theme files (e.g. `form_div_layout.html.twig`), there is a Twig filter (trans) that is used for translating form labels, errors, option text and other strings.

The problem is that the extractor command cannot read these messages so you have to write them manually in or use customize form rendering, applying the `trans` filter to form labels.

More information on [Form customization](https://symfony.com/doc/current/form/form_customization.html).

## Handling the User's Locale

#TODO

