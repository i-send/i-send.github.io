<?php
// notifier.php - envoie d'emails via Gmail SMTP (PHPMailer recommended)
// Two uses:
// 1) send_receipt=1&id=...  -> send initial receipt to sender after envoi
// 2) id=...&send_email=1    -> send thank-you email when delivered
require 'config.php';

function smtp_send_simple($to, $subject, $body){
    // best-effort: try PHP mail() as fallback; for reliable SMTP use PHPMailer (instructions in README)
    $headers = 'From: i-send <'.SMTP_USER.'\r\n' . 'Reply-To: '.SMTP_USER.'\r\n';
    return mail($to, $subject, $body, $headers);
}

if(isset($_GET['send_receipt']) && $_GET['send_receipt']==1 && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM envois WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
        $to = $row['email_expediteur'];
        $subject = 'i-send - Confirmation d\'enregistrement (ID: '.$id.')';
        $body = "Bonjour {$row['nom_expediteur']},\n\nVotre envoi (ID: $id) de {$row['montant']} {$row['devise']} a bien été enregistré.\nNous vous informerons quand il sera livré.\n\nCordialement, i-send";
        smtp_send_simple($to,$subject,$body);
        echo 'OK';
    } else echo 'NotFound';
    exit;
}

if(isset($_GET['id']) && isset($_GET['send_email']) && $_GET['send_email']==1){
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM envois WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
        $to = $row['email_expediteur'];
        $subject = 'Merci - Envoi livré (ID: '.$id.')';
        $body = "Bonjour {$row['nom_expediteur']},\n\nVotre envoi (ID: $id) de {$row['montant']} {$row['devise']} (≈ {$row['montant_mga']} MGA) a été livré. Merci pour votre confiance.\n\nCordialement, i-send";
        smtp_send_simple($to,$subject,$body);
        echo 'SENT';
    } else echo 'NotFound';
    exit;
}

echo 'No action';
?>