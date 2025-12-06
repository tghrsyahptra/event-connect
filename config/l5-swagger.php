<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Event Management API Documentation',
            ],

            'routes' => [
                /*
                 * Route untuk mengakses parsed swagger annotations.
                 */
                'api' => 'api/documentation',
            ],

            'paths' => [
                /*
                 * Path absolut ke direktori yang berisi file dokumentasi Swagger.
                 * Folder yang akan di-scan untuk annotation.
                 */
                'docs' => storage_path('api-docs'),

                /*
                 * File yang akan di-generate
                 */
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',

                /*
                 * Set path untuk menyimpan format json dan yaml
                 */
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),

                /*
                 * Path absolut ke direktori yang berisi view untuk dokumentasi.
                 */
                'views' => base_path('resources/views/vendor/l5-swagger'),

                /*
                 * Path ke base controller
                 */
                'base' => env('L5_SWAGGER_BASE_PATH', null),

                /*
                 * Exclude path dari scan
                 */
                'excludes' => [],

                /*
                 * Scan path - lokasi file yang akan di-scan untuk annotation
                 */
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                    base_path('app/Http/Controllers/DocumentationApi'),
                    base_path('app/Models'),
                ],
            ],

            'scanOptions' => [
                /**
                 * analyser: defaults to \OpenApi\StaticAnalyser
                 */
                'analyser' => null,

                /**
                 * analysis: defaults to a new \OpenApi\Analysis
                 */
                'analysis' => null,

                /**
                 * processors: defaults to the registered processors
                 */
                'processors' => [
                    // Add custom processors here
                ],

                /**
                 * pattern: defaults to null
                 */
                'pattern' => null,

                /*
                 * Absolute path to directory yang berisi Swagger annotation
                 */
                'exclude' => [],
            ],

            /*
             * API security definitions. Akan di-generate ke OpenAPI output.
             */
            'securityDefinitions' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'Masukkan JWT token dengan format: Bearer {token}',
                    ],
                ],
                'security' => [
                    [
                        'bearerAuth' => [],
                    ],
                ],
            ],

            /*
             * Set ini ke true untuk menggunakan absolute path dalam dokumentasi
             */
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

            /*
             * Edit untuk mengubah API Docs default URL
             */
            'proxy' => false,

            /*
             * Edit untuk mengubah path plugin swagger ui
             */
            'additional_config_url' => null,

            /*
             * Edit untuk set validator URL
             */
            'validator_url' => null,

            /*
             * Swagger UI configuration
             */
            'ui' => [
                'display' => [
                    'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
                    'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                    'filter' => env('L5_SWAGGER_UI_FILTERS', true),
                ],
                'authorization' => [
                    'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                ],
            ],

            /*
             * Constants untuk digunakan dalam annotation
             */
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
            ],
        ],
    ],
];