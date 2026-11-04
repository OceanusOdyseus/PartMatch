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
        <h3>5 Search Results for "RTX 5090"</h3>
        <select>
            <option>Sort: Low to High</option>
            <option>Sort: High to Low</option>
        </select>
    </div>

    <div class="card-row">
        <div class="card">
            <h3>Photo</h3>
            <p>Name: 4070 ti</p>
            <p>Price:</p>
            <p>Condition:</p>
            <p>Compatable</p>
        </div>

        <div class="card">
            <h3>Photo</h3>
            <p>Name: 4070 super</p>
            <p>Price:</p>
            <p>Condition:</p>
            <p>Not Compatable</p>
        </div>

        <div class="card">
            <h3>Photo</h3>
            <p>Name: 4070</p>
            <p>Price:</p>
            <p>Condition:</p>
            <p>Compatable</p>
        </div>
    </div>

</section>
</main>

<footer class="search-footer">&copy; 2026 PartMatch</footer>

<link rel="stylesheet" href="searchstyles.css">



</body>
</html>