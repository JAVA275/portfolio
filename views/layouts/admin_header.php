<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <div class="nav-brand">
                <a href="/admin">⚡ AdminPanel</a>
            </div>
            <ul class="nav-menu">
                <li><a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="/admin/create"><i class="fas fa-plus-circle"></i> Nouveau projet</a></li>
                <li><a href="/"><i class="fas fa-eye"></i> Voir le site</a></li>
                <li><a href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>