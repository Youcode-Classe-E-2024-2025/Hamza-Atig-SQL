<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
require '../config/db.php';

// Handle package addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO Packages (nom_package, description, date_ajout) VALUES (:name, :description, NOW())");
    $stmt->execute(['name' => $name, 'description' => $description]);
    header('Location: admin.php'); // Refresh to display the new package
}

// Handle package deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Packages WHERE id_package = :id");
    $stmt->execute(['id' => $id]);
    header('Location: admin.php'); // Refresh the page
}

// Handle version addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_version'])) {
    $package_id = $_POST['package_id'];
    $version = $_POST['version'];
    $stmt = $pdo->prepare("INSERT INTO Versions (id_package, version, date_release) VALUES (:package_id, :version, NOW())");
    $stmt->execute(['package_id' => $package_id, 'version' => $version]);
    header('Location: admin.php'); // Refresh to display the new version
}

// Handle authors addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_author'])) {
    $package_id = $_POST['package_id'];
    $author_id = $_POST['author_id'];
    $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_package, id_auteur) VALUES (:package_id, :author_id)");
    $stmt->execute(['package_id' => $package_id, 'author_id' => $author_id]);
    header('Location: admin.php'); // Refresh to display the new author
}

// Handle author addition (for the authors table itself)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_new_author'])) {
    $author_name = $_POST['author_name'];
    $email = $_POST['email'];  // Get the email input

    $stmt = $pdo->prepare("INSERT INTO Auteurs (nom_auteur, email) VALUES (:author_name, :email)");
    $stmt->execute(['author_name' => $author_name, 'email' => $email]);
    header('Location: admin.php'); // Refresh to display the new author
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
        }

        .container {
            margin-top: 40px;
        }

        .form-container {
            margin-bottom: 40px;
            padding: 30px;
            background-color: #1c1c1c;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container:hover {
            transform: scale(1.01);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        .package-item {
            margin: 15px 0;
            padding: 15px;
            background-color: #2a2a2a;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .package-item:hover {
            transform: scale(1.01);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .package-item h4 {
            margin-top: 0;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.01);
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 40px;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Form input and button styling */
        .form-control {
            background-color: #333;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:hover,
        .form-control:focus {
            border-color: #ff4b5c;
            box-shadow: 0 0 8px rgba(255, 75, 92, 0.6);
        }

        .btn {
            border-radius: 8px;
            font-size: 16px;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            margin-bottom: 19px;
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome, Admin!</h1>
    </header>

    <div class="container">

        <!-- Logout Button -->
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <!-- Add Package Form -->
        <div class="form-container">
            <h2>Add a New Package</h2>
            <form method="POST" action="admin.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Package Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Package name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" placeholder="Package description" required></textarea>
                </div>
                <button type="submit" name="add_package" class="btn btn-success">Add Package</button>
            </form>
        </div>

        <!-- Add Version Form -->
        <div class="form-container">
            <h2>Add Version to Package</h2>
            <form method="POST" action="admin.php">
                <div class="mb-3">
                    <label for="package_id" class="form-label">Select Package</label>
                    <select class="form-control" name="package_id" required>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM Packages");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['id_package']}'>{$row['nom_package']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="version" class="form-label">Version</label>
                    <input type="text" class="form-control" name="version" placeholder="Package version" required>
                </div>
                <button type="submit" name="add_version" class="btn btn-primary">Add Version</button>
            </form>
        </div>

        <!-- Add Author Form -->
        <div class="form-container">
            <h2>Add Author to Package</h2>
            <form method="POST" action="admin.php">
                <div class="mb-3">
                    <label for="package_id" class="form-label">Select Package</label>
                    <select class="form-control" name="package_id" required>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM Packages");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['id_package']}'>{$row['nom_package']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="author_id" class="form-label">Select Author</label>
                    <select class="form-control" name="author_id" required>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM Auteurs");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['id_auteur']}'>{$row['nom_auteur']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="add_author" class="btn btn-info">Add Author</button>
            </form>
        </div>

        <!-- Add New Author Form -->
        <div class="form-container">
            <h2>Add New Author</h2>
            <form method="POST" action="admin.php">
                <div class="mb-3">
                    <label for="author_name" class="form-label">Author Name</label>
                    <input type="text" class="form-control" name="author_name" placeholder="Author's name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Author's email" required>
                </div>
                <button type="submit" name="add_new_author" class="btn btn-warning">Add Author</button>
            </form>
        </div>

        <!-- Existing Packages List -->
        <h2>Existing Packages</h2>
        <?php
        $stmt = $pdo->query("SELECT * FROM Packages");
        while ($row = $stmt->fetch()) {
            echo "<div class='package-item'>";
            echo "<h4>{$row['nom_package']}</h4>";
            echo "<p>{$row['description']}</p>";
            echo "<a href='?delete={$row['id_package']}' class='btn btn-danger'>Delete Package</a>";
            echo "</div>";
        }
        ?>

    </div>

    <footer>
        <p>&copy; 2024 Your Game Store</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
