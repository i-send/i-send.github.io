<?php
// config.php - configuration MySQL and email (Gmail app password)
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'i_send';

// Gmail SMTP settings
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_PORT',587);
define('SMTP_USER','madagasikarasoa2025@gmail.com');
define('SMTP_PASS','1234567891234567'); // <= MOT DE PASSE D'APPLICATION fourni

// Connexion MySQL
$conn = new mysqli($host,$user,$pass,$dbname);
if($conn->connect_error){
    die('Erreur de connexion MySQL: '.$conn->connect_error);
}
?>