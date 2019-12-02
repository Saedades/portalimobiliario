<?php
$inipath = php_ini_loaded_file();
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

if(isset($_POST['action_type'])) {

  switch($_POST['action_type']){
    case 90:
      if(isset($_POST['idimovel']) AND $_POST['idimovel']!=0) {
        $exists = $conn->query('SELECT * FROM angariacoes_imoveis WHERE idimo=' . $_POST['idimovel']);
        if(!$exists OR $exists->num_rows==0) {
          $conn->query('INSERT INTO angariacoes(iduser) VALUES(' . $_SESSION['login'] . ')');
          $last_id = $conn->insert_id;
          $conn->query('INSERT INTO angariacoes_imoveis(idang, idimo) VALUES(' . $last_id . ',' . $_POST['idimovel'] . ')');
          echo $last_id;
        }
        elseif($exists) {
          echo $exists->fetch_assoc()['idang'];
        }
      }
  }

}

?>
