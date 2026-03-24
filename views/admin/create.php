<div class="admin-header">
    <h1><i class="fas fa-plus-circle"></i> Ajouter un projet</h1>
    <a href="/admin" class="btn btn-secondary">← Retour</a>
</div>

<form action="/portfolio/admin/create" method="POST" class="admin-form">
    <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="title" required>
    </div>
    
    <div class="form-group">
        <label>Description *</label>
        <textarea name="description" rows="5" required></textarea>
    </div>
    
    <div class="form-group">
        <label>Technologies * (séparées par des virgules)</label>
        <input type="text" name="technologies" placeholder="PHP, MySQL, JavaScript" required>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>URL de l'image</label>
            <input type="url" name="image_url" placeholder="https://...">
        </div>
        
        <div class="form-group">
            <label>URL du projet</label>
            <input type="url" name="project_url" placeholder="https://...">
        </div>
        
        <div class="form-group">
            <label>URL GitHub</label>
            <input type="url" name="github_url" placeholder="https://...">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Créer le projet</button>
</form>