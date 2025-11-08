<?php
// Database configuration
$host = 'localhost';
$dbname = 'test_db';
$username = 'root';
$password = '';

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add':
                    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
                    $stmt->execute([$_POST['name'], $_POST['email']]);
                    $message = 'User added successfully!';
                    $messageType = 'success';
                    break;
                
                case 'update':
                    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['id']]);
                    $message = 'User updated successfully!';
                    $messageType = 'success';
                    break;
                
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $message = 'User deleted successfully!';
                    $messageType = 'success';
                    break;
            }
        } catch(PDOException $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'error';
        }
        
        // Redirect with message
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=' . urlencode($message) . '&type=' . $messageType);
        exit;
    }
}

// Get message from URL
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $messageType = $_GET['type'] ?? 'info';
}

// Get user for editing
$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include the HTML view
include 'view.html';
?>