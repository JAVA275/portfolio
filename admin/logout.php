<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: /portfolio/admin/login.php');
exit;
