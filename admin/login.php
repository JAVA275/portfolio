<?php
// ============================================
// admin/login.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';

if (isAdmin()) {
    header('Location: /portfolio/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username']  = $admin['username'];
            header('Location: /portfolio/admin/dashboard.php');
            exit;
        }
    }
    $error = 'Identifiants incorrects.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Connexion</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<div class="login-page">
  <div class="login-box">
    <div class="login-box__logo">
      <img src='/portfolio/assets/images/logo-java.svg' alt='Java' style='width:48px;height:48px;border-radius:50%;margin-bottom:.5rem;'><br>Java Admin
    </div>

    <div class="login-box__card">
      <h2>Connexion</h2>
      <p class="sub">Accès réservé à l'administrateur</p>

      <?php if ($error): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="/portfolio/admin/login.php">
        <div class="form-group">
          <label class="form-label">Identifiant</label>
          <input type="text" name="username" class="form-control"
                 placeholder="admin" autofocus required
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <div style="position:relative;">
            <input type="password" name="password" id="passwordInput" class="form-control"
                   placeholder="••••••••" required style="padding-right:48px;">
            <button type="button" onclick="togglePassword()" id="toggleBtn"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:var(--muted);
                           font-size:1.1rem;padding:0;line-height:1;">
              👁
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn--primary"
                style="width:100%;justify-content:center;margin-top:.5rem;">
          Se connecter →
        </button>
      </form>

      <p style="text-align:center;margin-top:1.5rem;font-size:.75rem;color:var(--muted);">
        <a href="/portfolio/">← Retour au portfolio</a>
      </p>
    </div>

    <p style="text-align:center;margin-top:1rem;font-size:.7rem;color:var(--muted);">
      Par défaut : admin / Admin1234!
    </p>
  </div>
</div>

<script>
function togglePassword() {
    var input = document.getElementById('passwordInput');
    var btn = document.getElementById('toggleBtn');
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}
</script>
</body>
</html>
