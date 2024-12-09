# Package Management System

## Overview

The **Package Management System** is a web application designed for managing JavaScript packages and their associated authors. Users can search for packages, view package details, and explore the contributions of authors. There are two types of users: **Admin** (with full privileges to manage packages) and **User** (with limited access to search and view package information).

This project uses **PHP** for backend logic, **MySQL** for the database, and **HTML/CSS** for the frontend UI.

---

## Features

- **User Authentication**: Secure login for both Admin and User.
- **Search Packages**: Search packages by name or description.
- **View Package Details**: See descriptions, versions, and associated authors.
- **Responsive Design**: Optimized for desktops, tablets, and mobiles.
- **Admin Control Panel**: Admins can manage, add, edit, or delete packages.

---

## Technologies Used

- **PHP**: Handles server-side logic and database interaction.
- **MySQL**: Relational database for storing package and author information.
- **HTML5**: Structure of the web pages.
- **CSS3**: Styling and layout.
- **JavaScript**: For interactivity (form handling, search functionality).
- **Bootstrap** (Optional): For responsive design and layout.

---

## Database Schema

### Tables

#### `Packages`
- `id_package`: INT, Primary Key
- `nom_package`: VARCHAR(255), NOT NULL
- `description`: TEXT, NOT NULL

#### `Auteurs`
- `id_auteur`: INT, Primary Key
- `nom_auteur`: VARCHAR(255), NOT NULL

#### `Versions`
- `id_version`: INT, Primary Key
- `id_package`: INT, Foreign Key (References `Packages`)
- `version`: VARCHAR(50)

#### `auteurs_packages` (Many-to-Many Relationship)
- `id_package`: INT, Foreign Key (References `Packages`)
- `id_auteur`: INT, Foreign Key (References `Auteurs`)

---

### SQL Queries

**Package Search Query:**
```sql
SELECT p.id_package, p.nom_package, p.description, 
       MAX(v.version) AS version, GROUP_CONCAT(a.nom_auteur) AS authors
FROM Packages p
LEFT JOIN Versions v ON p.id_package = v.id_package
LEFT JOIN auteurs_packages ap ON p.id_package = ap.id_package
LEFT JOIN Auteurs a ON ap.id_auteur = a.id_auteur
WHERE p.nom_package LIKE :query OR p.description LIKE :query
GROUP BY p.id_package, p.nom_package, p.description
ORDER BY p.nom_package
```
# Setup Instructions

## Prerequisites
- PHP 7.4 or higher
- MySQL
- XAMPP or LAMP (Recommended for local development)

## Steps to Run the Project Locally

### Clone the Repository:
```
git clone https://github.com/Youcode-Classe-E-2024-2025/Hamza-Atig-SQL.git
```

### Install XAMPP or LAMP:
Install XAMPP (Windows) or LAMP (Linux).
Start Apache and MySQL from the control panel.
### Set Up Database:
Open phpMyAdmin or use the MySQL Command Line.
Create a new database (e.g., package_management).
Import the provided db.sql to create tables.

```sql
CREATE TABLE Packages (
    id_package INT AUTO_INCREMENT PRIMARY KEY,
    nom_package VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE Auteurs (
    id_auteur INT AUTO_INCREMENT PRIMARY KEY,
    nom_auteur VARCHAR(255) NOT NULL
);

CREATE TABLE Versions (
    id_version INT AUTO_INCREMENT PRIMARY KEY,
    id_package INT,
    version VARCHAR(50),
    FOREIGN KEY (id_package) REFERENCES Packages(id_package)
);

CREATE TABLE auteurs_packages (
    id_package INT,
    id_auteur INT,
    FOREIGN KEY (id_package) REFERENCES Packages(id_package),
    FOREIGN KEY (id_auteur) REFERENCES Auteurs(id_auteur)
);

```

## Configure Database Connection:
Edit config/db.php with your MySQL credentials.
### Run the Project:
Open your browser and go to: http://localhost/package-management-system/
## Login Credentials

- **Admin Login**:
  - Username: `admin`
  - Password: `admin`

- **User Login**:
  - Username: `user`
  - Password: `user`
## Usage
Login as User:
Access the `user.php` page and search for packages, view details, and explore authors.
Search for Packages:
Use the search bar to find packages by name or description.
### View Package Details:
Click on a package to view its details, version, and authors.
## Logout:
Click the "Logout" button to end your session.
## Admin Features
Admins can log in with admin credentials.
Admins can manage packages by adding, editing, or deleting them.
## Folder Structure
```plainetext
package-management/
├── config/
│   └── db.php                 # Database connection settings
├── public/
│   ├── user.php               # Main user page
│   ├── admin.php              # Admin page for managing packages
│   ├── login.php              # Login page
│   └── logout.php             # Logout script
└── README.md                  # Project README file
```
## Contributing
- Fork the repository.
- Clone your fork and create a new branch.
- Make changes and commit them.
- Push changes and create a pull request.
- Provide a clear description of the changes.

## Acknowledgments
- **PHP**: For server-side scripting.
- **MySQL**: For database management.
- **HTML5 & CSS3**: For frontend design.
- **Bootstrap**: For responsive layout.
