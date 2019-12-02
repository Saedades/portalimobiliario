<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';


//-----------------------INPUTS-------------------------//
//  $contato_id
//  $entidade_id
//  $imovel_id
//  $title
//  $date
//  $hour
//  $description
//  $title_top
//------------------------------------------------------//


if(isset($_GET['id'])) {

    if($ocontato = $conn->query("SELECT * FROM contatos WHERE id =" . $_GET['id'])->fetch_assoc()) {
      $contato_id   =   (isset($_GET['id']))          ?    $_GET['id']              : 0;
    }

}
else {
  $contato_id   =   (isset($ocontato['id']))          ?    $ocontato['id']          : 0;
}

    
    $title        =   (isset($ocontato['titulo']))      ?    $ocontato['titulo']      : "";
    $descricao    =   (isset($ocontato['descricao'])) ?    $ocontato['descricao'] : "";
    $date         =   (isset($ocontato['agendado']))    ?    $ocontato['agendado']    : 'NULL';
    $title_top    =   ($contato_id!=0)                  ?    'Editar Contato'         : 'Novo Contato';
    $seguimento   =   (isset($ocontato['seguimento']))  ?    $ocontato['seguimento']      :  (isset($_GET['seg']) ? $_GET['seg'] : -1);


  // ----------------------   GET LISTS   --------------------------//
  //$entidades = $conn->query("SELECT * FROM entidades WHERE user=" . $_SESSION['login'] . " AND id in (SELECT entidade FROM contatos_entidades WHERE contato=" . $contato_id . " AND deleted IS NULL)")->fetch_all(MYSQLI_ASSOC);
  //$imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'])->fetch_all(MYSQLI_ASSOC);
  //$imoveis = $conn->query("SELECT * FROM imoveis WHERE user=" . $_SESSION['login'] . " AND id in (SELECT imovel FROM contatos_imoveis WHERE contato=" . $contato_id . " AND deleted IS NULL)")->fetch_all(MYSQLI_ASSOC);
  //echo "SELECT * FROM contatos WHERE ((idimovel=" . $with . " AND idimovel<>0) OR (identidade=" . $entidade_id . " AND idimovel<>0)) AND id<>". $contato_id;
  //$history = $conn->query("SELECT * FROM contatos WHERE ((idimovel=" . $with . " AND idimovel<>0) OR (identidade=" . $entidade_id . " AND idimovel<>0)) AND id<>". $contato_id . " AND id<>0")->fetch_all(MYSQLI_ASSOC);
  //$all_imoveis =  $conn->query("SELECT * FROM imoveis WHERE user=" . $_SESSION['login'] . " AND ID NOT IN(SELECT imovel FROM contatos_imoveis WHERE contato=" . $contato_id . " AND deleted IS NULL)")->fetch_all(MYSQLI_ASSOC);
  //$all_entidades = $conn->query("SELECT * FROM entidades WHERE user=" . $_SESSION['login'] . " AND ID NOT IN(SELECT entidade FROM contatos_entidades WHERE contato=" . $contato_id . " AND deleted IS NULL)")->fetch_all(MYSQLI_ASSOC);

  $entidades = ($result = $conn->query("SELECT * FROM entidades WHERE id IN (SELECT entidade FROM contatos_entidades WHERE contato = " . $contato_id . " AND entidade <> 0) AND id <> 0 AND user=" . $_SESSION['login'])) ? $result->fetch_all(MYSQLI_ASSOC) : array();
  echo "SELECT * FROM entidades WHERE id IN (SELECT * FROM contatos_entidades WHERE contato = " . $contato_id . " AND entidade <> 0) AND id <> 0 AND user=" . $_SESSION['login'] . "<br>";
  // var_dump($entidades);

  $all_entidades = ($result = $conn->query("SELECT * FROM entidades WHERE user=" . $_SESSION['login'])) ? $result->fetch_all(MYSQLI_ASSOC) : array();
  //echo "SELECT * FROM entidades WHERE user=" . $_SESSION['login'] . " <br>";
  //var_dump($all_entidades);




  $history      =   ($result = $conn->query("SELECT * FROM contatos WHERE seguimento=" . $seguimento)) ? $result->fetch_all(MYSQLI_ASSOC) : array();



?>
