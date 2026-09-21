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
// Si la copia de una instancia reutilizada está dañada o bloqueada, se restaura desde el original.
$seed = __DIR__.'/../database/deploy.sqlite';
$database = '/tmp/database.sqlite';

$isHealthy = static function (string $path): bool {
    try {
        $pdo = new PDO('sqlite:'.$path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]);
        $pdo->query('SELECT 1 FROM settings LIMIT 1')->fetchColumn();

        return $pdo->query('PRAGMA quick_check')->fetchColumn() === 'ok';
    } catch (Throwable $exception) {
        error_log('[despacho] base SQLite no disponible: '.$exception->getMessage());

        return false;
    }
};

if (is_file($seed) && (! is_file($database) || ! $isHealthy($database))) {
    foreach (['', '-wal', '-shm', '-journal'] as $suffix) {
        @unlink($database.$suffix);
    }
    copy($seed, $database);
    error_log('[despacho] base SQLite restaurada desde deploy.sqlite');
}

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
