<?php
require_once '../partials/connectDB.php';
//include_once '../partials/validate_session.php';


function opposite_rel($id_relation, $conn) {
  $opposite = $conn->query('SELECT id FROM relacoes_familiares WHERE grupo IN (SELECT grupo FROM relacoes_familiares WHERE id =' . $id_relation.') AND id <> '. $id_relation)->fetch_assoc()['id'];
	
    /*if($id_relation>=19)
      return $id_relation;
    if($id_relation % 2 == 0 AND $id_relation!=0) {
      return $id_relation--;
    }
    else {
      return $id_relation++;
    }*/
	return $opposite;
}


if(isset($_GET['id'])) {
  //try {
    $thisentidade = $conn->query('SELECT * FROM entidades WHERE id=' . $_GET['id'])->fetch_all(MYSQLI_ASSOC)[0];
    $identidade = $_GET['id'];
    $title = "Editar entidade";
  //} catch (Exception $e) {
  //  header('Location: ../partials/404.php');
//	echo 'a';
 // }
}
else {
  $thisentidade = array();
  $identidade = 0;
  $title = "Nova entidade";
}


//queries

$query_familiares_selected = 'SELECT ER1.id, ER1.objetoB as idB, (SELECT CONCAT(nome," ",apelido) FROM entidades WHERE id=ER1.objetoB) as nomeB, (SELECT nome FROM relacoes_familiares WHERE id=ER1.relacao) as relacao FROM entidades_relacoes as ER1 WHERE objetoA='.$identidade.' AND tipo =1 union (SELECT ER2.id, ER2.objetoA as idB, (SELECT CONCAT(nome," ",apelido) FROM entidades WHERE id=ER2.objetoA) as nomeB, (SELECT nome FROM relacoes_familiares WHERE id=(IF(relacao % 2 = 0, relacao-1, relacao+1))) as relacao FROM entidades_relacoes as ER2 WHERE objetoB='.$identidade.' AND tipo =1 )';

$query_empresariais_selected = 'SELECT ER1.id,  ER1.objetoB as idB, (SELECT designacao FROM empresas WHERE id=ER1.objetoB)  as nomeB, (SELECT nome FROM relacoes_empresariais WHERE id=ER1.relacao) as relacao FROM entidades_relacoes as ER1 WHERE objetoA=' . $identidade . ' AND tipo =2 ';
$query_utilizadores_selected = 'SELECT * FROM users WHERE id IN (SELECT user FROM entidades_users WHERE entidade = '.$identidade.')';



//listas
$listatipo = ($result = $conn->query('SELECT * FROM tipos')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listaratings = ($result = $conn->query('SELECT * FROM ratings')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listacategoria = ($result = $conn->query('SELECT * FROM categorias')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listageneros = ($result = $conn->query('SELECT * FROM generos')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listarelacoes_famiglia = ($result = $conn->query('SELECT * FROM relacoes_familiares')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listarelacoes_societa = ($result = $conn->query('SELECT * FROM relacoes_empresariais')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listapaises = ($result = $conn->query('SELECT * FROM pais')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listaleads = ($result = $conn->query('SELECT * FROM leads')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listaclassificadores = ($result = $conn->query('SELECT * FROM classificadores')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listautilizadores = ($result = $conn->query('SELECT * FROM users WHERE id!=4')) ? $result->fetch_all(MYSQLI_ASSOC) : array();



//listas relacoes
$listaempresas = ($result = $conn->query('SELECT * FROM empresas ')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$empresas_selected = ($result = $conn->query($query_empresariais_selected)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$listafamiliares = ($result = $conn->query('SELECT * FROM entidades WHERE id!=' . $identidade)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$familiares_selected = ($result = $conn->query($query_familiares_selected )) ?  $result->fetch_all(MYSQLI_ASSOC) : array();
$utilizadores_selected = ($result = $conn->query($query_utilizadores_selected )) ?  $result->fetch_all(MYSQLI_ASSOC) : array();



//campos seleccionados
$tipo_selected = ($result = $conn->query('SELECT tipo FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['tipo'] : 0;
$rating_selected = ($result = $conn->query('SELECT rating FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['rating'] : 0;
$categoria_selected = ($result = $conn->query('SELECT categoria FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['categoria'] : 0;
$genero_selected = ($result = $conn->query('SELECT genero FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['genero'] : 0;
$pais_selected = ($result = $conn->query('SELECT pais FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['pais'] : 0;
$lead_selected = ($result = $conn->query('SELECT lead FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['lead'] : 1;
$classificador_selected = ($result = $conn->query('SELECT classificador FROM entidades WHERE id=' . $identidade )) ? $result->fetch_assoc()['classificador'] : 1;



//campos
$domname = (isset($thisentidade['nome'])) ? $thisentidade['nome'] : "";
$domape = (isset($thisentidade['apelido'])) ? $thisentidade['apelido'] : "";
$domtele = (isset($thisentidade['telemovel'])) ? $thisentidade['telemovel'] : "";
$domnick = (isset($thisentidade['nickname'])) ? $thisentidade['nickname'] : "";
$domemail = (isset($thisentidade['email'])) ? $thisentidade['email'] : "";
$domfilhos = (isset($thisentidade['filhos'])) ? $thisentidade['filhos'] : '';
$domnif = (isset($thisentidade['nif'])) ? $thisentidade['nif'] : '';
$domident = (isset($thisentidade['identificacao'])) ? $thisentidade['identificacao'] : '';
$domvalid = (isset($thisentidade['validade'])) ? $thisentidade['validade'] : '';
$domnasc = (isset($thisentidade['nascimento'])) ? $thisentidade['nascimento'] : '';
$domapagado = (isset($thisentidade['apagado'])) ? 1 : 0;
$domzip1 = (isset($thisentidade['zip1'])) ? $thisentidade['zip1'] : '';
$domzip2 = (isset($thisentidade['zip2'])) ? $thisentidade['zip2'] : '';
$domaddr = (isset($thisentidade['morada'])) ? $thisentidade['morada'] : '';
$domlocal = (isset($thisentidade['local'])) ? $thisentidade['local'] : '';


if(isset($_GET['id'])) 
{
	if($result = $conn->query('SELECT * FROM users WHERE id IN (SELECT user FROM entidades_users WHERE entidade = '.$_GET['id'].')')) 
	{
		$lista_pessoas = $result->fetch_all(MYSQLI_ASSOC);
	}
}
else 
{
	$lista_pessoas = [];
}

$isadmin = $conn->query('SELECT admin FROM users WHERE id = ' . $_SESSION['login'])->fetch_assoc()['admin'];
?>
