<?php
// ============================================
// config/db.php — Helper PDO + auth admin
// ============================================

$_env = parse_ini_file(__DIR__ . '/../.env');

define('DB_HOST', $_env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_env['DB_NAME'] ?? 'portfolio_db');
define('DB_USER', $_env['DB_USER'] ?? 'root');
define('DB_PASS', $_env['DB_PASS'] ?? '');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
    return $pdo;
}

function isAdmin(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_logged_in']);
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: /portfolio/admin/login.php');
        exit;
    }
}
