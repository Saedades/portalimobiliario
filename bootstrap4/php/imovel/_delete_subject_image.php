<?php
$inipath = php_ini_loaded_file();

require_once ('../partials/connectDB.php');
session_start();
$login = $_SESSION['login'];
echo '<pre>' . var_export($_POST, true) . '</pre>';

if(isset($_POST['id_subject']) AND $_POST['id_subject']!=0) {
	$conn->query('DELETE FROM imoveis_fotos WHERE id=' . $_POST['id_delete']);
}
