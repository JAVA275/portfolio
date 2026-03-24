<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    
    private function checkAuth() {
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /portfolio/admin/login');
            exit();
        }
    }
    
    public function index() {
        $this->checkAuth();
        $projectModel = new Project();
        $projects = $projectModel->getAll();
        
        require_once __DIR__ . '/../views/layouts/admin_header.php';
        require_once __DIR__ . '/../views/admin/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $user = $userModel->authenticate($_POST['username'], $_POST['password']);
            
            if($user) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                header('Location: /admin');
                exit();
            } else {
                $error = "Identifiants incorrects";
            }
        }
        
        require_once __DIR__ . '/../views/admin/login.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: /portfolio/admin/login');
        exit();
    }
    
    public function create() {
        $this->checkAuth();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $project = new Project();
            $project->title = $_POST['title'];
            $project->description = $_POST['description'];
            $project->technologies = $_POST['technologies'];
            $project->image_url = $_POST['image_url'];
            $project->project_url = $_POST['project_url'];
            $project->github_url = $_POST['github_url'];
            
            if($project->create()) {
                $_SESSION['success'] = "Projet créé avec succès!";
                header('Location: /admin');
            } else {
                $_SESSION['error'] = "Erreur lors de la création";
                header('Location: /portfolio/admin/create');
            }
            exit();
        }
        
        require_once __DIR__ . '/../views/layouts/admin_header.php';
        require_once __DIR__ . '/../views/admin/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function edit($id) {
        $this->checkAuth();
        $projectModel = new Project();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectModel->id = $id;
            $projectModel->title = $_POST['title'];
            $projectModel->description = $_POST['description'];
            $projectModel->technologies = $_POST['technologies'];
            $projectModel->image_url = $_POST['image_url'];
            $projectModel->project_url = $_POST['project_url'];
            $projectModel->github_url = $_POST['github_url'];
            
            if($projectModel->update()) {
                $_SESSION['success'] = "Projet modifié avec succès!";
                header('Location: /admin');
            }
            exit();
        }
        
        $project = $projectModel->getById($id);
        
        require_once __DIR__ . '/../views/layouts/admin_header.php';
        require_once __DIR__ . '/../views/admin/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function delete($id) {
        $this->checkAuth();
        $projectModel = new Project();
        
        if($projectModel->delete($id)) {
            $_SESSION['success'] = "Projet supprimé!";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression";
        }
        
        header('Location: /admin');
        exit();
    }
}
?>