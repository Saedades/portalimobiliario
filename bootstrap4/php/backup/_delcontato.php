<?php
require_once 'connectDB.php';
include_once 'validate_session.php';

if(isset($_POST['page']) && $_POST['page']==1) {
	$contact = 	$_POST['id_contact'];
	$ang = 		$_POST['id_ang'];
	$conn->query('DELETE FROM angariacoes_entidades WHERE ident=' . $contact . ' AND idang=' . $ang);
}
elseif(isset($_POST['page']) && $_POST['page']==2) {
	$imo = 	$_POST['id_imo'];
	$ang = 		$_POST['id_ang'];
	$conn->query('DELETE FROM angariacoes_imoveis WHERE idangariacao=' . $ang . ' AND idimovel =' . $imo);
}
else {
	$contact = $_POST['id_contact'];
	$subject = $_POST['id_subject'];
	$conn->query('DELETE FROM contatos_subjects WHERE ids=' . $subject . ' AND idc=' . $contact);
}

?>