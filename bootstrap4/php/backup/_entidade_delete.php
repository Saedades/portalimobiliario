<?php
require_once('connectDB.php');
include_once 'validate_session.php';


//------- input -------//
//		id_delete	   //
//		the_type       //
//---------------------//
//$_POST['id_delete']







//BIBLIOTECA

function delete_entidades_por_id($id) {
	return $conn->query("UPDATE entidades SET estado = 0 WHERE id = " . $id);
}

function delete_entidades_assoc_por_entidade($id) {
	return $conn->query("UPDATE entidades_assoc SET estado = 0 WHERE entidadeA = " . $id . " OR entidadeB = " . $id);
}

function delete_entidades_assoc_por_id($id) {
	return $conn->query("UPDATE entidades_assoc SET estado = 0 WHERE id = " . $id);
}

function delete_entidades_imoveis_por_entidade($id) {
	return $conn->query("DELETE FROM entidades_imoveis WHERE entidade = " . $id);
}

function delete_entidades_imoveis_por_imovel($id) {
	return $conn->query("DELETE FROM entidades_imoveis WHERE imovel = " . $id);
}

function delete_entidades_imoveis_por_id($id) {
	return $conn->query("DELETE FROM entidades_imoveis WHERE id = " . $id);
}

if(isset($_POST['id_type'])) {
	$type = $_POST['id_type'];
	switch($type){
		case 12: delete_entidades_por_id($_POST['id_delete']); break;
		case 0: echo '0'; break;
	}
}


/*

if(isset($_POST['id_delete'])) {
	if(isset($_POST['the_type']) AND $_POST['the_type'] == 9) {
		//type 9 deletes ENTITIES
		$success = $conn->query("DELETE FROM entidades WHERE id =" . $_POST['id_delete']);
	}
	elseif(isset($_POST['the_type']) AND $_POST['the_type'] == 1) {
		//type 1 deletes CONTATOS
		$success = $conn->query("DELETE FROM reports WHERE idreports =" . $_POST['id_delete']);
	}
	elseif(isset($_POST['the_type']) AND $_POST['the_type'] == 2) {
		//type 2 deletes ENTIDADES_ASSOC
		
	}
	elseif(isset($_POST['the_type']) AND $_POST['the_type'] == 3) {
		//type 3 deletes IMOVEIS
		$conn->query("DELETE FROM imoveis WHERE idsubjects = " . $_POST['id_delete']);
		$success = $conn->query("DELETE FROM contatos_subjects WHERE ids =" . $_POST['id_delete']);
	}


	if($success) {
		echo "yeah!";
	}
	else {
		echo "nope.";
	}

}*/
?>