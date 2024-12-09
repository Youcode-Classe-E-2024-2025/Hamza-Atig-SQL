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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #e0e0e0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        .container {
            width: 85%;
            margin: 20px auto;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.6);
        }

        .search-bar button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .packages-list {
            list-style-type: none;
            padding: 0;
        }

        .package-item {
            background-color: #2a2a2a;
            margin: 10px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .package-item:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .package-item h3 {
            margin: 0;
            font-size: 24px;
        }

        .package-item em {
            font-size: 16px;
            color: #777;
        }

        .package-item p {
            margin-top: 15px;
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        .logout-btn {
            display: block;
            margin-bottom: 20px;
            color: #fff;
            background-color: #dc3545;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
    </style>
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
