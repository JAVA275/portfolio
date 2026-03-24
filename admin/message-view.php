<?php
// admin/message-view.php
session_start();
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$db = getDB();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: /portfolio/admin/messages.php'); exit; }

$stmt = $db->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$msg = $stmt->fetch();
if (!$msg) { header('Location: /portfolio/admin/messages.php'); exit; }

// Marquer comme lu
$db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?")->execute([$id]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Message — Admin</title>
  <link rel="stylesheet" href="/portfolio/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="main">
  <div class="topbar">
    <span class="topbar__title">Message de <?= htmlspecialchars($msg['name']) ?></span>
    <a href="/admin/messages.php" class="btn btn--ghost btn--sm">← Retour</a>
  </div>
  <div class="content">
    <h1 class="page-title">Message reçu</h1>
    <div class="message-card">
      <div class="message-meta">
        <div><span>De : </span><strong><?= htmlspecialchars($msg['name']) ?></strong></div>
        <div><span>Email : </span><strong><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></strong></div>
        <div><span>Date : </span><strong><?= date('d/m/Y à H:i', strtotime($msg['created_at'])) ?></strong></div>
      </div>
      <?php if ($msg['subject']): ?>
      <p style="font-size:.8rem;font-family:'DM Mono',monospace;color:var(--muted);margin-bottom:1rem;">
        Sujet : <?= htmlspecialchars($msg['subject']) ?>
      </p>
      <?php endif; ?>
      <div class="message-body"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
      <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);display:flex;gap:.75rem;">
        <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject'] ?: 'Votre message') ?>"
           class="btn btn--primary btn--sm">↩ Répondre par email</a>
        <a href="/admin/messages.php?delete=<?= $msg['id'] ?>"
           class="btn btn--danger btn--sm" data-confirm="Supprimer ce message ?">Supprimer</a>
      </div>
    </div>
  </div>
</main>
<script src="/portfolio/assets/js/main.js"></script>
</body>
</html>
