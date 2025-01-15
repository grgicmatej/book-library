<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude('Migrations')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules(
        [
            '@DoctrineAnnotation' => true,
            '@PHP73Migration' => true,
            '@PHP71Migration:risky' => true,
            '@PHP70Migration:risky' => true,
            '@PHPUnit75Migration:risky' => true,
            '@Symfony' => true,
            '@Symfony:risky' => true,
            '@PSR2' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@PHP70Migration' => true,
            '@PHP71Migration' => true,
            'declare_strict_types' => true,
            'random_api_migration' => true,
            'dir_constant' => true,
            'modernize_types_casting' => true,
            'php_unit_construct' => true,
            'php_unit_attributes' => true,
            'php_unit_size_class' => false,
            'php_unit_test_class_requires_covers' => false,
            'psr_autoloading' => true,
            'final_internal_class' => true,
            'php_unit_strict' => [
                'assertions' => [
                    'assertAttributeEquals',
                    'assertAttributeNotEquals',
                    'assertEquals', // This will replace all assertEquals with assertSame that can affect array comparisons
                    'assertNotEquals',
                ],
            ],
            'php_unit_test_case_static_method_calls' => true,
            'strict_comparison' => true,
            'strict_param' => true,
            'date_time_immutable' => false,
            'general_phpdoc_annotation_remove' => true,
            'mb_str_functions' => true,
            'multiline_whitespace_before_semicolons' => false,
            'no_php4_constructor' => true,
            'no_superfluous_phpdoc_tags' => [
                'allow_mixed' => true,
            ],
            'not_operator_with_space' => false,
            'not_operator_with_successor_space' => false,
            'ordered_interfaces' => true,
            'phpdoc_to_return_type' => true,
            'static_lambda' => true,
            'php_unit_internal_class' => false,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'return_assignment' => false,
            'phpdoc_line_span' => [
                'const' => 'single',
                'method' => 'single',
                'property' => 'single',
            ],
            'void_return' => false,
            'phpdoc_to_comment' => false,
            'single_line_comment_style' => false,
            'array_syntax' => ['syntax' => 'short'],
            'single_blank_line_at_eof' => true,
            'blank_line_after_namespace' => true,
            'blank_line_after_opening_tag' => true,
            'single_space_after_construct' => true,
            'single_quote' => true,
            'ternary_operator_spaces' => true,
            'trim_array_spaces' => true,
            'ternary_to_null_coalescing' => true,
            'types_spaces' => true,
            'whitespace_after_comma_in_array' => true,
            'cast_spaces' => true,
            'blank_line_before_statement' => true,
            'braces' => true,
            'class_attributes_separation' => [
                'elements' => [
                    'method' => 'one',
                    'property' => 'one',
                    'trait_import' => 'one',
                ]
            ],
            'class_definition' => true,
        ]
    )
    ->setFinder($finder);
