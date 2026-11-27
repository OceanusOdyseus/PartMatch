<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-body">
    <header class="register-header">
        <h1><a href="index.php" class="logo">PartMatch</a></h1>
    </header>

        <form class="register-form" action="#" method="POST">

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Choose a username" required>
            </div>

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Create a password" required>
            </div>

            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password"
                       name="confirm-password"
                       placeholder="Re-enter your password" required>
            </div>

            <button type="submit">Create Account</button>

        </form>

        <p>Already have an account?</p>
        <nav class="register-nav">
            <a href="login.php">Log In</a>
        </nav>

</body>
</html>