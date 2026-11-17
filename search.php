<?php
require_once "db.php";

//Read the search and filter values from the address
$q = trim($_GET["q"] ?? "");
$category =($_GET["category"] ?? "");
$condition =($_GET["condition"] ?? "");
$brand =($_GET["brand"] ?? "");
$min =($_GET["min"] ?? "");
$max =($_GET["max"] ?? "");
$sort =($_GET["sort"] ?? "");
$onlyCompat = isset($_GET["compat"]);

//dropdown for brand
$brands = $pdo->query("SELECT DISTINCT brand FROM parts ORDER BY brand")->fetchAll(PDO::FETCH_COLUMN);

//Build the WHERE part one filter at a time
$where = ["l.status = 'available'"];
$params = [];

if ($q !== ""){
    $where[] = "(p.name LIKE ? OR p.brand LIKE ?)";
    $params[] = "%". $q ."%";
    $params[] = "%". $q ."%";
}

if (in_array($category, CATEGORIES, true)){
    $where[] = "p.category = ?";
    $params[] = $category;
}  else{
    $category = "";
}

if(array_key_exists($condition, CONDITIONS)){
    $where[] = "l.part_condition = ?";
    $params[] = $condition;
}else{
    $condition = "";
}

if(in_array($brand, $brands, true)){
    $where[] = "p.brand = ?";
    $params[] = $brand;
}else{
    $brand ="";
}

if(is_numeric($min)){
    $where[] = "l.asking_price >= ?";
    $params[] = $min;
}

if(is_numeric($max)){
    $where[] = "l.asking_price <= ?";
    $params[] = $max;
}

//Sorting: only values from this list 
$sorts = ["low" => "l.asking_price ASC", "high" => "l.asking_price DESC"];
$orderBy = $sorts[$sort] ?? "l.date_time DESC";

$sql = "SELECT l.listing_id, l.asking_price, l.part_condition, l.location,
            p.name, p.category, p.brand, p.socket, p.memory_type, p.power_watts
        FROM listings l
        JOIN parts p ON p.part_id = l.part_id
        WHERE " . implode(" AND ", $where) . "
        ORDER BY " . $orderBy;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

//Compatability checker
$build = get_build($pdo, CURRENT_USER_ID);
$hasBuild = !build_is_empty($build);

$results = [];
foreach($rows as $row){
    $row["compat"] = $hasBuild ? check_compat($row, $build): null;

    //"Compatability checker box checked
    if($onlyCompat && $row["compat"] !== true){
        continue;
    }
    $results[] = $row;
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
<body>
    <header class="search-header">
        <h1><a href="index.php" class="logo">PartMatch</a></h1>

        <form class="search" method="GET" action="search.php">
            <input type="search" name="q" value="<?php echo input($q); ?>" placeholder="Search for PC parts">
            <button type="submit">Search</button>
        </form>

        <nav>
            <a href="create_listing.php">Create Listing</a>
            <a href="profile.php">&emsp;Profile</a>
        </nav>

    </header>

<main class="search-main">
    <form id="filterform" class="search-filter" method="GET" action="search.php">
        <h3>Filters</h3>

        <!-- keep the current search text when filters are used -->
        <input type="hidden" name="q" value="<?php echo input($q); ?>">

        <div class="filter-group">
            <h4>Categories</h4>
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach (CATEGORIES as $c){ ?>
                    <option value="<?php echo input($c); ?>" <?php echo $category === $c ? "selected" : ""; ?>>
                        <?php echo input($c); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="filter-group">
            <h4>Price</h4>
            $<input type="number" name="min" min=0 placeholder="min" value="<?php echo input($min); ?>">
            $<input type="number" name="max" min=0 placeholder="max" value="<?php echo input($max); ?>">
        </div>

        <div class="filter-group">
            <h4>Condition</h4>
            <select name="condition">
                <option value="">Any Condition</option>
                <?php foreach (CONDITIONS as $value => $label){ ?>
                    <option value="<?php echo input($value); ?>" <?php echo $condition === $value ? "selected" : ""; ?>>
                        <?php echo input($label); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="filter-group">
            <h4>Brand</h4>
            <select name="brand">
                <option value="">Any Brand</option>
                <?php foreach ($brands as $b){ ?>
                    <option value="<?php echo input($b); ?>" <?php echo $brand === $b ? "selected" : ""; ?>>
                        <?php echo input($b); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="filter-group">
            <h4>Compatability Checker</h4>
            <label>
                <input type="checkbox" name="compat" value="1" <?php echo $onlyCompat ? "checked" : ""; ?>>
                Only parts that fit my build
            </label>
        </div>

        <div class="filter-group">
            <button type="submit">Apply Filters</button>
            <a class="clear-button" href="search.php">Clear all Filters</a>
        </div>
    </form>


<section class="search-result">
    <div class="result-header">
        <h3>
            <?php echo count($results); ?> Search Results
            <?php if($q !== "") {?> for "<?php echo input ($q); ?>"<?php } ?>
        </h3>

        <!-- this is select is for the filter form and this is sent with the filters -->
        <select name="sort" form="filterform" onchange="this.form.submit()">
            <option value="">Sort: Newest</option>
            <option value ="low" <?php echo $sort === "low" ? "selected" : ""; ?>>Sort: Low to High</option>
            <option value ="high" <?php echo $sort === "high" ? "selected" : ""; ?>>Sort: High to Low</option>
        </select>
    </div>

    <div class="searchcard-row">
        <?php if (count($results) === 0){ ?>
            <p>No listing match your search.</p>
        <?php } ?>

        <?php foreach ($results as $row) { ?>
            <a class="searchcard" href="listing.php?id=<?php echo (int)$row["listing_id"]; ?>">
                <h3><?php echo input($row["name"]); ?></h3>
                <?php echo compat_notification($row["compat"]); ?>
                <p>Price: $<?php echo number_format((float)$row["asking_price"],2); ?></p>
                <p>Condition: <?php echo input(condition_label($row["part_condition"])); ?></p>
                <p>Location: <?php echo input($row['location']); ?></p>
            </a>
        <?php } ?>
    </div>

</section>
</main>

<footer class="search-footer">&copy; 2026 PartMatch</footer>




</body>
</html>