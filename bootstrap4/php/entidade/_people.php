<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../partials/connectDB.php';

if(isset($_POST['action'])) {

	switch($_POST['action']) {
		case 1:
			if($conn->query('INSERT INTO entidades_users(user, entidade) VALUES('.$_POST['iduser'].','.$_POST['identidade'].')')) {
				$var = 'yes';
			}
			break;
		case 2:
			if($conn->query('DELETE FROM entidades_users WHERE user =' . $_POST['iduser'].' AND entidade='.$_POST['identidade'])) {
				$var = 'yes';
			}
			break;
		case 80:
			if($result = $conn->query('SELECT * FROM users WHERE user IN (SELECT user FROM entidades_users WHERE entidade = ' . $_POST['entity'] . ')')) 
			{
				echo json_encode($result);
			}
			else  
			{
				echo json_encode('SELECT * FROM users WHERE id IN (SELECT user FROM entidades_users WHERE entidade = ' . $_POST['entity'] . ')');
			}
			break;
	}
}