<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
            background-color: #1e1e2e;
            color: #ffffff;
            font-family: Arial, sans-serif;
            padding-top: 40px;
        
        }

        .container {
            background-color: #282a36;        
           border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 500;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .table th, .table td {
            text-align: center;
            padding: 12px;
        }

        .table th {
            background-color: #f0f0f0;
            color: #555;
        }

        .table td {
            background-color: #fff;
            color: #333;
            border-top: 1px solid #ddd;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-warning {
            background-color: #e0a800;
            border-color: #ffc107;
            font-weight: 500;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .btn-danger {
            background-color: #c82333;
            border-color: #dc3545;
            font-weight: 500;
        }

 .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: 500;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .modal-footer {
            border-top: 1px solid #ddd;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: none;
        }

        .btn-close {
            background-color: #6c757d;
        }
h5{
color:#9FA6BC;}


.modal-body{
background-color:#9FA6BC;}
        .modal-title {
            font-weight: 600;
        }

        .container .mb-3 {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Gestion des Clients</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClientModal">Ajouter un client</button>
        
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Entreprise</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM clients");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['company']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['id']}' data-name='{$row['name']}' data-email='{$row['email']}' data-company='{$row['company']}' data-bs-toggle='modal' data-bs-target='#editClientModal'>Modifier</button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'>Supprimer</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Ajouter Client -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="crudclients.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Entreprise</label>
                            <input type="text" class="form-control" name="company" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="add">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Client -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="crudclients.php" method="POST">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="name" id="edit-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-company" class="form-label">Entreprise</label>
                            <input type="text" class="form-control" name="company" id="edit-company" required>
                        </div>
                        <button type="submit" class="btn btn-warning" name="update">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('edit-id').value = this.getAttribute('data-id');
                document.getElementById('edit-name').value = this.getAttribute('data-name');
                document.getElementById('edit-email').value = this.getAttribute('data-email');
                document.getElementById('edit-company').value = this.getAttribute('data-company');
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                if (confirm("Voulez-vous vraiment supprimer ce client ?")) {
                    window.location.href = `crudclients.php?delete=${this.getAttribute('data-id')}`;
                }
            });
        });
    </script>
</body>
</html>
