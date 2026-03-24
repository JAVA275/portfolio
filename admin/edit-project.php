<?php
// ============================================
// admin/edit-project.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: /portfolio/admin/projects.php'); exit; }

$stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();
if (!$project) { header('Location: /portfolio/admin/projects.php'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $github      = trim($_POST['github_link'] ?? '');
    $live        = trim($_POST['live_link']   ?? '');
    $tags        = trim($_POST['tags']        ?? '');
    $featured    = isset($_POST['featured']) ? 1 : 0;
    $imagePath   = $project['image'];

    if (!$title || !$description) {
        $error = 'Le titre et la description sont obligatoires.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (!in_array($ext, $allowed)) {
                $error = 'Format non supporté.';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = 'Image trop lourde (max 5 Mo).';
            } else {
                $uploadDir = __DIR__ . '/../assets/images/projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $filename = uniqid('proj_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    // Supprimer ancienne image
                    if ($imagePath && file_exists(__DIR__ . '/..' . $imagePath)) {
                        unlink(__DIR__ . '/..' . $imagePath);
                    }
                    $imagePath = '/assets/images/projects/' . $filename;
                } else {
                    $error = 'Erreur upload.';
                }
            }
        }

        if (!$error) {
            $stmt2 = $db->prepare("
                UPDATE projects
                SET title=?, description=?, image=?, github_link=?,
                    live_link=?, tags=?, featured=?
                WHERE id=?
            ");
            $stmt2->execute([$title, $description, $imagePath, $github, $live, $tags, $featured, $id]);
            header('Location: /portfolio/admin/projects.php?saved=1');
            exit;
        }
    }
    // Repeupler depuis POST
    $project = array_merge($project, $_POST, ['id' => $id, 'image' => $imagePath]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Modifier le projet</span>
    <div style="display:flex;gap:.5rem;">
      <a href="/project-detail.php?id=<?= $id ?>" target="_blank"
         class="btn btn--ghost btn--sm">↗ Voir</a>
      <a href="/portfolio/admin/projects.php" class="btn btn--ghost btn--sm">← Retour</a>
    </div>
  </div>

  <div class="content">
    <h1 class="page-title">Modifier : <?= htmlspecialchars($project['title']) ?></h1>
    <p class="page-sub">Modifiez les informations et enregistrez.</p>

    <?php if ($error): ?>
    <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Titre *</label>
          <input type="text" name="title" class="form-control" required
                 value="<?= htmlspecialchars($project['title']) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Tags</label>
          <input type="text" name="tags" class="form-control"
                 placeholder="PHP,MySQL,React"
                 value="<?= htmlspecialchars($project['tags'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description *</label>
        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($project['description']) ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Lien GitHub</label>
          <input type="url" name="github_link" class="form-control"
                 value="<?= htmlspecialchars($project['github_link'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Lien Live</label>
          <input type="url" name="live_link" class="form-control"
                 value="<?= htmlspecialchars($project['live_link'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Image du projet</label>
        <div class="image-preview" id="imagePreview">
          <?php if ($project['image']): ?>
          <img src="<?= htmlspecialchars($project['image']) ?>" alt="Image actuelle">
          <?php else: ?>
          <span class="image-preview__placeholder">Aucune image</span>
          <?php endif; ?>
        </div>
        <input type="file" name="image" id="imageInput"
               accept="image/jpeg,image/png,image/webp,image/gif"
               class="form-control" style="padding:.5rem;margin-top:.5rem;">
        <p class="form-hint">Laisser vide pour conserver l'image actuelle.</p>
      </div>

      <div class="form-group" style="display:flex;align-items:center;gap:.75rem;">
        <input type="checkbox" name="featured" id="featured" value="1"
               <?= $project['featured'] ? 'checked' : '' ?>
               style="width:16px;height:16px;accent-color:var(--accent);cursor:pointer;">
        <label for="featured" style="font-size:.875rem;cursor:pointer;">
          Mettre en vedette sur la page d'accueil
        </label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">✓ Enregistrer</button>
        <a href="/portfolio/admin/projects.php?delete=<?= $id ?>"
           class="btn btn--danger"
           data-confirm="Supprimer définitivement ce projet ?">
          Supprimer
        </a>
        <a href="/portfolio/admin/projects.php" class="btn btn--ghost">Annuler</a>
      </div>

    </form>
  </div>
</main>

<script>
document.getElementById('imageInput').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('imagePreview').innerHTML =
      `<img src="${e.target.result}" alt="Aperçu">`;
  };
  reader.readAsDataURL(file);
});
</script>
<script src="/portfolio/assets/js/main.js"></script>
</body>
</html>
