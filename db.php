<?php

$host = 'localhost';
$dbname = 'smarttech';
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = 'dieynaba14'; // Remplacez par votre mot de passe MySQL si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

?>
