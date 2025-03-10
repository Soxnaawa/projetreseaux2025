<?php
require 'db.php';

// Ajouter un employé
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $stmt = $pdo->prepare("INSERT INTO employees (name, email, position) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $position])) {
        header("Location: gesemployes.php");
        exit();
    }
}

// Modifier un employé
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $stmt = $pdo->prepare("UPDATE employees SET name = ?, email = ?, position = ? WHERE id = ?");
    if ($stmt->execute([$name, $email, $position, $id])) {
        header("Location: gesemployes.php");
        exit();
    }
}

// Supprimer un employé
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    if ($stmt->execute([$id])) {
        header("Location: gesemployes.php");
        exit();
    }
}
?>
