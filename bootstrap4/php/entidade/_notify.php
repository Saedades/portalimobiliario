<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch($_POST['action']) {
	case 1: 
		$fullname = $conn->query('SELECT CONCAT(nome, " ", apelido) as fullname FROM entidades WHERE id=' . $_POST['identidade'])->fetch_assoc()['fullname'];
		$link = '/bootstrap4/php/entidade/entidade.php?id=' + $_POST['identidade'];
		if($conn->query('INSERT INTO notificacoes(user, descricao, tipo, href, criado_por) VALUES('.$_POST['iduser'].',"'.'Foi-lhe atribuída a administração da entidade '.$fullname.'",1,"'.$link.'", '.$_SESSION['login'].')')) 
		{
			echo 'yes';
		}
		else {
			echo mysqli_error($conn) . 
			'INSERT INTO notificacoes(user, descricao, criado_por) VALUES('.$_POST['iduser'].',"'.'Foi-lhe atribuída a administração da entidade '.$fullname.'"'.$_SESSION['login'].')';
		}
		break;
	case 2: 
		if($conn->query('INSERT INTO notificacoes(user, descricao, tipo, href, criado_por) VALUES('.$_POST['iduser'].',"'.'Foi-lhe removida a administração da entidade '.$fullname.'",1,"'.$link.'",'.$_SESSION['login'].')')) 
		{
			echo 'yes';
		}
		else 
		{
			echo mysqli_error($conn) . 
			'INSERT INTO notificacoes(user, descricao, criado_por) VALUES('.$_POST['iduser'].','.'"Foi-lhe removida a administração da entidade '.$fullname.'" ,'.$_SESSION['login'].')';
		}
		break;
}