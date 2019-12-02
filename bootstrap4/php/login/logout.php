<?php
	require_once '../partials/connectDB.php';

    session_start();

	$conn->query('UPDATE users SET crawl=0 WHERE id=' . $_SESSION['login']);
	$conn->query('INSERT atividade(accao,user,remote_addr,http_x_forwarder_for) VALUES(2,' . $_SESSION['login'] . ',' . $_SERVER['REMOTE_ADDR'] . ',' . $_SERVER['HTTP_X_FORWARDER_FOR'] . ')');

	session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
?>
