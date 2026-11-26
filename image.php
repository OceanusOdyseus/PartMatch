<?php
require_once "db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT image1_data FROM listings WHERE listing_id = ?");
$stmt->execute([$id]);
$image = $stmt->fetchColumn();

if (!$image) {
    http_response_code(404);
    exit;
}

while (ob_get_level()) { ob_end_clean(); }   // discard any stray output so only the photo is sent
header("Content-Type: image/jpeg");
echo $image;