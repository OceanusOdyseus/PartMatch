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
        </section>

        <section class="recentListings">
            <h2>Recent Listings</h2>
            <div class="listingContainer">
            
                <article class="listing">
                <h3>RTX 4070</h3>
                <p>Price: $450</p>
                <p>Condition: Used</p>
                </article>

                <article class="listing">
                <h3>16GB DDR5 RAM</h3>
                <p>Price: $150</p>
                <p>Condition: Used</p>
                </article>

                <article class="listing">
                <h3>Ryzen 7 7800x3d</h3>
                <p>Price: $400</p>
                <p>Condition: Used</p>
                </article>
            </div>
        </section>

        <footer>
        <p>&copy; 2026 PartMatch</p>
        </footer>
    </main>
</body>
</html>

