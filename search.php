<?php
$conn = mysqli_connect("localhost", "root", "", "partmatch");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/*-----------------------------------Search-----------------------------------*/

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$sql = "SELECT * FROM listings WHERE True"; //There are multiple filters, so I just used true 

if ($search != "") {
    $sql .= " AND CAST(part_id AS CHAR) LIKE '%$search%'";  //Find matches for search that contains the input
}

/*-----------------------------------Price Filter-----------------------------------*/
if (isset($_GET['min_price']) && $_GET['min_price'] != ""){
    $min_price = (float) $_GET['min_price'];
    $sql .= " AND asking_price >= $min_price";
}

if (isset($_GET['max_price']) && $_GET['max_price'] != ""){
    $max_price = (float) $_GET['max_price'];
    $sql .= " AND asking_price <= $max_price";
}

/*-----------------------------------Condition Filter-----------------------------------*/

if (isset($_GET['condition']) && $_GET['condition'] != "") {

    $condition = mysqli_real_escape_string($conn, $_GET['condition']);

    $sql .= " AND part_condition = '$condition'";
}

/*-----------------------------------Location Filter-----------------------------------*/

if (isset($_GET['location']) && $_GET['location'] != "") {

    $location = mysqli_real_escape_string($conn, $_GET['location']);

    $sql .= " AND location LIKE '%$location%'";
}

/*-----------------------------------Sort-----------------------------------*/
if (isset($_GET['sort'])){
    if ($_GET['sort'] == "low"){
        $sql .= " ORDER BY asking_price ASC";
    }
    elseif($_GET['sort'] == "high"){
        $sql .= " ORDER BY asking_price DESC";
    }
}


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
    <link rel="stylesheet" href="style.css">   
</head>
 <!-------------------------------------Page Header!------------------------------------->
<body>
    <header class="search-header">
        <h1><a href="index.php" class="logo">PartMatch</a></h1>

        <form class="search" method="GET" action="search.php">
            <input type="search" name="search" placeholder="Search for PC parts (Hint: Type ID #)">
            <button type="submit">Search</button>
        </form>

        <nav>
            <a href="create_listing.php">Create Listing</a>
            <a href="profile.php">&emsp;Profile</a>
        </nav>

    </header>
 <!-------------------------------------Side Filter!------------------------------------->
<main class="search-main">
    <form class="search-filter" method="GET" action="search.php">
        <h3>Filters</h3>
<!---Filters out by price!-->
        <div class="filter-group">
            <h4>Price</h4>

            $<input type="number" name="min_price" min="0" placeholder="Min" value="<?php
                if (isset($_GET['min_price'])) {
                        echo htmlspecialchars($_GET['min_price']);
                } 
                else {
                  echo '';
                }
                ?>">

            $<input type="number" name="max_price"min="0" placeholder="Max"value="<?php
                if (isset($_GET['max_price'])) {
                    echo htmlspecialchars($_GET['max_price']);
                } 
                else {
                    echo '';
                }
                ?>">
        </div>
<!--Filters out by condition!-->
        <div class="filter-group">
            <h4>Condition</h4>

            <select name="condition">
                <option value="" <?php if (isset($_GET['condition']) && $_GET['condition'] == "") echo "selected";?>>All Conditions</option>

                <option value="New in Box" <?php if (isset($_GET['condition']) && $_GET['condition'] == "New in Box") echo "selected";?>>New in Box</option>

                <option value="Used" <?php if (isset($_GET['condition']) && $_GET['condition'] == "Used") echo "selected";
                ?>>Used</option>

                <option value="Broken" <?php
                    if (isset($_GET['condition']) && $_GET['condition'] == "Broken") echo "selected"; ?>>Broken</option>
            </select>
        </div>
<!--Filters out by Location!-->
        <div class="filter-group">
            <h4>Location</h4>
            <input type="text" name="location"
                placeholder="Enter city or state">
        </div>
<!--Filters out by Compatability (Does nothing at the moment)!-->
        <div class="filter-group">
            <h4>Compatability Checker</h4>
            <input type="checkbox">
        </div>
<!--Apply or clear filter!-->
        <div class="filter-group">
            <button type="submit">Apply Filters</button>
            <a href="search.php">Clear All Filters</a>
        </div>
</form>
 <!-------------------------------------Class Card Section!------------------------------------->
<section class="search-result">
<!--This part is the header section for the cards (right is the text that gives the text and left is the filtering part)!-->
    <div class="result-header">
        <h3>
            <?php echo mysqli_num_rows($result); ?>
            Search Results for
            <?php echo htmlspecialchars($search); ?>
        </h3>

<!--This code chunck focuses on sorting rather than filtering!-->
<form method="GET" action="search.php">

    <input type="hidden" name="search"
           value="<?php echo htmlspecialchars($search); ?>">

    <select name="sort" onchange="this.form.submit()">
        <option value="" <?php if (!isset($_GET['sort']) || $_GET['sort'] == "") echo "selected"; ?>>Sort: Default</option>

        <option value="low" <?php if (isset($_GET['sort']) && $_GET['sort'] == "low") echo "selected"; ?>>Sort: Low to High</option>

        <option value="high" <?php if (isset($_GET['sort']) && $_GET['sort'] == "high") echo "selected"; ?>>Sort: High to Low</option>
    </select>

</form>
    </div>
<!--Display card/pc parts with information!-->
    <div class="searchcard-row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="searchcard">
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
</body>
</html>