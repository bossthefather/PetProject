<?php include 'index_navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PetPal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>

        <!-- Login Form -->
        <form action="process_login.php" method="POST" class="mt-4">
            <!-- Email or Name -->
            <div class="mb-3">
                <label for="email_or_name" class="form-label">Email or Name</label>
                <input type="text" name="email_or_name" id="email_or_name" class="form-control" required
                    value="<?= isset($_COOKIE['email_or_name']) ? $_COOKIE['email_or_name'] : ''; ?>">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required
                    value="<?= isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
            </div>

            <!-- Show Password Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="showPassword">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="remember" id="remember"
                    <?= isset($_COOKIE['email_or_name']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </div>
        </form>

        <!-- Register Text -->
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="register.php">Press here to register</a>.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show Password Script -->
    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const type = this.checked ? 'text' : 'password';
            passwordField.type = type;
        });
    </script>
</body>
</html>
