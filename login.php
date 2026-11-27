<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class=login-body>
    <header class="login-header">
        <h1><a href="index.php" class="logo">PartMatch</a></h1>
    </header>

    <form class="login-form" action="profile.php" method="POST">

        <div class="input-group">
        <label>Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>

        <div class="input-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit">Log In</button>
    </form>
    
<p>Want to become a seller?</p> 
<nav class="login-nav">
    <a href="registration.php">Create Account</a>
</nav>

</body>
</html>