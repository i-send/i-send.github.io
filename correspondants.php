<?php
require 'config.php';
$conn = $conn ?? null;
if(!$conn){ die('Erreur DB connexion'); }

// Gérer marquage livré via POST
$message = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['livrer'])){
    $id = intval($_POST['id']);
    // récupérer info
    $stmt = $conn->prepare('SELECT * FROM envois WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
        if($row['statut']==='Livré'){
            $message = 'Cet envoi est déjà marqué Livré.';
        } else {
            // mettre à jour statut
            $u = $conn->prepare('UPDATE envois SET statut=? WHERE id=?');
            $stat='Livré';
            $u->bind_param('si',$stat,$id);
            $u->execute();
            // envoyer email de remerciement via notifier.php (local call)
            $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/notifier.php?id=' . $id . '&send_email=1';
            @file_get_contents($url);
            $message = 'Envoi marqué Livré et email envoyé.';
        }
    } else {
        $message = 'Envoi introuvable.';
    }
}

// lister envois
$res2 = $conn->query('SELECT * FROM envois ORDER BY date_envoi DESC');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"><title>Correspondants - i-send</title>
<link rel="icon" type="image/png" href="favicon.png">
<style>
body{background:#000;color:#FFD700;font-family:Arial;padding:20px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #FFD700;padding:8px;text-align:center;}
.button{background:#FFD700;color:#000;padding:6px 10px;border:none;border-radius:6px;cursor:pointer;}
</style>
</head>
<body>
<h1>Interface Correspondants</h1>
<?php if($message) echo '<p style="color:#FFD700;">'.htmlspecialchars($message).'</p>'; ?>
<table>
<tr><th>ID</th><th>Expéditeur</th><th>Email</th><th>Destinataire</th><th>Montant</th><th>Devise</th><th>MGA</th><th>Frais</th><th>Statut</th><th>Action</th></tr>
<?php while($row = $res2->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['nom_expediteur']); ?></td>
<td><?php echo htmlspecialchars($row['email_expediteur']); ?></td>
<td><?php echo htmlspecialchars($row['nom_destinataire']); ?></td>
<td><?php echo $row['montant']; ?></td>
<td><?php echo $row['devise']; ?></td>
<td><?php echo number_format($row['montant_mga'],2); ?></td>
<td><?php echo number_format($row['frais'],2); ?></td>
<td><?php echo $row['statut']; ?></td>
<td>
<?php if($row['statut']!=='Livré'): ?>
<form method="POST" style="margin:0;">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" name="livrer" class="button">Marquer Livré</button>
</form>
<?php else: ?>✔
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
<a href="index.html" style="color:#FFD700;display:block;margin-top:12px;">← Retour</a>
</body>
</html>
<?php $conn->close(); ?>