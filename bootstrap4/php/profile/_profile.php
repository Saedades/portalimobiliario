<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case 1: 
			echo $conn->query('SELECT id FROM entidades WHERE id IN (SELECT entidade FROM contatos WHERE id = '.$_POST['idseg'].')')->fetch_assoc()['id'];
			break;
	}
}