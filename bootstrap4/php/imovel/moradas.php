<?php
	require_once '../partials/connectDB.php';
	include_once '../partials/validate_session.php';

	$distrito =	($result = $conn->query('SELECT nome FROM distrito WHERE id IN 
				(SELECT CodigoDistrito FROM codigospostais WHERE NumCodigoPostal = ' . 
				(isset($_POST['zip1']) ? $_POST['zip1'] : 0) . ')')) ? $result->fetch_assoc() : [];

	$zip1 = (isset($_POST['zip1']) ? $_POST['zip1'] : 0);
	$zip2 = ((isset($_POST['zip2']) AND strlen($_POST['zip2'])>1) ? $_POST['zip2'] : 0); 

	$concelho =	($result = $conn->query(
		'SELECT Designacao FROM concelhos WHERE 
			CodigoConcelho IN 
				(SELECT CodigoConcelho FROM codigospostais WHERE NumCodigoPostal = ' . $zip1 .  ' AND ExtCodigoPostal = ' . $zip2 . ')
			AND CodigoDistrito IN 
				 (SELECT CodigoDistrito FROM codigospostais WHERE NumCodigoPostal = ' . $zip1 .  ' AND ExtCodigoPostal = ' . $zip2 . ')'
				)) ? $result->fetch_assoc() : [];

	/*$freguesias_escolhiveis = ($result = $conn->query('SELECT Designacao, id FROM freguesiasactuais WHERE IdConcelho IN '. 
													  '(SELECT CodigoConcelho FROM codigospostais WHERE NumCodigoPostal = ' . $zip1 .  
													  ' AND ExtCodigoPostal = ' . $zip2 . ')' )) ? $result->fetch_assoc() : [];
				 */

	echo json_encode([$distrito, $concelho]);
?>