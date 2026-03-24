<?php
// ============================================
// admin/messages.php
// ============================================
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();

// Suppression
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM messages WHERE id = ?")->execute([intval($_GET['delete'])]);
    header('Location: /portfolio/admin/messages.php?deleted=1'); exit;
}

$messages = $db->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Messages — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="main">
  <div class="topbar">
    <span class="topbar__title">Messages reçus</span>
  </div>
  <div class="content">
    <h1 class="page-title">Boîte de réception</h1>
    <p class="page-sub"><?= count($messages) ?> message(s).</p>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert--success">✓ Message supprimé.</div>
    <?php endif; ?>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>De</th><th>Email</th><th>Sujet</th><th>Date</th><th>Statut</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messages as $m): ?>
          <tr>
            <td style="font-weight:600;color:var(--text);"><?= htmlspecialchars($m['name']) ?></td>
            <td class="text-muted"><?= htmlspecialchars($m['email']) ?></td>
            <td class="text-muted"><?= htmlspecialchars(substr($m['subject'] ?: '(aucun)', 0, 40)) ?></td>
            <td class="text-muted"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
            <td>
              <span class="badge <?= $m['is_read'] ? 'badge--read' : 'badge--unread' ?>">
                <?= $m['is_read'] ? 'Lu' : 'Nouveau' ?>
              </span>
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="/portfolio/admin/message-view.php?id=<?= $m['id'] ?>" class="btn btn--ghost btn--sm">Lire</a>
                <a href="/portfolio/admin/messages.php?delete=<?= $m['id'] ?>"
                   class="btn btn--danger btn--sm"
                   data-confirm="Supprimer ce message ?">✕</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($messages)): ?>
          <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--muted);">Aucun message.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<script src="/portfolio/assets/js/main.js"></script>
</body>
</html>
