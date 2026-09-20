<?php

/*
 * Punto de entrada para Vercel (runtime vercel-php).
 * El sistema de archivos es de solo lectura salvo /tmp, así que el almacenamiento
 * y los cachés de Laravel se redirigen allí (ver variables de entorno del proyecto).
 * Vercel no ofrece disco persistente: para producción real use una base de datos
 * administrada (DB_CONNECTION=mysql/pgsql) y un disco de archivos externo.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs', 'app/public'] as $directory) {
    if (! is_dir('/tmp/storage/'.$directory)) {
        mkdir('/tmp/storage/'.$directory, 0777, true);
    }
}

// Base de datos de demostración (SQLite) con el contenido de ejemplo: se copia a /tmp al arrancar.
if (! is_file('/tmp/database.sqlite') && is_file(__DIR__.'/../database/deploy.sqlite')) {
    copy(__DIR__.'/../database/deploy.sqlite', '/tmp/database.sqlite');
}

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
