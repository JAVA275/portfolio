<?php require_once dirname(__DIR__, 2) . '/config/app.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rakiel Samuel — Développeur PHP Full Stack</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <nav>
        <div class="container">
            <div class="nav-brand">
                <a href="<?= base_url() ?>">MonPortfolio</a>
            </div>
            <ul class="nav-menu">
                <li><a href="<?= base_url() ?>">Accueil</a></li>
                <li><a href="<?= base_url('projects') ?>">Projets</a></li>
                <li><a href="<?= base_url('contact') ?>">Contact</a></li>
            </ul>
        </div>
    </nav>
    <main>