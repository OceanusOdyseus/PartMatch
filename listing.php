<?php
require_once "db.php";
// Get the listing id from the address
$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PartMatch</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
    <header>
        <a href="index.php" class="logo">PartMatch</a>
        <nav>
            <a href="search.php">Search</a>
            <a href="create_listing.php">Create Listing</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    

    <main class="non-search-main">
        
    </main>

    <footer>
        <p>&copy; 2026 PartMatch</p>
    </footer>
</body>
</html>

