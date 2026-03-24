<?php
require_once __DIR__ . '/../config/database.php';

class Project {
    private $conn;
    private $table = 'projects';
    
    public $id;
    public $title;
    public $description;
    public $technologies;
    public $image_url;
    public $project_url;
    public $github_url;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET title=:title, description=:description, 
                      technologies=:technologies, image_url=:image_url,
                      project_url=:project_url, github_url=:github_url";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':technologies', $this->technologies);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':project_url', $this->project_url);
        $stmt->bindParam(':github_url', $this->github_url);
        
        return $stmt->execute();
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      description = :description, 
                      technologies = :technologies,
                      image_url = :image_url,
                      project_url = :project_url,
                      github_url = :github_url
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':technologies', $this->technologies);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':project_url', $this->project_url);
        $stmt->bindParam(':github_url', $this->github_url);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>