# PHP Coding Standards Fixer <!-- omit in toc -->

- [Overview](#overview)
  - [Vs PHP Code Sniffer](#vs-php-code-sniffer)
- [Installation](#installation)
- [Usage](#usage)
  - [Docker](#docker)
- [Configuration](#configuration)
  - [Optional composer helper](#optional-composer-helper)
- [Rules](#rules)
  - [Describing rules](#describing-rules)
  - [Risky Rules](#risky-rules)
  - [YOUR rules](#your-rules)
- [Migration helpers](#migration-helpers)

## Overview

PHP Coding Standard Fixer (PHP CS Fixer) updates your code to

- Confirm to PSR standards.
- Confirm to community/framework standards.
- Modernize syntax.
- Test or migrate against a different PHP version.

[Homepage](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

- Supports PHP >= 7.4

### Vs PHP Code Sniffer

Overlap exists between PHP CS Fixer and PHP CS.

- PHPCS scans for problems, sometimes it can fix them.
- PHP CS Fixer corrects code to conform.

## Installation

- To install in a composer project:

```shell
composer require --dev friendsofphp/php-cs-fixer
```

- To confirm installed version:

```shell
$ vendor/bin/php-cs-fixer -v
PHP CS Fixer 3.56.0 15 Keys Accelerate by Fabien Potencier, Dariusz Ruminski and contributors.
PHP runtime: 8.2.18
```

## Usage

- To fix the `app` folder:

```shell
vendor/bin/php-cs-fixer fix app
```

- To do a dry-run, "check" with out changing files:

```shell
vendor/bin/php-cs-fixer check app
# This is an alias for "fix --dry-run"
```

- To display a diff of changes:

```shell
vendor/bin/php-cs-fixer check --diff
```

- To list files PHP CS Fixer will target:

```shell
vendor/bin/php-cs-fixer list-files
```

### Docker

To can the `app` folder using version `3` with PHP `8.3`:

```shell
docker run -v $(pwd):/code ghcr.io/php-cs-fixer/php-cs-fixer:${FIXER_VERSION:-3-php8.3} fix app
```

`$FIXER_VERSION` used in example above is an identifier of a release you want to use.
The format is: `<php-cs-fixer-version>-php<php-version>`. For example:

- `3.47.0-php7.4`
- `3.47-php8.0`
- `3-php8.3`

## Configuration

The `PhpCsFixer\Finder` class uses the following default config ...

- Filters for `*.php`.
- Ignores `__DIR__ . "/vendor"` dir.
- Ignores `hidden` paths (ones starting with a dot).
- Ignores VCS paths (e.g. .git).

Without a configuration file, PHP CS Fixer defaults to `@PSR12` rule set.

1. Create a `.php-cs-fixer.dist.php` in the project root.

    ```php
    <?php

    $finder = (new PhpCsFixer\Finder())
        ->in(__DIR__);

    return (new PhpCsFixer\Config())
        ->setRules([
            // @see https://mlocati.github.io/php-cs-fixer-configurator
            // Newest PER-CS standard
            '@PER-CS' => true,
        ])
        ->setFinder($finder);
    ```

2. Add required rules, targets.
3. Add `.php-cs-fixer.cache` to `.gitignore`.

Optionally, uncomment and update `'@PHP82Migration'` to target the same version as your project's PHP.

@see <https://cs.symfony.com/doc/config.html>
@see [Symfony\Finder](https://symfony.com/doc/current/components/finder.html#location)

### Optional composer helper

Add the following helper scripts to `composer.json` to simplify running:

```json
  "scripts": {
      "cs-fix": "php vendor/bin/php-cs-fixer fix -v",
      "cs-diff": "php vendor/bin/php-cs-fixer check  -v --diff"
  },
```

## Rules

- [List of Available Rules](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst)
- [Rule sets](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst)

Define rule sets, rules and overrides in a configuration file, `.php-cs-fixer.dist.php`

```php
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
```

To override specific rules in a rule set, ensure that override is *below* the set.

### Describing rules

Use the following command to give a brief description of a specific rule:

```shell
php-cs-fixer describe no_short_bool_cast
```

### Risky Rules

PHP-CS-Fixer includes the concept of `risky` rules.
These rules potential break or cause intended behavior.

For Example. [date_time_create_from_format_call](https://mlocati.github.io/php-cs-fixer-configurator/#version:3.56|fixer:date_time_create_from_format_call)

<!-- textlint-disable write-good -->
> Consider this code: `DateTime::createFromFormat('Y-m-d', '2022-02-11')`.
What value will be returned? `2022-02-11 00:00:00.0`?
No, actual return value has `H:i:s` section like `2022-02-11 16:55:37.0`.
Change `Y-m-d` to `!Y-m-d`, return value will be `2022-02-11 00:00:00.0`.
Adding `!` to format string will make return value more intuitive.
<!-- textlint-enable write-good -->

### YOUR rules

1. Generate a configuration file with the desired rule baseline.
1. Run `php-cs-fixer check -v` to test files and display the failing rules by name (`concat_space`).

```shell
$ php vendor/bin/php-cs-fixer check -v app/Console/Kernel.php
 0/1 [░░░░░░░░░░░░░░░░░░░░░░░░░░░░]   0%
 1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

   1) app/Console/Kernel.php (concat_space)
```

1. Disable rule in `.php-cs-fixer.dist.php`

    ```php
        ->setRules([
            // @see https://mlocati.github.io/php-cs-fixer-configurator
            '@PER-CS' => true,
            'concat_space' => true,
    ```

## Migration helpers

Some rules update code to take advantage of new PHP methods or styling.
For example:

- [@PHP82Migration](https://cs.symfony.com/doc/ruleSets/PHP82Migration.html)
- PHP 8.1 -> 8.2 (`simple_to_complex_string_variable`)

```diff
<?php
$name = 'World';
-echo "Hello ${name}!";
+echo "Hello {$name}!";
```
