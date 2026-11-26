
<?php

require_once "db.php";

$message = "";
//getting parts for the dorpdown list for part pick
$parts = $pdo->query("SELECT part_id, name, category FROM parts ORDER BY category, name")->fetchALL();
$validPartIds = array_column($parts, "part_id");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Checks if the form is too big and will throw away everything if it is
    $sizeCheck = empty($_POST) && empty($_FILES) && (int)($_SERVER["CONTENT_LENGTH"] ?? 0) > 0;

    $seller_id = CURRENT_USER_ID;
    $part_id = trim($_POST["part_id"] ?? "");
    $asking_price = $_POST["asking_price"] ?? "";
    $part_condition = $_POST["part_condition"] ?? "";
    $location = trim($_POST["location"] ?? "");
    $description = trim($_POST["description"] ?? "");

    //checks for the uploaded photo
    $imageError = "";
    $imageExt = "";
    $hasImage = isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE;
    
    if(!$hasImage){
        $imageError = "Please add a photo of the part.";
    }else{
        $f = $_FILES["image"];
        $allowed = ["image/jpeg" => "jpg"];

        if($f["error"] !== UPLOAD_ERR_OK || $f["size"] > 1024 * 1024){
            $imageError = "The photo must be 1 MB or smaller.";
        }else{
            //checks the file type and not by the file name the user sent
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f["tmp_name"]);
            if(isset($allowed[$mime])){
                $imageExt = $allowed[$mime];
            }else{
                $imageError = "The photo must be a JPG image.";
            }
        }
    }


    //Added limit for how many listings I user can make
    $recent = $pdo->prepare( "SELECT COUNT(*) FROM listings WHERE seller_id = ? AND date_time > NOW() - INTERVAL 1 HOUR");
    $recent->execute([$seller_id]);
    $tooMany = $recent->fetchColumn() >= 5;
    if($sizeCheck){
        $message = "that upload is too large. Try again";
    }
    elseif($tooMany){
        $message = "You have reached the limit of 5 new listing per hour";
    }
    elseif($imageError !== ""){
        $message = $imageError;
    }
    elseif (
        !in_array($part_id, $validPartIds) ||
        !is_numeric($asking_price) ||
        $asking_price < 0 ||        
        !array_key_exists($part_condition, CONDITIONS) ||
        $location === "" ||
        strlen($location) > 50 ||
        strlen($description) > 100
    ) {

        $message = "Please enter valid information in all fields.";

    } else {
        //Randomly generated names for images
        $imageName = bin2hex(random_bytes(16)) . "." . $imageExt;
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

        $sql = "INSERT INTO listings
            (seller_id, part_id, asking_price, part_condition, status, location, description, image1_name, image1_data)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $seller_id,
                $part_id,
                $asking_price,
                $part_condition,
                "available",
                $location,
                $description,
                $imageName,
                $imageData
            ]);
            $message = "Listing successfully created!";

        } catch (PDOException $e) {
            if(stripos($e->getMessage(), "max_allowed_packet") !== false){
                $message = "That photo is too large for the database. Try a smaller one";
            }else{
                $message = "Error creating listing: " . $e->getMessage();
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Listing - PartMatch</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>


    <header>
        <h1><a href="index.php" class="logo">PartMatch</a></h1>

        <nav>
            <a href="index.php">Home</a>
            <a href="search.php">Search</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <main class="non-search-main">

        <section class="listing-container">

            <h2>Create a Listing</h2>

            <p>Enter your PC part information below.</p>

            <?php if ($message !== ""): ?>

                <p class="message">
                    <?php echo input($message); ?>
                </p>

            <?php endif; ?>

            <form action="create_listing.php" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="part_id">Part ID:</label>

                    <select id="part_id" name="part_id" required>
                        <option value="">Select a part</option>
                        <?php foreach ($parts as $p){ ?>
                            <option value="<?php echo (int)$p['part_id']; ?>">
                                <?php echo input($p['category'] . " - ". $p['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="asking_price">Asking Price:</label>

                    <input
                        type="number"
                        id="asking_price"
                        name="asking_price"
                        placeholder="Enter price"
                        min="0"
                        step="0.01"
                        required>
                </div>

                <div class="form-group">
                    <label for="part_condition">Condition:</label>

                    <select id="part_condition" name="part_condition" required>
                        <option value="">Select condition</option>
                        <option value="new-in-box">New in Box</option>
                        <option value="used">Used</option>
                        <option value="broken">Broken</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>

                    <input
                        type="text"
                        id="location"
                        name="location"
                        placeholder="Enter city and state"
                        required>
                </div>

                <div class="form-group">
                    <label for="description">Description (optional):</label>

                    <input
                        type="text"
                        id="description"
                        name="description"
                        maxlength ="100"
                        placeholder="Example: Works well, no issues">
                </div>

                <div class="form-group">
                    <label for="image">Photo (JPG only, 1 MB max):</label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/jpeg"
                        required>
                </div>

                <div class="publish-button">
                    <button type="submit">Publish Listing</button>
                </div>

            </form>

        </section>

    </main>

    <footer>
        <p>&copy; 2026 PartMatch</p>    
    </footer>

</body>
</html>
