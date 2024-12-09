<?php
require '../config/db.php';

// Search functionality
$query = $_GET['query'] ?? '';

// Fetch packages with their version and authors
$stmt = $pdo->prepare("
    SELECT p.id_package, p.nom_package, p.description, 
       MAX(v.version) AS version, GROUP_CONCAT(a.nom_auteur) AS authors
    FROM Packages p
    LEFT JOIN Versions v ON p.id_package = v.id_package
    LEFT JOIN auteurs_packages ap ON p.id_package = ap.id_package
    LEFT JOIN Auteurs a ON ap.id_auteur = a.id_auteur
    WHERE p.nom_package LIKE :query OR p.description LIKE :query
    GROUP BY p.id_package, p.nom_package, p.description
    ORDER BY p.nom_package
");
$stmt->execute(['query' => "%$query%"]);
$packages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>

<body>

    <header>
        <h1>Welcome, User!</h1>
    </header>

    <div class="container">
        <a href="logout.php" class="logout-btn">Logout</a>
        
        <div class="search-bar">
            <form method="GET" action="user.php">
                <input type="text" name="query" placeholder="Search packages"
                    value="<?php echo htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <h2>Packages</h2>
        <ul class="packages-list">
            <?php foreach ($packages as $package): ?>
                <li class="package-item">
                    <h3><?php echo htmlspecialchars($package['nom_package'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                    <em>Version: <?php echo htmlspecialchars($package['version'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                    <em>Authors: <?php echo htmlspecialchars($package['authors'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                    <p><?php echo nl2br(htmlspecialchars($package['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer>
        <p>&copy; 2024 Package Management System</p>
    </footer>

</body>

</html>
