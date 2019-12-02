<?php

	session_start();
	if(isset($_SESSION['login']) && !empty($_SESSION['login'])) {
	   	header('Location: php/profile/profile.php');
	}
	else {
		header('Location: php/login/login.php');
	}

?>