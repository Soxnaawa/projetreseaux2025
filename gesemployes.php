<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2e; /* Fond clair pour la page */
        }
        .container {
            margin-top: 30px;
background-color: #282a36;
        }
        .modal-header {
            background-color: #e9ecef; /* Couleur de fond neutre pour les modals */
        }
        .btn {
            border-radius: 0.25rem; /* Bords arrondis pour les boutons */
               background-color: #007bff;
        }
        table {
            background-color: white;
        }
.btn-outline-danger{
background-color:#CDA4A4;
font-color:#000;}

.btn-outline-secondary{background-color:#f0f0f0;}
        th, td {
            text-align: center;
        }
 h1{
color: #fff;}
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center">Gestion des Employés</h1>
        <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Ajouter un employé</button>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'db.php';
                $stmt = $pdo->query("SELECT * FROM employees");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['position']}</td>
                            <td>
                                <button class='btn btn-outline-secondary btn-sm edit-btn' data-id='{$row['id']}' data-name='{$row['name']}' data-email='{$row['email']}' data-position='{$row['position']}' data-bs-toggle='modal' data-bs-target='#editEmployeeModal'>Modifier</button>
                                <button class='btn btn-outline-danger btn-sm delete-btn' data-id='{$row['id']}'>Supprimer</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Ajouter Employé -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="crudemploye.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" required>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="add">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Employé -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="crudemploye.php" method="POST">
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
                            <label for="edit-position" class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" id="edit-position" required>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary" name="update">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Récupérer les infos de l'employé pour le modal d'édition
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('edit-id').value = this.getAttribute('data-id');
                document.getElementById('edit-name').value = this.getAttribute('data-name');
                document.getElementById('edit-email').value = this.getAttribute('data-email');
                document.getElementById('edit-position').value = this.getAttribute('data-position');
            });
        });

        // Confirmation de suppression
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                if (confirm("Voulez-vous vraiment supprimer cet employé ?")) {
                    window.location.href = `crudemploye.php?delete=${this.getAttribute('data-id')}`;
                }
            });
        });
    </script>
</body>
</html>
