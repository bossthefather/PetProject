<?php
require_once 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle the deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];
    $user_id = $_SESSION['user_id'];

    // Delete the pet only if it belongs to the logged-in user
    $sql = "DELETE FROM pets WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pet_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error deleting pet.";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
