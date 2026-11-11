
<?php

$host = "localhost";
$dbname = "partmatch";
$username = "root";
$password = "";

define("CURRENT_USER_ID", 104);

const CATEGORIES = ["CPU", "GPU", "Motherboard", "RAM", "PSU", "Storage"];
const CONDITIONS = ["new-in-box" => "New in Box", "used" => "Used", "broken" => "Broken"];

function input($value): string{ //Prevent HTML injections by filtering text
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function condition_label($value): string{ //take value from condition arrays and return string
    return CONDITIONS[$value] ?? (string)$value;
}
try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}
//Get user's build to check with compatability checker and returns as an array
function get_build(PDO $pdo, int $userID): array{
    $build = ["cpu" => null, "gpu" => null, "mb" => null];//build array to prepare for getting parts
    $stmt = $pdo->prepare("SELECT cpu_part_id, gpu_part_id, motherboard_part_id FROM user_builds WHERE user_id = ?");
    $stmt->execute([$userID]);
    $row = $stmt->fetch();//saves part id in $row
    if(!$row){ //checks if row is empty then leaves if it is empty
        return $build;
    }

    $columns = ["cpu" => "cpu_part_id", "gpu" => "gpu_part_id", "mb" => "motherboard_part_id",];
    $partStmt = $pdo->prepare("SELECT * FROM parts WHERE part_id = ?"); //prepare to give parts from part id
    foreach($columns as $key => $column){ //go through all part id from cpu, gpu and mb
        if($row[$column] !== null){
            $partStmt->execute([$row[$column]]);
            $build[$key] = $partStmt->fetch() ?: null; //saves the parts but if there is nothing saves as null
        }
    }
    return $build;
}

function build_is_empty(array $build): bool{
    return !$build["cpu"] && !$build['gpu'] && !$build["mb"];
}

function check_compat(array $item, array $build): ?bool{
    if($item["category"] === "CPU"){
        if($build["mb"]){
            return $item["socket"] === $build["mb"]["socket"];
        }else{
            return null;
        }
    }elseif($item["category"] === "Motherboard"){
        if($build["cpu"]){
            return $item["socket"] === $build["cpu"]["socket"];
        }else{
            return null;
        }

    }elseif($item["category"] === "RAM"){
        if($build["mb"]){
            return $item["memory_type"] === $build["mb"]["memory_type"];    
        }else{
            return null;
        }

    }elseif($item["category"] === "PSU"){
        if($build["gpu"]){
            return (int)$item["power_watts"] >= (int)$build["gpu"]["power_watts"];
        } else{
            return null;
        }
    }else{
        return null;
    }   
}

function compat_notification(?bool $compatible): string{
    if($compatible === true){
        return '<span class= "compatNoti compatNoti-ok">Compatible with your build</span>';
    }
    if($compatible === false){
        return '<span class= "compatNoti compatNoti-no">Not compatible with your build</span>';
    }
    return "";
}

?>
