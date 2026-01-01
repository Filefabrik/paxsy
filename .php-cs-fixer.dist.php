<?php declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

return new PhpCsFixer\Config()->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache')
                              ->setRiskyAllowed(true)
                              ->setIndent('    ')
                              ->setLineEnding("\n")
                              ->setRules([
                                  '@PSR12'                       => true,
                                  'concat_space'                 => false,
                                  'blank_line_after_opening_tag' => false,
                                  'function_declaration'         => [
                                      'closure_function_spacing' => 'none',
                                      'closure_fn_spacing'       => 'none',
                                  ],

                              ])
                              ->setFinder(
                                  PhpCsFixer\Finder::create()
                                                   ->exclude('storage')
                                                   ->exclude('.circleci')
                                                   ->exclude('__stuff')
                                                   ->exclude('.coverage')
                                                   ->exclude('.profile')
                                                   ->exclude('.php-cs-fixer.cache')
                                                   ->exclude('.phpunit.cache')
                                                   ->exclude('bin')
                                                   ->exclude('node_modules')
                                                   ->exclude('vendor')
                                                   ->exclude('stubs')
                                                   ->exclude('bootstrap')
                                                   ->notPath('.phpstorm.meta.php')
                                                   ->notPath('_ide_helper.php')
                                                   ->notPath('artisan')
                                                   ->in(__DIR__),
                              )
;
