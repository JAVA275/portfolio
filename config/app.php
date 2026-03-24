<?php
// ============================================
// config/app.php — Constantes globales
// ============================================
if (!defined('BASE_URL')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    // BASE_URL : sous-dossier si hébergé dans un sous-dossier (ex: /portfolio)
    // Laisser vide "" si le site est à la racine du domaine
    define('BASE_URL', rtrim($env['BASE_URL'] ?? '', '/'));
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
        return BASE_URL . ($path !== '' ? '/' . ltrim($path, '/') : '/');
    }
}
