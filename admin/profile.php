<?php
// ============================================
// admin/profile.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();
$profile = $db->query("SELECT * FROM profile LIMIT 1")->fetch();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'title'     => trim($_POST['title']     ?? ''),
        'bio'       => trim($_POST['bio']        ?? ''),
        'email'     => trim($_POST['email']      ?? ''),
        'github'    => trim($_POST['github']     ?? ''),
        'linkedin'  => trim($_POST['linkedin']   ?? ''),
        'twitter'   => trim($_POST['twitter']    ?? ''),
        'location'  => trim($_POST['location']   ?? ''),
    ];

    // Upload avatar
    if (!empty($_FILES['avatar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $dir = __DIR__ . '/../assets/images/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $fname = 'avatar.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dir . $fname)) {
                $fields['avatar'] = '/assets/images/' . $fname;
            }
        }
    } elseif (isset($profile['avatar'])) {
        $fields['avatar'] = $profile['avatar'];
    }

    // Upload CV
    if (!empty($_FILES['cv']['name'])) {
        $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $dir = __DIR__ . '/../assets/';
            $fname = 'cv.pdf';
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $dir . $fname)) {
                $fields['cv_path'] = '/assets/cv.pdf';
            }
        }
    } elseif (isset($profile['cv_path'])) {
        $fields['cv_path'] = $profile['cv_path'];
    }

    $sql = "UPDATE profile SET " .
        implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields))) .
        " WHERE id = ?";
    $db->prepare($sql)->execute([...array_values($fields), $profile['id']]);

    $profile  = $db->query("SELECT * FROM profile LIMIT 1")->fetch();
    $success  = 'Profil mis à jour avec succès.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Profil</span>
    <a href="/portfolio/" target="_blank" class="btn btn--ghost btn--sm">↗ Voir le site</a>
  </div>

  <div class="content">
    <h1 class="page-title">Modifier mon profil</h1>
    <p class="page-sub">Ces informations apparaissent sur votre portfolio.</p>

    <?php if ($success): ?>
    <div class="alert alert--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card" style="max-width:800px;">

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label class="form-label">Nom complet</label>
          <input type="text" name="full_name" class="form-control"
                 value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Titre / Fonction</label>
          <input type="text" name="title" class="form-control"
                 placeholder="Développeur Full-Stack"
                 value="<?= htmlspecialchars($profile['title'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Biographie</label>
        <textarea name="bio" class="form-control" rows="4"
                  placeholder="Décrivez-vous en quelques phrases…"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label class="form-label">Email de contact</label>
          <input type="email" name="email" class="form-control"
                 value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Localisation</label>
          <input type="text" name="location" class="form-control"
                 placeholder="Paris, France"
                 value="<?= htmlspecialchars($profile['location'] ?? '') ?>">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label class="form-label">GitHub URL</label>
          <input type="url" name="github" class="form-control"
                 value="<?= htmlspecialchars($profile['github'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">LinkedIn URL</label>
          <input type="url" name="linkedin" class="form-control"
                 value="<?= htmlspecialchars($profile['linkedin'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Twitter URL</label>
          <input type="url" name="twitter" class="form-control"
                 value="<?= htmlspecialchars($profile['twitter'] ?? '') ?>">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="form-group">
          <label class="form-label">Photo de profil (avatar)</label>
          <?php if (!empty($profile['avatar'])): ?>
          <img src="<?= htmlspecialchars($profile['avatar']) ?>"
               style="width:60px;height:60px;border-radius:50%;object-fit:cover;
                      margin-bottom:.5rem;border:2px solid var(--border);">
          <?php endif; ?>
          <input type="file" name="avatar" class="form-control"
                 accept="image/jpeg,image/png,image/webp" style="padding:.5rem;">
          <p class="form-hint">JPG, PNG, WebP.</p>
        </div>
        <div class="form-group">
          <label class="form-label">CV (PDF)</label>
          <?php if (!empty($profile['cv_path'])): ?>
          <p class="form-hint" style="margin-bottom:.5rem;">
            ✓ <a href="<?= htmlspecialchars($profile['cv_path']) ?>" target="_blank">CV actuel</a>
          </p>
          <?php endif; ?>
          <input type="file" name="cv" class="form-control"
                 accept="application/pdf" style="padding:.5rem;">
          <p class="form-hint">Fichier PDF uniquement.</p>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">✓ Enregistrer le profil</button>
      </div>

    </form>
  </div>
</main>

</body>
</html>
