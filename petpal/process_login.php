<?php
// Include database configuration
require_once 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_or_name = trim($_POST['email_or_name']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Validate inputs
    if (empty($email_or_name) || empty($password)) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    // Check if user exists by email or name
    $sql = "SELECT * FROM users WHERE email = ? OR name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_name, $email_or_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Set cookies if "Remember Me" is checked
            if ($remember) {
                setcookie('email_or_name', $email_or_name, time() + (86400 * 30), "/"); // 30 days
                setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days
            } else {
                // Clear cookies
                setcookie('email_or_name', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('No account found with this email or name.'); window.history.back();</script>";
        exit;
    }
}

$conn->close();
?>
