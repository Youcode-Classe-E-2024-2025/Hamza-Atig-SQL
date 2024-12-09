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
