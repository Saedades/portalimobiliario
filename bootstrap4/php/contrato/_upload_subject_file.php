<?php
//ifiletype
//ifile
$inipath = php_ini_loaded_file();
require_once ('connectDB.php');
session_start();
$login = $_SESSION['login'];
echo '<pre>' . var_export($_POST, true) . '</pre>';
echo '<pre>' . var_export($_FILES, true) . '</pre>';



// ----------------------------- profile pic upload --------------------------- //
if(isset($_FILES['ifile']['name']) AND strlen($_FILES['ifile']['name'])>1) {

    echo "<br>SET<br>";
    $path = $_FILES['ifile']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);


	//-------------------------------------------------------------//
	echo "<h3>".$path."</h3>";

	$target_dir_base = "../docs/subjects/" . $_POST['subject_id'] . "/";

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


	$aquery = 'INSERT INTO angariacoes_ficheiros(url, idtipoficheiro, idangariacoes, adicionado_em, estado)  VALUES("' . $target_file . '",' . $_POST['ifiletype'] . ',' . $_POST['subject_id'] . ',"' . $_SERVER['REQUEST_TIME'] . '", 1)';
	echo "<br>" . $aquery;
	if( $conn->query($aquery) ) {
		echo "<h5>Succesfully inserted in DB";
	}
	else {
		echo "<br><br>" . mysqli_error($conn);
	}
	//header('Location: ../subjects.php?idsubject='.$_POST['subjectid']);
}
elseif ($_POST['domangid']!=0) {
	echo "editar angariacao";
}
else {
	echo "error";
	header('Location: ../partials/404.php');
}

?>
