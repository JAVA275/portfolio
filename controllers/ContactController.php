<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../models/Contact.php';

class ContactController {
    public function index() {
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/contact/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function send() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact = new Contact();
            $contact->name = $_POST['name'];
            $contact->email = $_POST['email'];
            $contact->message = $_POST['message'];
            
            if($contact->sendEmail()) {
                $_SESSION['success'] = "Message envoyé avec succès!";
            } else {
                $_SESSION['error'] = "Erreur lors de l'envoi.";
            }
            
            header('Location: /portfolio/contact');
            exit();
        }
    }
}
?>