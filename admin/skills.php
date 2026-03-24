<?php
// ============================================
// admin/skills.php — Gestion des compétences
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();
$error = $success = '';

// Suppression
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM skills WHERE id = ?")->execute([intval($_GET['delete'])]);
    header('Location: /portfolio/admin/skills.php?deleted=1'); exit;
}

// Ajout / Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = intval($_POST['id'] ?? 0);
    $name     = trim($_POST['name']     ?? '');
    $level    = intval($_POST['level']  ?? 0);
    $category = trim($_POST['category'] ?? 'General');
    $order    = intval($_POST['sort_order'] ?? 0);

    if (!$name) {
        $error = 'Le nom est obligatoire.';
    } else {
        $level = max(0, min(100, $level));
        if ($id) {
            $db->prepare("UPDATE skills SET name=?,level=?,category=?,sort_order=? WHERE id=?")
               ->execute([$name, $level, $category, $order, $id]);
        } else {
            $db->prepare("INSERT INTO skills (name,level,category,sort_order) VALUES(?,?,?,?)")
               ->execute([$name, $level, $category, $order]);
        }
        header('Location: /portfolio/admin/skills.php?saved=1'); exit;
    }
}

// Édition
$editSkill = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $editSkill = $stmt->fetch();
}

$skills = $db->query("SELECT * FROM skills ORDER BY category, sort_order")->fetchAll();
$byCategory = [];
foreach ($skills as $s) $byCategory[$s['category']][] = $s;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Compétences — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Compétences</span>
  </div>

  <div class="content">
    <h1 class="page-title">Gestion des compétences</h1>
    <p class="page-sub"><?= count($skills) ?> compétence(s).</p>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert--success">✓ Compétence supprimée.</div>
    <?php elseif (isset($_GET['saved'])): ?>
    <div class="alert alert--success">✓ Compétence enregistrée.</div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.5rem;align-items:start;">

      <!-- Liste -->
      <div>
        <?php foreach ($byCategory as $cat => $catSkills): ?>
        <div style="margin-bottom:1.5rem;">
          <p style="font-size:.7rem;font-family:'DM Mono',monospace;letter-spacing:.15em;
                    text-transform:uppercase;color:var(--muted);margin-bottom:.75rem;">
            <?= htmlspecialchars($cat) ?>
          </p>
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Niveau</th>
                  <th>Ordre</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($catSkills as $s): ?>
                <tr>
                  <td style="font-weight:600;color:var(--text);"><?= htmlspecialchars($s['name']) ?></td>
                  <td>
                    <div style="display:flex;align-items:center;gap:.75rem;">
                      <div style="flex:1;height:4px;background:var(--border);border-radius:2px;max-width:80px;">
                        <div style="height:100%;width:<?=$s['level']?>%;
                                    background:linear-gradient(90deg,var(--accent2),var(--accent));
                                    border-radius:2px;"></div>
                      </div>
                      <span style="font-family:'DM Mono',monospace;font-size:.75rem;color:var(--accent);">
                        <?= $s['level'] ?>%
                      </span>
                    </div>
                  </td>
                  <td class="text-muted"><?= $s['sort_order'] ?></td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="/admin/skills.php?edit=<?= $s['id'] ?>"
                         class="btn btn--ghost btn--sm">Modifier</a>
                      <a href="/admin/skills.php?delete=<?= $s['id'] ?>"
                         class="btn btn--danger btn--sm"
                         data-confirm="Supprimer « <?= htmlspecialchars($s['name']) ?> » ?">
                        ✕
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Formulaire -->
      <div class="form-card" style="position:sticky;top:1rem;">
        <h3 style="font-size:1rem;margin-bottom:1.25rem;color:var(--text);">
          <?= $editSkill ? 'Modifier la compétence' : 'Ajouter une compétence' ?>
        </h3>

        <?php if ($error): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
          <?php if ($editSkill): ?>
          <input type="hidden" name="id" value="<?= $editSkill['id'] ?>">
          <?php endif; ?>

          <div class="form-group">
            <label class="form-label">Nom *</label>
            <input type="text" name="name" class="form-control" required
                   value="<?= htmlspecialchars($editSkill['name'] ?? '') ?>"
                   placeholder="PHP, React, Docker…">
          </div>

          <div class="form-group">
            <label class="form-label">Niveau : <span id="levelVal"><?= $editSkill['level'] ?? 80 ?></span>%</label>
            <div class="range-wrapper">
              <input type="range" name="level" id="levelRange"
                     min="0" max="100" value="<?= $editSkill['level'] ?? 80 ?>"
                     oninput="document.getElementById('levelVal').textContent=this.value">
              <span class="range-value" id="levelDisp"><?= $editSkill['level'] ?? 80 ?>%</span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Catégorie</label>
            <input type="text" name="category" class="form-control"
                   placeholder="Frontend, Backend, DevOps…"
                   value="<?= htmlspecialchars($editSkill['category'] ?? 'General') ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Ordre d'affichage</label>
            <input type="number" name="sort_order" class="form-control" min="0"
                   value="<?= $editSkill['sort_order'] ?? 0 ?>">
          </div>

          <div style="display:flex;gap:.5rem;margin-top:1rem;">
            <button type="submit" class="btn btn--primary btn--sm">
              <?= $editSkill ? '✓ Enregistrer' : '+ Ajouter' ?>
            </button>
            <?php if ($editSkill): ?>
            <a href="/admin/skills.php" class="btn btn--ghost btn--sm">Annuler</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

    </div>
  </div>
</main>

<script>
const range = document.getElementById('levelRange');
const disp  = document.getElementById('levelDisp');
range.addEventListener('input', () => disp.textContent = range.value + '%');
</script>
<script src="/portfolio/assets/js/main.js"></script>
</body>
</html>
