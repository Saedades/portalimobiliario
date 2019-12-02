<?php
//ifiletype
//ifile
$inipath = php_ini_loaded_file();
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
$login = $_SESSION['login'];
//echo '<pre>' . var_export($_POST, true) . '</pre>';
//echo '<pre>' . var_export($_FILES, true) . '</pre>';





if(isset($_FILES['ifile']['name']) AND strlen($_FILES['ifile']['name'])>1) {

    echo "<br>SET<br>";
    $path = $_FILES['ifile']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);


	//-------------------------------------------------------------//
	echo "<h3>".$path."</h3>";

	$target_dir_base = "../../docs/contracts/" . $_POST['domangid'] . "/";

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


	$aquery = 'INSERT INTO angariacoes_ficheiros(url, idtipoficheiro, idangariacoes, estado)  VALUES("' . $target_file . '",' . $_POST['ifiletype'] . ',' . $_POST['domangid'] . ', ' . $_POST['domestado']. ')';
	echo "<br>" . $aquery;
	if( $conn->query($aquery) ) {
    echo "<h5>Succesfully inserted in DB";
    header('Location: angariacao.php?id='. $_POST['domangid']);
	}
	else {
    echo "<br><br>" . mysqli_error($conn);
    header('Location: ../partials/404.php');
	}
	//header('Location: ../subjects.php?idsubject='.$_POST['subjectid']);
}
elseif (isset($_POST['domangid']) AND $_POST['domangid']!=0 AND !(isset($_POST['action_type']))) {
  echo "editar angariacao";
  /*
  'domangid' => '1',

  'domuserid' => '4',
  'domdate' => '2019-07-09',
  'domtime' => '16:14',
  'domestado' => '1',
  */

  $query = 'UPDATE angariacoes SET
  created_at = "' . $_POST['domdate'] . ' ' . $_POST['domtime'] . '",
  estado = ' . $_POST['domestado'] . ',
  risco = ' . $_POST['domrisco'] .'
  WHERE id = ' . $_POST['domangid'];
  echo $query;
  $conn->query($query);


  //header('Location: angariacao.php?id=' . $_POST['domangid']);
}
elseif(isset($_POST['domangid']) AND $_POST['domangid']==0 AND !(isset($_POST['action_type']))) {
	echo "nova angariacao";
	//header('Location: ../subjects.php');
}
elseif(isset($_POST['action_type'])) {
  switch($_POST['action_type']){
    case 10:  $result = $conn->query(' SELECT * FROM angariacoes_imoveis WHERE idang=' . $_POST['id'] . ' AND idimo=' . $_POST['id_imovel']);
              if($result->num_rows==0) {
                $conn->query(' INSERT INTO angariacoes_imoveis(idang, idimo) VALUES(' . $_POST['id'] . ', ' . $_POST['id_imovel'] . ')');
                echo 1;
              }
              else {
                echo 2;
              }
              break;
    case 11:  $conn->query(' DELETE FROM angariacoes_imoveis WHERE idang=' . $_POST['id'] . ' AND idimo=' . $_POST['id_imovel']); echo 1; break;
    case 30:  $result = $conn->query(' SELECT * FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
              if($result AND $result->num_rows==0) {
                $conn->query(' INSERT INTO angariacoes_entidades(idang, ident) VALUES(' . $_POST['id'] . ', ' . $_POST['id_entidade'] . ')');
                echo 1;
              }
              break;
    case 31:  $result = $conn->query(' SELECT * FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
              if($result AND $result->num_rows>0) {
                $conn->query(' DELETE FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
                echo 1;
              }
              break;
  }
}else {

  header('Location: ../partials/404.php');

}

?>
