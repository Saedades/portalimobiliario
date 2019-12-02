<?php
$inipath = php_ini_loaded_file();

require_once ('../partials/connectDB.php');
require_once ('../partials/validate_session.php');

$login = $_SESSION['login'];
echo '<pre>' . var_export($_POST, true) . '</pre>';
echo '<pre>' . var_export($_FILES, true) . '</pre>';



// ----------------------------- profile pic upload --------------------------- //
if(isset($_FILES["subject_file"]["tmp_name"]) AND strlen($_FILES["subject_file"]["tmp_name"])>1 AND isset($_POST['subjectid']) AND $_POST['subjectid']!=0) {
    echo "picture is set!<br><br>";
    $path = $_FILES['subject_file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $target_dir = "../../img/uploads/imoveis/" . $_POST['subjectid'];
    if (!file_exists($target_dir)) {
        $test_make = mkdir($target_dir);
		if($test_make)
		{
			echo "<br>Directory created! " . $target_dir . "<br>";
		}
		else {
			$error = error_get_last();
    		echo $error['message'];
		}
    }

    $counter = 0;
    $target_file = $target_dir . "/" . $path;
	echo "<h2>Target file: <br> " . $target_file . "</h2>";
    $check = getimagesize($_FILES["subject_file"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
	echo "<h5>Filesize: ".print_r($check,true)."</h5>";
    if ($uploadOk) {
		echo "<h2>File upload size is ok</h2>";
        if (move_uploaded_file($_FILES['subject_file']['tmp_name'], $target_file)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n" . $_FILES["file"]["error"];
        }
    }

	$urldb = $_POST['subjectid'] . "/" . substr($target_file, strrpos($target_file, "/")+1, strlen($target_file));



	$aquery = 'INSERT INTO imoveis_fotos(imovel, url) VALUES(' . $_POST['subjectid'] . ',"' . $urldb . '")';
	echo "<br>" . $aquery;
    if( $conn->query($aquery) ) {
		echo "<h5>Succesfully inserted in DB";
	}
	else {
		echo "<br><br>" . mysqli_error($conn);
    }
	header('Location: imovel.php?id='.$_POST['subjectid']);
} elseif ($_POST['subjectid']!=0) {
	header('Location: imovel.php?id='.$_POST['subjectid']);
}
else {
    echo "error";
	header('Location: imovel.php');
}
?>
