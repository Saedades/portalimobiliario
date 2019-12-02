<?php
	require_once '../partials/connectDB.php';
	include_once '../partials/validate_session.php';
    echo '<pre>' . var_export($_POST, true) . '</pre>';
    
    echo $conn->query('UPDATE imoveis SET dist=2 WHERE id=' . $_POST['imovel']);