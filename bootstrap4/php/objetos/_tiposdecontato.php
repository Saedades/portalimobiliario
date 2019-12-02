<?php
    require_once '../partials/connectDB.php';
    include_once '../partials/validate_session.php';

	switch($_POST['action']) {
		case 1: 
			$conn->query('INSERT INTO tipo_contato(nome) VALUES("' . $_POST['nome'] . '")'); 
			break;
		case 2: 
			$conn->query('DELETE FROM tipo_contato WHERE id =' . $_POST['id']); 
			$conn->query('UPDATE entidades SET classificador=1 WHERE classificador=' . $_POST['id']);
			break;
		case 4: 
			if($conn->query('UPDATE tipo_contato SET nome = "'.  $_POST['nome'] .'" WHERE id =' . $_POST['id']))
				echo 1;
			else
				echo 0;
			break;
	}
	
?>