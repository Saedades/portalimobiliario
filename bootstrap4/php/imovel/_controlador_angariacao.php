<?php
$inipath = php_ini_loaded_file();
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';



//----------------------DEBUG ECHOS---------------------------//
//echo '<pre>' . var_export($_POST, true) . '</pre>';
//echo '<pre>' . var_export($_FILES, true) . '</pre>';
//------------------------------------------------------------//




//------------------------------------------------------------//
// UPLOADS ONE FILE, IN VARIABLE ifile IF IS SET              //
//------------------------------------------------------------//
if(isset($_FILES['ifile_ang']['name']) AND strlen($_FILES['ifile_ang']['name'])>1) {



  //------------------------file info-------------------------//
  $path = $_FILES['ifile_ang']['name'];
  $ext = pathinfo($path, PATHINFO_EXTENSION);
	$target_dir_base = "../../docs/contracts/" . $_POST['domangid'] . "/";
	if( $_POST['type_of_contract'] ==1){
		$target_dir = $target_dir_base . "angariacoes";
	}
	elseif( $_POST['type_of_contract'] ==2) {
		$target_dir = $target_dir_base . "vendas";
	}
	//-------------------------------------------------------------//



  //----------------destionation file create---------------------//
  if (!file_exists($target_dir_base)) {
      mkdir($target_dir_base);
  }
  if (!file_exists($target_dir)) {
      mkdir($target_dir);
  }
  $target_file = $target_dir . "/" . $path;
	$counter = 0;
	while(file_exists($target_file)) {
		$counter++;
    $target_file = $target_dir . "/" . $counter . "_" . $path;
  }
  //-------------------------------------------------------------//



  //---------move ifile_ang content to new destination file----------//
	if (move_uploaded_file($_FILES['ifile_ang']['tmp_name'], $target_file)) {
		echo "File is valid, and was successfully uploaded.\n";
	} else {
		echo "Possible file upload attack!\n" . $_FILES["ifile_ang"]["error"];
	}
  //-------------------------------------------------------------//



  //----------------Insert file path in database-----------------//
  $aquery = 
  'INSERT INTO angariacoes_ficheiros(url, idtipoficheiro, idangariacoes, estado)  
  VALUES("' . $target_file . '",' . $_POST['ifiletype'] . ',' . $_POST['domangid'] . ', ' . $_POST['domestado']. ')';
	if( $conn->query($aquery) ) {
    echo "<h5>Succesfully inserted in DB";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	else {
    echo "<br><br>" . mysqli_error($conn);
    header('Location: ../partials/404.php');
  }
  //--------------------------------------------------------------//
//----------------------------------END OF FILE UPLOAD-------------------------------------//
}






//--------------------If is updation angariacao-------------------//
elseif (isset($_POST['domangid']) AND $_POST['domangid']!=0 AND !(isset($_POST['action_type']))) {
  echo "editar angariacao";
  $query = 'UPDATE angariacoes SET
  created_at = "' . $_POST['domdate'] . ' ' . $_POST['domtime'] . '",
  estado = ' . $_POST['domestado'] . ',
  risco = ' . $_POST['domrisco'] .'
  WHERE id = ' . $_POST['domangid'];
  if($conn->query($query)) {
    //echo "done";
  }
  else {
    //echo "failed";
  }
  header('Location: ' . $_SERVER['HTTP_REFERER'] . '&tab=4');
}


//--------------------If is creating angariacao-------------------//
elseif(isset($_POST['domangid']) AND $_POST['domangid']==0 AND !(isset($_POST['action_type']))) {
  echo "nova angariacao";
  $query = 'INSERT INTO angariacoes(created_at, estado, risco) VALUES(
 "' . $_POST['domdate'] . ' ' . $_POST['domtime'] . '",
  ' . $_POST['domestado'] . ',
  ' . $_POST['domrisco'] . ')';
  echo $query;
  $result = $conn->query($query);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}




//------------------------If is associating-----------------------//
elseif(isset($_POST['action_type'])) {
  switch($_POST['action_type']){


    case 10:  //------------------create new association between angariacao and imovel-------------------//
              $result = $conn->query(' SELECT * FROM angariacoes_imoveis WHERE idang=' . $_POST['id'] . ' AND idimo=' . $_POST['id_imovel']);
              if($result->num_rows==0) {
                $conn->query(' INSERT INTO angariacoes_imoveis(idang, idimo) VALUES(' . $_POST['id'] . ', ' . $_POST['id_imovel'] . ')');
                echo 1;
              }
              else {
                echo 2;
              }
              break;


    case 11:  //--------------------delete association between angariacao and imovel-----------------------//
              $conn->query(' DELETE FROM angariacoes_imoveis WHERE idang=' . $_POST['id'] . ' AND idimo=' . $_POST['id_imovel']); echo 1; break;
    
    
    case 30:  //-----------------create new association between angariacao and entidade--------------------//
              $result = $conn->query(' SELECT * FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
              if($result AND $result->num_rows==0) {
                $conn->query(' INSERT INTO angariacoes_entidades(idang, ident) VALUES(' . $_POST['id'] . ', ' . $_POST['id_entidade'] . ')');
                echo 1;
              }
              break;

              //--------------------delete association between angariacao and entidade---------------------//
    case 31:  $result = $conn->query(' SELECT * FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
              if($result AND $result->num_rows>0) {
                $conn->query(' DELETE FROM angariacoes_entidades WHERE idang=' . $_POST['id'] . ' AND ident=' . $_POST['id_entidade']);
                echo 1;
              }
              break;
  }
}
else {
  header('Location: ../partials/404.php');
}

?>
