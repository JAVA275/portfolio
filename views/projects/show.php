<section class="project-detail">
    <div class="container">
        <h1><?php echo htmlspecialchars($project['title']); ?></h1>
        
        <?php if($project['image_url']): ?>
        <div class="project-image">
            <img src="<?php echo $project['image_url']; ?>" alt="<?php echo $project['title']; ?>">
        </div>
        <?php endif; ?>
        
        <div class="project-description">
            <h2>Description</h2>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
        </div>
        
        <div class="project-technologies">
            <h2>Technologies utilisées</h2>
            <?php $techs = explode(',', $project['technologies']); ?>
            <?php foreach($techs as $tech): ?>
            <span class="tech-badge"><?php echo trim($tech); ?></span>
            <?php endforeach; ?>
        </div>
        
        <div class="project-links">
            <?php if($project['project_url']): ?>
            <a href="<?php echo $project['project_url']; ?>" target="_blank" class="btn btn-primary">Voir le site</a>
            <?php endif; ?>
            <?php if($project['github_url']): ?>
            <a href="<?php echo $project['github_url']; ?>" target="_blank" class="btn btn-secondary">Code source</a>
            <?php endif; ?>
        </div>
        
        <a href="<?= base_url('projects') ?>" class="btn btn-secondary">← Retour aux projets</a>
    </div>
</section>