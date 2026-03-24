<?php
// admin/partials/sidebar.php
if (session_status() === PHP_SESSION_NONE) session_start();
$db = getDB();
$unread = $db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar__logo">
    <span style='display:flex;align-items:center;gap:.5rem;'><img src='/portfolio/assets/images/logo-java.svg' alt='Java' style='width:28px;height:28px;border-radius:50%;background:#111113;'> <span>Java</span></span>
  </div>

  <nav class="sidebar__nav">
    <p class="sidebar__section">Principal</p>

    <a href="/portfolio/admin/dashboard.php"
       class="sidebar__link <?= $current === 'dashboard.php' ? 'active' : '' ?>">
      <span class="icon">⊞</span> Tableau de bord
    </a>

    <p class="sidebar__section">Contenu</p>

    <a href="/portfolio/admin/projects.php"
       class="sidebar__link <?= in_array($current, ['projects.php','add-project.php','edit-project.php']) ? 'active' : '' ?>">
      <span class="icon">◈</span> Projets
    </a>

    <a href="/portfolio/admin/skills.php"
       class="sidebar__link <?= $current === 'skills.php' ? 'active' : '' ?>">
      <span class="icon">⚡</span> Compétences
    </a>

    <a href="/portfolio/admin/profile.php"
       class="sidebar__link <?= $current === 'profile.php' ? 'active' : '' ?>">
      <span class="icon">👤</span> Profil
    </a>

    <p class="sidebar__section">Communication</p>

    <a href="/portfolio/admin/messages.php"
       class="sidebar__link <?= in_array($current, ['messages.php','message-view.php']) ? 'active' : '' ?>">
      <span class="icon">✉</span> Messages
      <?php if ($unread > 0): ?>
      <span class="sidebar__badge"><?= $unread ?></span>
      <?php endif; ?>
    </a>

    <p class="sidebar__section">API</p>

    <a href="/api/projects" target="_blank" class="sidebar__link">
      <span class="icon">⊡</span> API Projets ↗
    </a>

    <p class="sidebar__section">Compte</p>

    <a href="/portfolio/" target="_blank" class="sidebar__link">
      <span class="icon">↗</span> Voir le site
    </a>

    <a href="/portfolio/admin/logout.php" class="sidebar__link"
       style="color:var(--danger);">
      <span class="icon">⊗</span> Déconnexion
    </a>
  </nav>

  <div class="sidebar__footer">
    Connecté : <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></strong>
  </div>
</aside>
