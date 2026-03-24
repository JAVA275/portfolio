<?php
class Contact {
    public $name;
    public $email;
    public $message;
    
    public function sendEmail() {
        $env = parse_ini_file(__DIR__ . '/../.env');
        $to = $env['ADMIN_EMAIL'];
        $subject = "Nouveau message - " . $this->name;
        $body = "Nom: " . $this->name . "\n";
        $body .= "Email: " . $this->email . "\n";
        $body .= "Message:\n" . $this->message;
        $headers = "From: " . $this->email;
        
        return mail($to, $subject, $body, $headers);
    }
}
?>