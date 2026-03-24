<div class="admin-header">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <a href="/admin/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau projet
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-number"><?php echo count($projects); ?></div>
        <div class="stat-label">Projets totaux</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-number"><?php echo count($projects); ?></div>
        <div class="stat-label">En ligne</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👁️</div>
        <div class="stat-number">1,234</div>
        <div class="stat-label">Vues totales</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💬</div>
        <div class="stat-number">0</div>
        <div class="stat-label">Messages</div>
    </div>
</div>

<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Technologies</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($projects as $project): ?>
            <tr>
                <td>#<?php echo $project['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($project['title']); ?></strong></td>
                <td><?php echo htmlspecialchars(substr($project['technologies'], 0, 30)); ?>...</td>
                <td><?php echo date('d/m/Y', strtotime($project['created_at'])); ?></td>
                <td><span class="status-badge status-published">Publié</span></td>
                <td class="actions">
                    <a href="/admin/edit/<?php echo $project['id']; ?>" class="btn-edit">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="/admin/delete/<?php echo $project['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Supprimer ce projet ?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>