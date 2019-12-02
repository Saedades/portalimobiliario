<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


print_r($_POST);
print_r($_FILES);

if(isset($_FILES['ifile']['name']) AND strlen($_FILES['ifile']['name'])>1) {

    echo "<br>SET<br>";
    $path = $_FILES['ifile']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);


    //-------------------------------------------------------------//
    echo "<h3>".$path."</h3>";

    $target_dir_base = "../../docs/contracts/" . $_POST['idimo'] . "/";

    echo "<h3>".$target_dir_base."</h3>";

    if( $_POST['type_of_contract'] ==1){
        $target_dir = $target_dir_base . "angariacoes";
    }
    elseif( $_POST['type_of_contract'] ==2) {
        $target_dir = $target_dir_base . "vendas";
    }

    echo "<h3>". $target_dir ."</h3>";
    //-------------------------------------------------------------//


    if (!file_exists($target_dir_base)) {
        mkdir($target_dir_base);
    }
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }

    $target_file = $target_dir . "/" . $path;
    $counter = 0;
    while(file_exists($target_file)) {
        echo "exists";
        $counter++;
        $target_file = $target_dir . "/" . $counter . "_" . $path;
    }


    echo "<h3>TARGET FILE: ". $target_file ."</h3>";

    if (move_uploaded_file($_FILES['ifile']['tmp_name'], $target_file)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n" . $_FILES["ifile"]["error"];
    }


    $aquery = 'INSERT INTO imoveis_ficheiros(url, tipo, imovel)  VALUES("' . $target_file . '",' . $_POST['ifiletype'] . ',' . $_POST['idimo'] . ')';
    echo "<br>" . $aquery;
    if( $conn->query($aquery) ) {
        echo "<h5>Succesfully inserted in DB";
        header('Location: imovel.php?id='. $_POST['idimo'] . '&tab=2');
    }
    else {
        echo "<br><br>" . mysqli_error();
        header('Location: ../partials/404.php');
    }
}
?>
