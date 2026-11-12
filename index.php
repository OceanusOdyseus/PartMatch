<?php
require_once "db.php";

$stmt = $pdo->query(
    "SELECT l.listing_id, l.asking_price, l.part_condition, l.location, p.name AS part_name
    FROM listings l
    JOIN parts p ON p.part_id = l.part_id
    WHERE l.status = 'available'
    ORDER BY l.date_time DESC
    LIMIT 3"
);
$listings = $stmt->fetchALL();
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
        <h1><a href="index.php" class="logo">PartMatch</a></h1>
        <nav>
            <a href="create_listing.php">Create Listing</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    

    <main class="non-search-main">
        <section class="searchBar">
            <form action="search.php" method="GET">
                <input type="text" name="q"
                    placeholder="Search for PC parts">
                <button type="submit">Search</button>
            </form>

            <div class="categories"> 
                <a href="search.php">GPU</a>
                <a href="search.php">CPU</a>
                <a href="search.php">PSU</a>
                <a href="search.php">RAM</a>
                <a href="search.php">Storage</a>
                <a href="search.php">Motherboard</a>
            </div>
        </section>

        <section class="recentListings">
            <h2>Recent Listings</h2>
            <?php if(count($listings) === 0) { ?>
                    <p> No listing yet. <a href="create_listing.php">Be the first.</a></p>
            <?php } ?>
            <div class="listingGrid">
                <?php foreach($listings as $row){ ?>
                   <a class="card" href="listing.php?id=<?php echo (int)$row['listing_id']; ?>">
                    <img
                        src="image/default-part.jpg"
                        alt="Default PC part image"
                    >
                    <h3>PC part: <?php echo input($row['part_name']); ?></h3>
                    <p>Price: $<?php echo input($row['asking_price']); ?></p>
                    <p>Condition: <?php echo input(condition_label($row['part_condition'])); ?></p>
                    <p>Location: <?php echo input($row['location']); ?></p>
                    </a>
                <?php } ?>                
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 PartMatch</p>
    </footer>
</body>
</html>



