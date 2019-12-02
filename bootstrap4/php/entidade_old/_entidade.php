<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if(isset($_GET['id'])) {

    //-------------------------------se este id de entidade existe e pertence ao utilizador logado-------------------------------------------//
    if($thisentidade = $conn->query("SELECT * FROM entidades WHERE id =" . $_GET['id'] . " AND pertence_a = " . $_SESSION['login'])->fetch_assoc()) {

        if (isset($thisentidade['nome'])) {
            $name = $thisentidade['nome'];
        }
        else {
            $name = "";
        }

        if (isset($thisentidade['email'])) {
            $email = $thisentidade['email'];
        }
        else {
            $email = "";
        }

        if (isset($thisentidade['telemovel'])) {
            $phnr = $thisentidade['telemovel'];
        }
        else {
            $phnr = "";
        }

        if (isset($thisentidade['estado'])) {
            $status = $thisentidade['estado'];
        }
        else {
            $status = 1;
        }

        if (isset($thisentidade['nascimento'])) {
            $birth = $thisentidade['nascimento'];
        }
        else {
            $birth = "";
        }


		if (isset($thisentidade['idsubject'])) {
            $idsubject = $thisentidade['idsubject'];
        }
        else {
            $idsubject = 0;
        }

		if (isset($thisentidade['morada'])) {
            $morada = $thisentidade['morada'];
        }
        else {
            $morada = "";
        }

		if (isset($thisentidade['local'])) {
            $local = $thisentidade['local'];
        }
        else {
            $local = "";
        }

		if (isset($thisentidade['zip1'])) {
            $zip1 = $thisentidade['zip1'];
        }
        else {
            $zip1 = "";
        }

		if (isset($thisentidade['zip2'])) {
            $zip2 = $thisentidade['zip2'];
        }
        else {
            $zip2 = "";
        }

		if (isset($thisentidade['nif'])) {
            $nif = $thisentidade['nif'];
        }
        else {
            $nif = "";
        }

        $title = "Editar Entidade";


		if($relations = $conn->query("SELECT * FROM relations ")->fetch_all(MYSQLI_ASSOC)) {

		}
		if($all_entidades = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $_SESSION['login'] )->fetch_all(MYSQLI_ASSOC)) {

    }
    if($all_imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN (SELECT imovel FROM foco20.imoveis_entidades where entidade=" .$_GET['id'] . " AND date_deleted IS NULL)")->fetch_all(MYSQLI_ASSOC)) {

		}
		if($entidades_assoc = $conn->query("SELECT * FROM entidades_assoc WHERE (entidadeA = " . $_GET['id'] . " OR entidadeB = " . $_GET['id'] . ") AND estado=1" )->fetch_all(MYSQLI_ASSOC)){

		}

    }
    else {
      header('Location: listaentidades.php');
    }
    $identidade = (isset($_GET['id'])) ? $_GET['id'] : 0;
    $iduser = $_SESSION['login'];

}
else {
  $name = " ";
  $email = " ";
  $phnr = " ";
  $morada="";
  $status = 1;
  $nif="";
  $zip1 ="";
  $zip2 = "";
  $birth ="";
  $idsubject=0;
  $history="";
  $local="";
  $title = "Criar Entidade";
  $entidades_assoc=[];
  $identidade=0;
}

if(!isset($identidade)) { $identidade=0; }
if(!isset($iduser)) { $iduser=0; }
$listaestados = $conn->query('SELECT * FROM estados')->fetch_all(MYSQLI_ASSOC);
$imoveis = $conn->query('SELECT * FROM `imoveis` WHERE ID IN (SELECT imovel FROM imoveis_entidades WHERE entidade = ' . $identidade . ' AND date_deleted IS NULL)')->fetch_all(MYSQLI_ASSOC);
$history = $conn->query("SELECT * FROM contatos WHERE id IN (SELECT DISTINCT contato FROM contatos_entidades WHERE entidade=" . $identidade . ") AND iduser=" . $iduser)->fetch_all(MYSQLI_ASSOC);
//echo "SELECT * FROM contatos WHERE ((identidade=" . $identidade . " AND identidade<>0 AND identidade IS NOT NULL) OR (idimovel=" . $idsubject . " AND idimovel<>0 AND idimovel IS NOT NULL)) AND iduser=" . $iduser;
$query_all_imovels ="SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN (SELECT imovel FROM foco20.imoveis_entidades where entidade=" .$identidade . " AND date_deleted IS NULL)";
$all_imoveis = $conn->query($query_all_imovels)->fetch_all(MYSQLI_ASSOC);

$listatipo = $conn->query('SELECT * FROM entidade_tipo')->fetch_all(MYSQLI_ASSOC);
$tipo_selected = ($result = $conn->query('SELECT tipo FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['tipo'] : 0;
$domtipox = ($result = $conn->query('SELECT tipo_extra FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['tipo_extra'] : '';
$domcargo = ($result = $conn->query('SELECT cargo FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['cargo'] : '';

$listainteresse = $conn->query("SELECT * FROM interesses")->fetch_all(MYSQLI_ASSOC);
$interesse_selected = ($result = $conn->query('SELECT interesse FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['interesse'] : '';

?>
