<?php
require_once "db.php";
// Get the listing id from the address
$id = (int)($_GET["id"] ?? 0);

//Load this one listing with its part and seller
$stmt = $pdo->prepare(
    "SELECT l.listing_id, l.seller_id, l.asking_price, l.part_condition, l.status,
            l.location, l.description, l.date_time, p.name, p.category, p.brand,
            p.socket, p.memory_type, p.power_watts,
            u.username AS seller_name
     FROM listings l
     JOIN parts p ON p.part_id = l.part_id
     JOIN users u ON u.user_id = l.seller_id
     WHERE l.listing_id = ?");
$stmt->execute([$id]);
$listing = $stmt->fetch(); //false if id doesn't exist

//Get Contact: only fetch email/phone after the button is pressed
$contact = null;
if($listing && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["get_contact"])){
    $c = $pdo->prepare("SELECT email, phone FROM users WHERE user_id = ?");
    $c->execute([$listing["seller_id"]]);
    $contact = $c->fetch();
}
//Compatibility checker 
$compat = null;
if($listing){
    $build = get_build($pdo, CURRENT_USER_ID);
    if(build_is_empty($build)){
        $compat = null;
    }else{
        $compat = check_compat($listing, $build);
    }
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
        <a href="index.php" class="logo">PartMatch</a>
        <nav>
            <a href="search.php">Search</a>
            <a href="create_listing.php">Create Listing</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    

    <main class="non-search-main">
        <?php if (!$listing){?>
            <section class="listing-container">
                <h2>Listing not Found</h2>
                <p><a href="index.php">Back to listing</a></p>
            </section>
        
        <?php }else{ ?>
            <section class="listing-container">
                <h2><?php echo input($listing["name"]); ?></h2>
                <?php echo compat_notification($compat); ?>

                <p><strong>Category:</strong> <?php echo input($listing["category"]); ?></p>
                <p><strong>Brand:</strong> <?php echo input($listing["brand"]); ?></p>
                <p><strong>Price:</strong> $<?php echo number_format((float)$listing["asking_price"], 2); ?></p>
                <p><strong>Condition:</strong> <?php echo input(str_replace("-", " ", $listing["part_condition"])); ?></p>
                <p><strong>Location:</strong> <?php echo input($listing["location"]); ?></p>
                <p><strong>Seller:</strong> <?php echo input($listing["seller_name"]); ?></p>
                <p><strong>Status:</strong> <?php echo input($listing["status"]); ?></p>

                <?php if($listing["description"] !== "") { ?>
                    <p><?php echo input($listing["description"]); ?></p>
                <?php } ?>
                //contact button for get contact
                <?php if ($contact) { ?> 
                    <div class="contact-box">
                        <h3>Contact <?php echo input($listing["seller_name"]); ?></h3>
                        <p>Email: <?php echo input($contact["email"]); ?></p>
                        <p>Phone: <?php echo input($contact["phone"]); ?></p>
                    </div>
                <?php } else { ?>
                    <form method="POST" action="listing.php?id=<?php echo (int)$listing["listing_id"]; ?>">
                        <button type="submit" name="get_contact" value="1">Get Contact</button>
                    </form>
                <?php } ?>
                
                <p><a href="index.php">Back to listings</a></p>
            </section>
        <?php } ?>   
    </main>

    <footer>
        <p>&copy; 2026 PartMatch</p>
    </footer>
</body>
</html>

