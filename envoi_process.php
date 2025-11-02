<?php
require 'config.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){
    header('Location: index.html'); exit;
}

// Récupération et sécurisation
$nom_expediteur = $conn->real_escape_string($_POST['nom_expediteur']);
$email_expediteur = $conn->real_escape_string($_POST['email_expediteur']);
$nom_destinataire = $conn->real_escape_string($_POST['nom_destinataire']);
$numero_destinataire = $conn->real_escape_string($_POST['numero_destinataire']);
$montant = floatval($_POST['montant']);
$devise = $conn->real_escape_string($_POST['devise']);
$montant_mga = floatval($_POST['montant_mga']);
$frais = floatval($_POST['frais']);
$total_mga = floatval($_POST['total_mga']);

// Enregistrer dans la base
$stmt = $conn->prepare('INSERT INTO envois (nom_expediteur,email_expediteur,nom_destinataire,numero_destinataire,montant,devise,montant_mga,frais) VALUES (?,?,?,?,?,?,?,?)');
$stmt->bind_param('ssssdssd',$nom_expediteur,$email_expediteur,$nom_destinataire,$numero_destinataire,$montant,$devise,$montant_mga,$frais);
$stmt->execute();
$id = $stmt->insert_id;
$stmt->close();

// Envoyer accusé de réception (simple)
$subject = 'i-send - Confirmation d\'enregistrement';
$body = "Bonjour $nom_expediteur,\n\nVotre envoi (ID: $id) de $montant $devise a été enregistré. Nous vous informerons lorsqu'il sera livré.\n\nCordialement, i-send";

// Use notifier logic by calling notifier.php?send_receipt=1&id=...
$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['REQUEST_URI']);
@file_get_contents('http://'.$host.$path.'/notifier.php?send_receipt=1&id='.$id);

// Afficher confirmation simple
echo "<p style='color:#FFD700;text-align:center;'>Envoi enregistré (ID: $id). Un email de confirmation a été envoyé.</p>";
echo "<p style='text-align:center;'><a href='index.html' style='color:#FFD700;'>← Retour</a></p>";
$conn->close();
?>