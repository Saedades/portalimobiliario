<?php

require_once ('connectDB.php');
session_start();
$login = $_SESSION['login'];

$path = $_FILES['profpic']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

$target_dir = "uploads/";
if(!file_exists($target_dir)) {
    mkdir($target_dir);
}

$counter=0;
$target_file = $target_dir . 'profile' . '.' . $ext;
while(file_exists($target_file)) {
    $target_file = $target_dir . 'profile' . $counter . '.' . $ext;
    $counter++;
}

$check = getimagesize($_FILES["profpic"]["tmp_name"]);
if($check !== false) {
    $uploadOk = 1;
} else {
    $uploadOk = 0;
}

if($uploadOk) {
    if (move_uploaded_file($_FILES['profpic']['tmp_name'], $target_file)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }
}

$conn->query("UPDATE users SET profile='" . $target_file . "' WHERE idusers=" . $login);
header('Location: ../profile.php');


?>