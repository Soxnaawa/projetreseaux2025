<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Chargement de PHPMailer via Composer
require 'db.php'; // Connexion à la base de données de l'application

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et validation des données du formulaire
    $name  = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password_plain = $_POST['password'];
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
    $role  = $_POST['role'];

    // Vérifier la validité de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email invalide.'); window.location.replace('creercompte.html');</script>";
        exit();
    }

    // Récupération du nom d'utilisateur et du domaine
    list($username, $domain) = explode('@', $email);

    // Vérifier si l'email existe déjà dans la table users
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "<script>alert('Cet email est déjà utilisé.'); window.location.replace('creercompte.html');</script>";
        exit();
    }

    // Définir le quota pour iRedMail (exemple : 100MB)
    $quota_mb = 100;
    $quota_bytes = $quota_mb * 1024 * 1024;
    // iRedMail attend le mot de passe en clair précédé de {PLAIN}
    $hashed_password_for_mailbox = "{PLAIN}" . $password_plain;

    // Insertion de l'utilisateur dans la base de l'application
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $password_hashed, $role])) {

        // Connexion à la base iRedMail (vmail)
        try {
            $pdoMail = new PDO("mysql:host=localhost;dbname=vmail", "root", "dieynaba14");
            $pdoMail->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "<script>alert('Erreur de connexion à la base iRedMail : " . addslashes($e->getMessage()) . "'); window.location.replace('creercompte.html');</script>";
            exit();
        }

        // Vérifier si le domaine existe déjà dans iRedMail, sinon l'ajouter
        $stmt = $pdoMail->prepare("SELECT COUNT(*) FROM domain WHERE domain = ?");
        $stmt->execute([$domain]);
        if ($stmt->fetchColumn() == 0) {
            $pdoMail->prepare("INSERT INTO domain (domain) VALUES (?)")->execute([$domain]);
        }

        // Vérifier si l'utilisateur existe déjà sur iRedMail
        $stmt = $pdoMail->prepare("SELECT COUNT(*) FROM mailbox WHERE username = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('L\'adresse email existe déjà sur le serveur mail.'); window.location.replace('creercompte.html');</script>";
            exit();
        }

        // Insérer l'utilisateur dans la table mailbox d'iRedMail
        $stmt = $pdoMail->prepare("INSERT INTO mailbox (username, domain, password, created, active, quota)
                                   VALUES (?, ?, ?, NOW(), 1, ?)");
        if (!$stmt->execute([$email, $domain, $hashed_password_for_mailbox, $quota_bytes])) {
            echo "<script>alert('Erreur lors de la création du compte mail.'); window.location.replace('creercompte.html');</script>";
            exit();
        }

        // Insérer un alias pour l'utilisateur (facultatif)
        $pdoMail->prepare("INSERT INTO alias (address, domain, created) VALUES (?, ?, NOW())")->execute([$email, $domain]);

        // Envoyer un email de confirmation via PHPMailer
        if (sendConfirmationEmail($email, $name)) {
            echo "<script>alert('Compte créé avec succès ! Un email de confirmation vous a été envoyé.'); window.location.replace('connexion.html');</script>";
        } else {
            echo "<script>alert('Compte créé, mais erreur lors de l\'envoi de l\'email.'); window.location.replace('connexion.html');</script>";
        }
    } else {
        echo "<script>alert('Erreur lors de l\'inscription. Veuillez réessayer.'); window.location.replace('creercompte.html');</script>";
    }
} else {
    header("Location: creercompte.html");
    exit();
}

// Fonction d'envoi d'email de confirmation avec PHPMailer
function sendConfirmationEmail($email, $name) {
    $mail = new PHPMailer(true);
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.smarttech.sn'; // Remplace par votre serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@smarttech.sn'; // Identifiant SMTP
        $mail->Password = '#GLSI2025';     // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Désactivation de la vérification du certificat SSL (option 2, à n'utiliser qu'en développement)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );

        // Paramètres de l'email
        $mail->setFrom('admin@smarttech.sn', 'SmartTech');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Bienvenue chez SmartTech ! 🎉';
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
        
                <p style='font-size: 16px; color: #333;'>Nous sommes ravis de vous compter parmi nous.</p>
                <p style='font-size: 16px; color: #333;'>Votre compte a bien été créé sur <strong>SmartTech</strong>.</p>
                 <p style='font-size: 14px; color: #777;'>Si vous n'êtes pas à l'origine de cette inscription, ignorez cet email.</p>
                   ";

        // Envoi de l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi email : " . $mail->ErrorInfo);
        return false;
    }
}
?>
