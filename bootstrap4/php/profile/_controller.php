<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

//$query = 'SELECT * FROM imoveis WHERE id =' . $_GET['id'];
$query = 'SELECT * FROM imoveis WHERE id LIKE "' . $_POST['id'] . '"';
//$query = 'SELECT * FROM imoveis WHERE kwid LIKE "' . $_GET['id'] . '"';
//echo $query . '<br><br>';

$imoveis = ($result=$conn->query($query)) ? $result->fetch_all(MYSQLI_ASSOC) : [];

foreach($imoveis as $key => $imo) {
	//echo print_r($imo, true) .'<br><br>';
	$imoveis[$key]['negocio'] = $conn->query('SELECT nome FROM negocios WHERE id='. $imo['negocio'])->fetch_assoc()['nome'];
	$imoveis[$key]['tipocasa'] = $conn->query('SELECT nome FROM tiposcasa WHERE id='. $imo['tipocasa'])->fetch_assoc()['nome'];
	$imoveis[$key]['tipologia'] = $conn->query('SELECT nome FROM tipologias WHERE id='. $imo['tipologia'])->fetch_assoc()['nome'];
	
	$imoveis[$key]['distrito'] = $conn->query('SELECT NomeLocalidade FROM codigospostais WHERE NumCodigoPostal='. $imo['zip1'] . 
											  ' AND ExtCodigoPostal='. $imo['zip2'])->fetch_assoc()['NomeLocalidade'];
	$imoveis[$key]['concelho'] = $conn->query('SELECT Designacao FROM concelhos WHERE 
				CodigoConcelho IN 
				(SELECT CodigoConcelho FROM codigospostais WHERE NumCodigoPostal='. $imo['zip1'] . ' AND ExtCodigoPostal='. $imo['zip2'] . ')
				AND CodigoDistrito IN
				(SELECT CodigoDistrito FROM codigospostais WHERE NumCodigoPostal='. $imo['zip1'] . ' AND ExtCodigoPostal='. $imo['zip2'] . ')'
	)->fetch_assoc()['Designacao'];
	//$imoveis[$key]['freguesia'] = $conn->query('SELECT nome FROM codigospostais WHERE NumCodigoPostal='. $imo['zip1'] . ' AND ExtCodigoPostal='. $imo['zip2'])->fetch_assoc()['nome'];
	//NumCodigoPostal 
}

//print_r($imoveis);
//echo '<br><br>';



echo json_encode(['message' => 'this evening side', 'input' => $_POST['id'], 'query' => $query, 'imoveis'=>$imoveis]);