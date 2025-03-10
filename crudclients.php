<?php
require 'db.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    
    $stmt = $pdo->prepare("INSERT INTO clients (name, email, company) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $company]);
    
    header("Location: gesclients.php");
    exit();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    
    $stmt = $pdo->prepare("UPDATE clients SET name = ?, email = ?, company = ? WHERE id = ?");
    $stmt->execute([$name, $email, $company, $id]);
    
    header("Location: gesclients.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: gesclients.php");
    exit();
}
?>
