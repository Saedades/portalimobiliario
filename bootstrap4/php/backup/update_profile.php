<?php
require_once ('connectDB.php');
session_start();
$login = $_SESSION['login'];
print_r($_POST);
print_r($_FILES);


// ----------------------------- profile pic upload --------------------------- //
if(isset($_FILES["profpic"]["tmp_name"]) AND strlen($_FILES["profpic"]["tmp_name"])>1) {
    echo "picture is set!";
    $path = $_FILES['profpic']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }
    $counter = 0;
    $target_file = $target_dir . 'profile' . '.' . $ext;
    /*while (file_exists($target_file)) {
        $target_file = $target_dir . 'profile' . $counter . '.' . $ext;
        $counter++;
    }*/
    $target_file = $target_dir . 'profile-' . $login . '.' . $ext;
    $check = getimagesize($_FILES["profpic"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
    if ($uploadOk) {
        if (move_uploaded_file($_FILES['profpic']['tmp_name'], $target_file)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
    }
    $conn->query("UPDATE users SET profile='" . $target_file . "' WHERE idusers=" . $login);
} // ---------------------------------------------------------------------------- //

if(isset($_POST["domname"]) ) {
    $conn->query("UPDATE users SET name='" .  $_POST["domname"] . "' WHERE idusers=" . $login);
}

if(isset($_POST["domemail"]) ) {
    $conn->query("UPDATE email SET name='" .  $_POST["domemail"] . "' WHERE idusers=" . $login);
}

if(isset($_POST["domactive"]) ) {
    $conn->query("UPDATE status SET name='" .  $_POST["domactive"] . "' WHERE idusers=" . $login);
}

header('Location: ../profile.php')
?>
