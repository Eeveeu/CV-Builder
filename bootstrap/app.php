<?php

use Illuminate\Foundation\Application;

$basePath = dirname(__DIR__);

// Define helper functions if not exists
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return $GLOBALS['basePath'] . DIRECTORY_SEPARATOR . 'storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('public_path')) {
    function public_path($path = '') {
        return $GLOBALS['basePath'] . DIRECTORY_SEPARATOR . 'public' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

$GLOBALS['basePath'] = $basePath;

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: $basePath.'/routes/web.php',
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();
