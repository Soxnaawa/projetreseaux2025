<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        // Création de session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirection selon le rôle
        if ($user['role'] == 'admin') {
            header("Location: index.html");
        } else {
            header("Location: index2.html");
        }
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.'); window.location.href = 'connexion.html';</script>";
    }
}
?>
