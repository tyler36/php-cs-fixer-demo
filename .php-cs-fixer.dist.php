<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__);

return (new Config())
    ->setRules([
        // @see https://mlocati.github.io/php-cs-fixer-configurator
        '@PER-CS' => true,
        '@PhpCsFixer' => true,
        '@PHP83Migration' => true,
        'concat_space' => ['spacing' => 'one'],
        'multiline_whitespace_before_semicolons' => false,
        'no_short_bool_cast' => true,
        'simplified_null_return' => true,
        // Risky, these rules require setRiskyAllowed(true)
        '@PhpCsFixer:risky' => true,
        'date_time_create_from_format_call' => true,
        'mb_str_functions' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
