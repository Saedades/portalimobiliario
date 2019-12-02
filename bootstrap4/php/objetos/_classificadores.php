<?php
    require_once '../partials/connectDB.php';
    include_once '../partials/validate_session.php';

	switch($_POST['action']) {
		case 1: 
			$conn->query('INSERT INTO classificadores(nome) VALUES("' . $_POST['nome'] . '")'); 
			break;
		case 2: 
			$conn->query('DELETE FROM classificadores WHERE id =' . $_POST['id']); 
			$conn->query('UPDATE entidades SET classificador=1 WHERE classificador=' . $_POST['id']);
			break;
		case 3: 
			if($conn->query('UPDATE classificadores SET cor = "'.  $_POST['hex'] .'" WHERE id =' . $_POST['id']))
				echo 1;
			else
				echo 0;
			break;
		case 4: 
			if($conn->query('UPDATE classificadores SET nome = "'.  $_POST['nome'] .'" WHERE id =' . $_POST['id']))
				echo 1;
			else
				echo 0;
			break;
	}
	
?>