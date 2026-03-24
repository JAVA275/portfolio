<?php
// ============================================
// admin/add-project.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $github      = trim($_POST['github_link'] ?? '');
    $live        = trim($_POST['live_link']   ?? '');
    $tags        = trim($_POST['tags']        ?? '');
    $featured    = isset($_POST['featured']) ? 1 : 0;
    $imagePath   = null;

    if (!$title || !$description) {
        $error = 'Le titre et la description sont obligatoires.';
    } else {
        // Upload image
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (!in_array($ext, $allowed)) {
                $error = 'Format d\'image non supporté.';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = 'L\'image ne doit pas dépasser 5 Mo.';
            } else {
                $uploadDir = __DIR__ . '/../assets/images/projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $filename  = uniqid('proj_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = '/assets/images/projects/' . $filename;
                } else {
                    $error = 'Erreur lors de l\'upload de l\'image.';
                }
            }
        }

        if (!$error) {
            $stmt = $db->prepare("
                INSERT INTO projects (title, description, image, github_link, live_link, tags, featured)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $description, $imagePath, $github, $live, $tags, $featured]);
            header('Location: /portfolio/admin/projects.php?saved=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter un projet — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Ajouter un projet</span>
    <a href="/portfolio/admin/projects.php" class="btn btn--ghost btn--sm">← Retour</a>
  </div>

  <div class="content">
    <h1 class="page-title">Nouveau projet</h1>
    <p class="page-sub">Remplissez les informations du projet.</p>

    <?php if ($error): ?>
    <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Titre *</label>
          <input type="text" name="title" class="form-control"
                 placeholder="Nom du projet" required
                 value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Tags (séparés par virgule)</label>
          <input type="text" name="tags" class="form-control"
                 placeholder="PHP, MySQL, JavaScript"
                 value="<?= htmlspecialchars($_POST['tags'] ?? '') ?>">
          <p class="form-hint">Ex : PHP,MySQL,React</p>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Description *</label>
        <textarea name="description" class="form-control" rows="5"
                  placeholder="Décrivez le projet en détail…" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Lien GitHub</label>
          <input type="url" name="github_link" class="form-control"
                 placeholder="https://github.com/…"
                 value="<?= htmlspecialchars($_POST['github_link'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Lien en ligne (Live)</label>
          <input type="url" name="live_link" class="form-control"
                 placeholder="https://monprojet.com"
                 value="<?= htmlspecialchars($_POST['live_link'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Image du projet</label>
        <div class="image-preview" id="imagePreview">
          <span class="image-preview__placeholder">Aperçu de l'image</span>
        </div>
        <input type="file" name="image" id="imageInput"
               accept="image/jpeg,image/png,image/webp,image/gif"
               class="form-control" style="padding:.5rem;">
        <p class="form-hint">JPG, PNG, WebP. Max 5 Mo.</p>
      </div>

      <div class="form-group" style="display:flex;align-items:center;gap:.75rem;">
        <input type="checkbox" name="featured" id="featured"
               value="1" <?= isset($_POST['featured']) ? 'checked' : '' ?>
               style="width:16px;height:16px;accent-color:var(--accent);cursor:pointer;">
        <label for="featured" style="font-size:.875rem;cursor:pointer;">
          Mettre en vedette sur la page d'accueil
        </label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">
          ✓ Enregistrer le projet
        </button>
        <a href="/portfolio/admin/projects.php" class="btn btn--ghost">Annuler</a>
      </div>

    </form>
  </div>
</main>

<script>
// Aperçu image
document.getElementById('imageInput').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu">`;
  };
  reader.readAsDataURL(file);
});
</script>
</body>
</html>
