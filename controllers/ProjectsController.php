<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Project.php';

class ProjectsController {
    private $projectModel;
    
    public function __construct() {
        $this->projectModel = new Project();
    }
    
    public function index() {
        $projects = $this->projectModel->getAll();
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/projects/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function show($id) {
        $project = $this->projectModel->getById($id);
        
        if(!$project) {
            http_response_code(404);
            echo "Projet non trouvé";
            return;
        }
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/projects/show.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
?>