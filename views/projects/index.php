<section class="projects">
    <div class="container">
        <h1>Mes projets</h1>
        <div class="projects-grid">
            <?php foreach($projects as $project): ?>
            <div class="project-card">
                <?php if($project['image_url']): ?>
                <img src="<?php echo $project['image_url']; ?>" alt="<?php echo $project['title']; ?>">
                <?php endif; ?>
                <div class="project-content">
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($project['description'], 0, 150)) . '...'; ?></p>
                    <div class="project-tech">
                        <?php $techs = explode(',', $project['technologies']); ?>
                        <?php foreach($techs as $tech): ?>
                        <span class="tech-tag"><?php echo trim($tech); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= base_url('projects/show/' . $project['id']) ?>" class="btn btn-small">Voir le projet</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>