<?php
$inipath = php_ini_loaded_file();

require_once ('../partials/connectDB.php');
session_start();
$login = $_SESSION['login'];
echo '<pre>' . var_export($_POST, true) . '</pre>';

if($conn->query('DELETE FROM angariacoes_ficheiros WHERE id=' . $_POST['id_delete']))
	echo 1;
else
	echo 0;
