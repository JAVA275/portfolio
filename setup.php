<?php
// ============================================
// setup.php — Création du compte admin
// SUPPRIMER CE FICHIER APRÈS UTILISATION !
// ============================================
require_once __DIR__ . '/config/db.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? 'admin');
    $password = trim($_POST['password'] ?? '');

    if ($password) {
        $db = getDB();

        // Créer les tables si elles n'existent pas
        $db->exec("CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS projects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            technologies VARCHAR(500) NOT NULL,
            image_url VARCHAR(500),
            project_url VARCHAR(500),
            github_url VARCHAR(500),
            featured TINYINT(1) DEFAULT 0,
            views INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS skills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            category VARCHAR(100) NOT NULL,
            level INT DEFAULT 80,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(255),
            message TEXT NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)
                              ON DUPLICATE KEY UPDATE password = ?");
        $stmt->execute([$username, $hash, $hash]);

        $message = "✅ Admin créé ! Identifiant : <strong>$username</strong> — Mot de passe : <strong>" . htmlspecialchars($password) . "</strong>";
        $success = true;
    } else {
        $message = "❌ Veuillez entrer un mot de passe.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Setup Admin</title>
  <style>
    body { font-family: sans-serif; background: #0f172a; color: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
    .box { background: #1e293b; border-radius: 16px; padding: 2rem; width: 100%; max-width: 400px; border: 1px solid #334155; }
    h1 { margin-bottom: 1.5rem; color: #6366f1; }
    label { display: block; margin-bottom: .4rem; font-weight: 600; font-size: .875rem; }
    input { width: 100%; padding: 10px 14px; background: #0f172a; border: 1.5px solid #334155; border-radius: 8px; color: #f1f5f9; font-size: 1rem; margin-bottom: 1rem; box-sizing: border-box; }
    button { width: 100%; padding: 12px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; }
    .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; background: rgba(16,185,129,.15); border-left: 4px solid #10b981; color: #10b981; }
    .alert.err { background: rgba(239,68,68,.15); border-color: #ef4444; color: #ef4444; }
    .warn { margin-top: 1rem; font-size: .75rem; color: #f59e0b; text-align: center; }
    a { color: #6366f1; }
  </style>
</head>
<body>
<div class="box">
  <h1>⚙️ Setup Admin</h1>

  <?php if ($message): ?>
  <div class="alert <?= $success ? '' : 'err' ?>"><?= $message ?></div>
  <?php if ($success): ?>
  <p style="text-align:center;margin-bottom:1rem;">
    <a href="/portfolio/admin/login.php">→ Aller à la connexion</a>
  </p>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (!$success): ?>
  <form method="POST">
    <label>Identifiant admin</label>
    <input type="text" name="username" value="admin" required>
    <label>Mot de passe</label>
    <input type="text" name="password" placeholder="Choisir un mot de passe" required>
    <button type="submit">Créer le compte admin</button>
  </form>
  <?php endif; ?>

  <p class="warn">⚠️ Supprimer ce fichier après utilisation !</p>
</div>
</body>
</html>
