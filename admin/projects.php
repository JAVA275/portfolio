<?php
// ============================================
// admin/projects.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();

// Suppression
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $db->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
        header('Location: /portfolio/admin/projects.php?deleted=1');
        exit;
    }
}

$projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projets — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Projets</span>
    <a href="/portfolio/admin/add-project.php" class="btn btn--primary btn--sm">+ Nouveau projet</a>
  </div>

  <div class="content">
    <h1 class="page-title">Gestion des projets</h1>
    <p class="page-sub"><?= count($projects) ?> projet(s) au total.</p>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert--success">✓ Projet supprimé.</div>
    <?php endif; ?>
    <?php if (isset($_GET['saved'])): ?>
    <div class="alert alert--success">✓ Projet enregistré.</div>
    <?php endif; ?>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Tags</th>
            <th>Vues</th>
            <th>Vedette</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($projects as $p): ?>
          <tr>
            <td class="text-muted"><?= $p['id'] ?></td>
            <td>
              <strong style="color:var(--text);"><?= htmlspecialchars($p['title']) ?></strong>
            </td>
            <td>
              <?php if ($p['tags']): ?>
              <div style="display:flex;flex-wrap:wrap;gap:.25rem;">
                <?php foreach (array_slice(explode(',', $p['tags']), 0, 3) as $t): ?>
                <span style="font-size:.65rem;padding:.15rem .5rem;background:var(--bg3);
                             border:1px solid var(--border);border-radius:2px;
                             font-family:'DM Mono',monospace;color:var(--muted);">
                  <?= htmlspecialchars(trim($t)) ?>
                </span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </td>
            <td class="text-muted"><?= $p['views'] ?></td>
            <td>
              <span class="badge <?= $p['featured'] ? 'badge--featured' : 'badge--normal' ?>">
                <?= $p['featured'] ? '★' : '—' ?>
              </span>
            </td>
            <td class="text-muted"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="/project-detail.php?id=<?= $p['id'] ?>"
                   class="btn btn--ghost btn--sm btn--icon" target="_blank" title="Voir">↗</a>
                <a href="/portfolio/admin/edit-project.php?id=<?= $p['id'] ?>"
                   class="btn btn--ghost btn--sm">Modifier</a>
                <a href="/portfolio/admin/projects.php?delete=<?= $p['id'] ?>"
                   class="btn btn--danger btn--sm"
                   data-confirm="Supprimer « <?= htmlspecialchars($p['title']) ?> » ?">
                  Suppr.
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<script src="/portfolio/assets/js/main.js"></script>
</body>
</html>
