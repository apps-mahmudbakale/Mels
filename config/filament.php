<?php

return [
    'brand_name' => 'Millenial Circuit',
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DRIVER', 'public'),
    'middleware' => [
        'auth',
        'verified',
    ],
    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [
            \App\Filament\Pages\Dashboard::class,
        ],
    ],
    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
        'register' => [],
    ],
    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [],
    ],
];
