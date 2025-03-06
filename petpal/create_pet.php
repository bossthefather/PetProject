<?php
include 'index_navbar.php';
require_once 'config.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$alert_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $type = $_POST['type'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session after login

    // Validate inputs
    if (empty($name) || empty($gender) || empty($type)) {
        $message = "All fields are required.";
        $alert_class = "alert-danger";
    } else {
        // Insert pet into the database
        $sql = "INSERT INTO pets (user_id, name, gender, type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $name, $gender, $type);

        if ($stmt->execute()) {
            $message = "Pet created successfully!";
            $alert_class = "alert-success";
        } else {
            $message = "Error: " . $stmt->error;
            $alert_class = "alert-danger";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Pet - PetPal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Create a Pet</h2>

        <!-- Message Alert -->
        <?php if (!empty($message)) : ?>
            <div class="alert <?= $alert_class; ?> text-center">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <!-- Pet Creation Form -->
        <form action="create_pet.php" method="POST" class="mt-4">
            <!-- Pet Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <!-- Pet Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="boy">Boy</option>
                    <option value="girl">Girl</option>
                </select>
            </div>

            <!-- Pet Type -->
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="rabbit">Rabbit</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Create Pet</button>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
