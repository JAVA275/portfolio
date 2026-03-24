<div class="admin-header">
    <h1><i class="fas fa-edit"></i> Modifier le projet</h1>
    <a href="/admin" class="btn btn-secondary">← Retour</a>
</div>

<form action="/portfolio/admin/edit/<?php echo $project['id']; ?>" method="POST" class="admin-form">
    <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
    </div>
    
    <div class="form-group">
        <label>Description *</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($project['description']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Technologies * (séparées par des virgules)</label>
        <input type="text" name="technologies" value="<?php echo htmlspecialchars($project['technologies']); ?>" required>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>URL de l'image</label>
            <input type="url" name="image_url" value="<?php echo htmlspecialchars($project['image_url']); ?>">
        </div>
        
        <div class="form-group">
            <label>URL du projet</label>
            <input type="url" name="project_url" value="<?php echo htmlspecialchars($project['project_url']); ?>">
        </div>
        
        <div class="form-group">
            <label>URL GitHub</label>
            <input type="url" name="github_url" value="<?php echo htmlspecialchars($project['github_url']); ?>">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>