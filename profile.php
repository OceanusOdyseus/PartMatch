<?php
require_once "db.php";

$message = "";

//slots for build options label and part category
$slots = [
    "cpu_part_id" => ["label" => "CPU",                "category" => "CPU"],
    "gpu_part_id" => ["label" => "GPU",                "category" => "GPU"],
    "motherboard_part_id" => ["label" => "Motherboard","category" => "Motherboard"],
];
//parts grouoed by category, this is used for dropdowns 
$partsByCategory = [];
$partStmt = $pdo->query("SELECT part_id, name, category FROM parts ORDER BY name");
foreach($partStmt->fetchAll() as $part){
    $partsByCategory[$part["category"]][(int)$part["part_id"]] = $part["name"]; //builds a two level array with the first key being category and second key is part id
}

//save the build of the user
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_build"])){
    $values = [];
    $valid = true;

    foreach($slots as $field => $slot){
        $raw = $_POST[$field] ?? "";
        if($raw === ""){
            $values[$field] = null;     //"None" selected
        }elseif (isset($partsByCategory[$slot["category"]][(int)$raw])){
            $values[$field] = (int)$raw;    //real part of the right category
        }else{
            $valid = false;
        }
    }

    if (!$valid){
        $message = "Please choose parts from the lists.";
    }else{
        $stmt = $pdo->prepare(
            "INSERT INTO user_builds (user_id, cpu_part_id, gpu_part_id, motherboard_part_id)
            VALUES (?,?,?,?)
            ON DUPLICATE KEY UPDATE
                cpu_part_id = VALUES(cpu_part_id),
                gpu_part_id = VALUES(gpu_part_id),
                motherboard_part_id = VALUES(motherboard_part_id) "
        );
        $stmt->execute([
            CURRENT_USER_ID,
            $values["cpu_part_id"],
            $values["gpu_part_id"],
            $values["motherboard_part_id"],
        ]);
        $message = "Your build was saved";
    }
}

//load the user data
$userStmt = $pdo->prepare("SELECT username, email, location FROM users WHERE user_id = ?");
$userStmt->execute([CURRENT_USER_ID]);
$user = $userStmt->fetch();

//load the saved build
$buildStmt = $pdo->prepare("SELECT cpu_part_id, gpu_part_id, motherboard_part_id FROM user_builds WHERE user_id = ?");
$buildStmt->execute([CURRENT_USER_ID]);
$build = $buildStmt->fetch();
if(!$build){
$build = ["cpu_part_id" => null, "gpu_part_id" => null, "motherboard_part_id" => null];
}

//load this user's listings
$myStmt = $pdo->prepare(
    "SELECT l.listing_id, l.asking_price, l.status, p.name
    FROM listings l
    JOIN parts p ON p.part_id = l.part_id
    WHERE l.seller_id = ?
    ORDER BY l.date_time DESC"
);
$myStmt->execute([CURRENT_USER_ID]);
$myListings = $myStmt->fetchAll();

//checks compatability with the inputted with cpu and motherboard
$buildParts = get_build($pdo, CURRENT_USER_ID);
$buildNote = "";

//checks compatability of parts that users picked
if($buildParts["cpu"] && $buildParts["mb"]){
    if(check_compat($buildParts["cpu"], $buildParts)){
        $buildNote = "Your build is Compatible.";
    }else {
        $buildNote = "Warnings: your CPU and motherboard are not compatible, Compatability Checker may not work.";
    }
}

//Mark one of the listing as sold
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["mark_sold"])){
    $stmt = $pdo ->prepare("UPDATE listings SET status = 'sold' WHERE listing_id = ? AND seller_id =?");
    $stmt->execute([(int)$_POST["mark_sold"], CURRENT_USER_ID]);
    $message = "Listing marked as sold.";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="profile-body">

    <header class="profile-header">
        <h1><a href="index.php">PartMatch</a></h1>

        <nav>
            <a href="search.php">&emsp;Search</a>
            <a href="create_listing.php">&emsp;Create Listing</a>
        </nav>
    </header>

    <div class="profile-row">

        <div class="profile-left">
            <i class="fa-solid fa-circle-user profile-icon"></i>
            <h3><?php echo $user ? input($user["username"]): "Unknown user"; ?></h3>
        </div>

        <div class="profile-right">

            <h2>Personal Information</h2>
            
            <?php if($message !== ""){ ?>
                <p class="message"><?php echo input($message); ?></p>
            <?php } ?>

            <?php if($user){ ?>
                <div class="profile-info">
                    <h3>Email</h3>
                    <p><?php echo input($user["email"]); ?></p>
                </div>

            <div class="profile-info">
                <h3>Location</h3>
                <p><?php echo input($user["location"]); ?></p>
            </div>
        <?php } ?>
            <div class="profile-info">
                <h3>My Build</h3>
                <p>Save your parts. Listings will show compatibility</p>

                <?php if($buildNote !== ""){ ?>
                    <p class="message"><?php echo input($buildNote); ?></p>
                <?php }?>

                <form action="profile.php" method="POST">
                    <?php foreach($slots as $field => $slot){ ?>
                        <div class="form-group">
                            <label for="<?php echo input($field); ?>"><?php echo input($slot["label"]); ?>:</label>

                            <select id="<?php echo input($field); ?>" name="<?php echo input($field); ?>">
                                <option value="">None</option>

                                <?php foreach ($partsByCategory[$slot["category"]] ?? [] as $partId => $partName){ ?>
                                    <option value="<?php echo $partId; ?>"
                                        <?php echo (string)$build[$field] === (string)$partId ? "selected" : ""; ?>>
                                        <?php echo input($partName); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                        
                    <button type="submit" name ="save_build" value="1">Save Build</button>
                </form>        
            </div>

            <div class="profile-info">
                <h3>Current Listings</h3>

                <?php if(count($myListings) === 0) { ?>
                    <p> No current listings. </p>
                <?php } else{ ?>
                    <?php foreach ($myListings as $row){ ?>
                        <p> 
                            <a class ="my-listing" href="listing.php?id=<?php echo (int)$row["listing_id"]; ?>">
                                <?php echo input($row["name"]); ?>
                            </a>
                            - $<?php echo number_format((float)$row["asking_price"], 2); ?>
                            (<?php echo input(ucfirst($row["status"])); ?>)
                        </p>
                        <?php if($row["status"] === "available") { ?>
                            <form action="profile.php" method="POST">
                                <button type="submit" name ="mark_sold" value="<?php echo (int)$row["listing_id"]; ?>">Mark as sold</button>
                            </form>    
                        <?php } ?>   
                    <?php } ?>
                <?php } ?>
            </div>

        </div>

    </div>

    <footer class="search-footer">&copy; 2026 PartMatch</footer>

</body>
</html>
