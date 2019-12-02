<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

if(isset($_POST['page']) && $_POST['page']==1) {
	$contact = $_POST['id_entity'];
	$ang = 		$_POST['id_ang'];
	if($ang!=0)
		$conn->query('INSERT INTO angariacoes_entidades(idang, ident) VALUES (' . $ang . ',' . $contact . ')');
}
elseif(isset($_POST['page']) && $_POST['page']==2) {
	$imo = 	$_POST['id_imo'];
	$ang = 		$_POST['id_ang'];
	if($ang!=0) {
		$result = $conn->query('SELECT * FROM angariacoes_imoveis WHERE idangariacao=' . $ang . ' AND idimovel =' . $imo);
		if($result->num_rows==0)
			$conn->query('INSERT INTO angariacoes_imoveis(idangariacao, idimovel) VALUES (' . $ang . ',' . $imo . ')');
	}

}
else {
	$contact = $_POST['id_contact'];
	$subject = $_POST['id_subject'];
	if($subject!=0)
		$conn->query('INSERT INTO contatos_subjects(ids, idc) VALUES (' . $subject . ',' . $contact . ')');
}
?>
