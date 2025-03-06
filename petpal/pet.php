<?php
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

// Get the pet ID
if (!isset($_GET['pet_id'])) {
    header("Location: dashboard.php");
    exit;
}

$pet_id = intval($_GET['pet_id']);

// Fetch pet details
$sql = "SELECT * FROM pets WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pet_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Interaction - PetPal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/<?= htmlspecialchars($pet['type']); ?>/idle.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            overflow: hidden;
        }
        .bars {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
        }
        .bar {
            width: 150px;
            height: 20px;
            background: #333;
            border: 2px solid #fff;
            position: relative;
            border-radius: 5px;
        }
        .bar span {
            display: block;
            height: 100%;
            border-radius: 5px;
        }
        .bar-label {
            position: absolute;
            top: -25px;
            left: 0;
            color: #fff;
            font-size: 14px;
        }
        .buttons {
            position: absolute;
            bottom: 20px;
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 0 40px;
        }
        .buttons button {
            font-size: 1.2em;
            padding: 10px 20px;
        }
        .exit {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Exit Button -->
    <a href="dashboard.php" class="btn btn-danger exit">Exit</a>

    <!-- Bars -->
    <div class="bars">
        <div class="bar" id="food-bar">
            <span style="width: 100%; background: #0d6efd;"></span>
            <div class="bar-label">Food</div>
        </div>
        <div class="bar" id="thirst-bar">
            <span style="width: 100%; background: #0dcaf0;"></span>
            <div class="bar-label">Thirst</div>
        </div>
        <div class="bar" id="sleep-bar">
            <span style="width: 100%; background: #ffc107;"></span>
            <div class="bar-label">Sleep</div>
        </div>
        <div class="bar" id="happiness-bar">
            <span style="width: 100%; background: #198754;"></span>
            <div class="bar-label">Happiness</div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="buttons">
        <div>
            <button class="btn btn-primary" id="eat-btn">Eat</button>
            <button class="btn btn-info" id="drink-btn">Drink</button>
        </div>
        <div>
            <button class="btn btn-warning" id="sleep-btn">Sleep</button>
            <button class="btn btn-success" id="play-btn">Play</button>
        </div>
    </div>

    <!-- Audio -->
    <audio id="audio" src=""></audio>

    <script>
        let food = 100;
        let thirst = 100;
        let sleep = 100;
        let happiness = 100;
        let actionCooldown = false;

        const updateBar = (id, value) => {
            const bar = document.getElementById(id);
            bar.querySelector('span').style.width = value + '%';
        };

        const setActionCooldown = (status) => {
            actionCooldown = status;
            document.querySelectorAll('button').forEach(btn => btn.disabled = status);
        };

        const performAction = (action, valueChange, duration = 5000) => {
            setActionCooldown(true);

            // Change background and play sound
            document.body.style.backgroundImage = `url('images/<?= htmlspecialchars($pet['type']); ?>/${action}.jpg')`;
            const audio = document.getElementById('audio');
            audio.src = `audio/${action}.mp3`;
            audio.play();

            // Update stats
            if (action === 'play') {
                food = Math.max(food - 5, 0);
                thirst = Math.max(thirst - 5, 0);
                sleep = Math.max(sleep - 5, 0);
                happiness = Math.min(happiness + valueChange, 100);
            } else if (action === 'eat') {
                food = Math.min(food + valueChange, 100);
            } else if (action === 'drink') {
                thirst = Math.min(thirst + valueChange, 100);
            } else if (action === 'sleep') {
                sleep = Math.min(sleep + valueChange, 100);
            }

            updateAllBars();

            // Revert to idle
            setTimeout(() => {
                document.body.style.backgroundImage = "url('images/<?= htmlspecialchars($pet['type']); ?>/idle.jpg')";
                setActionCooldown(false);
            }, duration);
        };

        const updateAllBars = () => {
            updateBar('food-bar', food);
            updateBar('thirst-bar', thirst);
            updateBar('sleep-bar', sleep);
            updateBar('happiness-bar', happiness);
        };

        // Decrease stats every 2 seconds
        setInterval(() => {
            if (!actionCooldown) {
                food = Math.max(food - 4, 0);
                thirst = Math.max(thirst - 6, 0);
                sleep = Math.max(sleep - 3, 0);
                happiness = Math.max(happiness - 3, 0);
                updateAllBars();
            }
        }, 2000);

        // Event listeners
        document.getElementById('eat-btn').addEventListener('click', () => performAction('eat', 25));
        document.getElementById('drink-btn').addEventListener('click', () => performAction('drink', 30));
        document.getElementById('sleep-btn').addEventListener('click', () => performAction('sleep', 100));
        document.getElementById('play-btn').addEventListener('click', () => performAction('play', 60));

        // Initialize bars
        updateAllBars();
    </script>
</body>
</html>
