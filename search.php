<?php
$conn = mysqli_connect("localhost", "root", "", "partmatch");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM listings";

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
    <title>Search</title>
</head>
<body>
    <header class="search-header">
        <h1><a href="index.php">PartMatch</a></h1>

        <section class="search">
            <input type="search">
            <button type="button">Search</button>
        </section>

        <nav>
            <a href="#">Create Listing</a>
            <a href="#">&emsp;Profile</a>
        </nav>

    </header>

<main class="search-main">
    <div class="search-filter">
        <h3>Filters</h3>

        <div class="filter-group">
            <h4>Price</h4>
            $<input type="number"> min -
            $<input type="number"> max

        </div>

        <div class="filter-group">
            <h4>Condition</h4>
            <input type="checkbox"> Works
            <input type="checkbox"> Broken
            
        </div>

        <div class="filter-group">
            <h4>Brand</h4>
            <select name="brand">
                <option>Intel</option>
                <option>Nvidia</option>
                <option>AMD</option>
            </select>
        </div>

        <div class="filter-group">
            <h4>Compatability Checker</h4>
            <input type="checkbox">
        </div>

        <div class="filter-group">
            <button type="button">Clear all Filters</button>
        </div>
</div>

<section class="search-result">
    <div class="result-header">
        <h3>
            <?php echo mysqli_num_rows($result); ?>
            Search Results for "RTX 5090"
        </h3>
        <select>
            <option>Sort: Low to High</option>
            <option>Sort: High to Low</option>
        </select>
    </div>

    <div class="card-row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card">
                <h3>PC Part #<?php echo $row['part_id']; ?></h3>
                <p>Price: $<?php echo $row['asking_price']; ?></p>
                <p>Condition: <?php echo $row['part_condition']; ?></p>
                <p>Location: <?php echo $row['location']; ?></p>
            </div>
        <?php } ?>
    </div>

</section>
</main>

<footer class="search-footer">&copy; 2026 PartMatch</footer>

<link rel="stylesheet" href="searchstyles.css">



</body>
</html>