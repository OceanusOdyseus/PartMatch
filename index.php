<?php
$conn = mysqli_connect("localhost", "root", "", "partmatch");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM listings
        WHERE status = 'available'
        ORDER BY date_time DESC
        LIMIT 3";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
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
        <a href="index.php">PartMatch</a>
        <nav>
            <a href="search.php">Create Listing</a>
            <a href="#">Profile</a>
        </nav>
    </header>

    

    <main>
        <section class="searchBar">
            <form action="search.php" method="GET">
                <input type="text" name="search"
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
            <div class="listingGrid">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>

                    <div class="card">
                        
                        <?php if(!empty($row['image1_data'])) { ?>
                            <img
                                src="data:image/jpeg;base64,<?php
                                    echo base64_encode($row['image1_data']);
                                ?>" 
                                alt="PC part" 
                            >  
                        <?php } else { ?>
                            <img 
                                src="image/default-part.jpg"
                                alt="Default PC part image"
                            >
                        <?php } ?>

                        <h3>PC part<?php echo htmlspecialchars($row['part_id']); ?></h3>
                        <p>Price: $<?php echo htmlspecialchars($row['asking_price']); ?></p>
                        <p>Condition: $<?php echo htmlspecialchars($row['part_condition']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                    </div>
                <?php } ?>                
            </div>
        </section>

        <footer>
        <p>&copy; 2026 PartMatch</p>
        </footer>
    </main>
</body>
</html>

