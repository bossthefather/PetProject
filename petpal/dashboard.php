<?php
// Include the navbar and configuration file
include 'dashboard_navbar.php';
require_once 'config.php';

// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user's pets
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM pets WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pets = $result->fetch_all(MYSQLI_ASSOC);

// Check if the user has reached the maximum number of pets
$can_create_pet = count($pets) < 3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PetPal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Welcome to Your Dashboard</h2>

        <!-- Create Pet Button -->
        <div class="text-center my-4">
            <?php if ($can_create_pet): ?>
                <a href="create_pet.php" class="btn btn-primary">Create Pet</a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>Maximum Pets Reached</button>
            <?php endif; ?>
        </div>

        <!-- Display Pets -->
        <?php if (!empty($pets)): ?>
            <div class="row">
                <?php foreach ($pets as $pet): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <!-- Pet Image -->
                            <img 
                                src="images/<?= htmlspecialchars($pet['type']); ?>/idle.jpg" 
                                class="card-img-top" 
                                alt="<?= htmlspecialchars($pet['type']); ?> Image"
                            >
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($pet['name']); ?></h5>
                                <p class="card-text">
                                    <strong>Gender:</strong> <?= htmlspecialchars($pet['gender']); ?><br>
                                    <strong>Type:</strong> <?= htmlspecialchars($pet['type']); ?>
                                </p>
                                <!-- Buttons -->
                                <div class="d-flex justify-content-between">
                                    <!-- Play Button -->
                                    <a href="pet.php?pet_id=<?= $pet['id']; ?>" class="btn btn-success">Play</a>
                                    <!-- Delete Button -->
                                    <form action="delete_pet.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="pet_id" value="<?= $pet['id']; ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this pet?');">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">You don't have any pets yet. Click "Create Pet" to get started!</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
