<?php

    //$conn = mysqli_connect("localhost","root","","foco200");
    //$conn = mysqli_connect("localhost","pleskuser","Lxo9y*9xJ5fIegco","foco200");
	//$conn = mysqli_connect("localhost","foco200user","1Tqmy4!K2Wljdxgw","foco200");
	$conn = mysqli_connect("localhost","portal_user","sRg03w~0AQvbfmso","rg_db_portal");

    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    mysqli_set_charset($conn,"utf8");

	session_start();
	if(isset($_SESSION['login']) && !empty($_SESSION['login'])) {
	   $conn->query('UPDATE users SET expiration = "' . date("Y-m-d H:i:s", strtotime("+15 minutes")) .'" WHERE id = ' . $_SESSION['login']);
	}

?>
