INSTALLATION LOCALE (XAMPP)
1) Copiez le dossier 'i-send-v2' dans C:\xampp\htdocs\
2) Démarrez Apache et MySQL via XAMPP Control Panel.
3) Ouvrez http://localhost/phpmyadmin
4) Créez la base de données i_send (collation utf8mb4_general_ci) puis exécutez ce SQL pour créer la table:
CREATE TABLE envois (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_expediteur VARCHAR(100) NOT NULL,
    email_expediteur VARCHAR(100) NOT NULL,
    nom_destinataire VARCHAR(100) NOT NULL,
    numero_destinataire VARCHAR(30) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    devise VARCHAR(10) NOT NULL,
    montant_mga DECIMAL(12,2) NOT NULL,
    frais DECIMAL(10,2) NOT NULL,
    statut ENUM('En attente','Livré') DEFAULT 'En attente',
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
5) Éditez config.php si nécessaire (MySQL user/password).
6) Pour emails fiables avec Gmail, installez PHPMailer (composer require phpmailer/phpmailer) et suivez README notes.
7) Accédez à http://localhost/i-send-v2/index.html
8) Testez un envoi et marquez Livré dans l'interface correspondants.
SECURITE: Ne laissez pas SMTP_PASS dans un serveur public. Utilisez variables d'environnement en production.
