<?php
// ============================================
// admin/dashboard.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();

$stats = [
    'projects'  => $db->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
    'skills'    => $db->query("SELECT COUNT(*) FROM skills")->fetchColumn(),
    'messages'  => $db->query("SELECT COUNT(*) FROM messages")->fetchColumn(),
    'unread'    => $db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn(),
    'views'     => $db->query("SELECT COALESCE(SUM(views),0) FROM projects")->fetchColumn(),
];

$recentProjects = $db->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMessages = $db->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard — DevFolio Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <span class="topbar__title">Tableau de bord</span>
    <div class="topbar__actions">
      <a href="/admin/add-project.php" class="btn btn--primary btn--sm">+ Nouveau projet</a>
      <div class="topbar__user">
        <div class="topbar__avatar"><?= strtoupper(substr($_SESSION['admin_username'], 0, 1)) ?></div>
        <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
      </div>
    </div>
  </div>

  <div class="content">
    <h1 class="page-title">Tableau de bord</h1>
    <p class="page-sub">Bienvenue ! Voici un aperçu de votre portfolio.</p>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card stat-card--projects">
        <div class="stat-card__icon">◈</div>
        <div class="stat-card__value"><?= $stats['projects'] ?></div>
        <div class="stat-card__label">Projets</div>
      </div>
      <div class="stat-card stat-card--skills">
        <div class="stat-card__icon">⚡</div>
        <div class="stat-card__value"><?= $stats['skills'] ?></div>
        <div class="stat-card__label">Compétences</div>
      </div>
      <div class="stat-card stat-card--messages">
        <div class="stat-card__icon">✉</div>
        <div class="stat-card__value"><?= $stats['messages'] ?></div>
        <div class="stat-card__label">Messages <?php if($stats['unread']>0): ?><span class="sidebar__badge"><?=$stats['unread']?></span><?php endif;?></div>
      </div>
      <div class="stat-card stat-card--views">
        <div class="stat-card__icon">👁</div>
        <div class="stat-card__value"><?= $stats['views'] ?></div>
        <div class="stat-card__label">Vues totales</div>
      </div>
    </div>

    <!-- Tables row -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

      <!-- Projets récents -->
      <div class="table-wrapper">
        <div class="table-header">
          <span class="table-header__title">Projets récents</span>
          <a href="/admin/projects.php" class="btn btn--ghost btn--sm">Voir tout</a>
        </div>
        <table>
          <thead>
            <tr>
              <th>Titre</th>
              <th>Vues</th>
              <th>Vedette</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentProjects as $p): ?>
            <tr>
              <td>
                <a href="/admin/edit-project.php?id=<?= $p['id'] ?>"
                   style="color:var(--text);font-weight:500;">
                  <?= htmlspecialchars($p['title']) ?>
                </a>
              </td>
              <td class="text-muted"><?= $p['views'] ?></td>
              <td>
                <span class="badge <?= $p['featured'] ? 'badge--featured' : 'badge--normal' ?>">
                  <?= $p['featured'] ? '★ Vedette' : 'Normal' ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Messages récents -->
      <div class="table-wrapper">
        <div class="table-header">
          <span class="table-header__title">Messages récents</span>
          <a href="/admin/messages.php" class="btn btn--ghost btn--sm">Voir tout</a>
        </div>
        <table>
          <thead>
            <tr>
              <th>De</th>
              <th>Sujet</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentMessages as $m): ?>
            <tr>
              <td>
                <a href="/admin/message-view.php?id=<?= $m['id'] ?>"
                   style="color:var(--text);font-weight:500;">
                  <?= htmlspecialchars($m['name']) ?>
                </a>
              </td>
              <td class="text-muted"><?= htmlspecialchars(substr($m['subject'] ?: $m['message'], 0, 30)) ?>…</td>
              <td>
                <span class="badge <?= $m['is_read'] ? 'badge--read' : 'badge--unread' ?>">
                  <?= $m['is_read'] ? 'Lu' : 'Nouveau' ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div><!-- /grid -->

    <!-- Quick actions -->
    <div style="margin-top:1.5rem;background:var(--surface);border:1px solid var(--border);
                border-radius:8px;padding:1.5rem;">
      <p style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;
                color:var(--muted);margin-bottom:1rem;font-family:'DM Mono',monospace;">
        Actions rapides
      </p>
      <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
        <a href="/admin/add-project.php"  class="btn btn--primary btn--sm">+ Ajouter un projet</a>
        <a href="/admin/skills.php"       class="btn btn--ghost btn--sm">⚡ Gérer les compétences</a>
        <a href="/admin/profile.php"      class="btn btn--ghost btn--sm">👤 Modifier le profil</a>
        <a href="/admin/messages.php"     class="btn btn--ghost btn--sm">✉ Voir les messages</a>
        <a href="/portfolio/" target="_blank"       class="btn btn--ghost btn--sm">↗ Voir le site</a>
      </div>
    </div>

  </div>
</main>

</body>
</html>
