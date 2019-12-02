<?php
session_start();
if(isset($_SESSION['login']) AND isset($_SESSION['session'])) {
  	
	$token = $_SESSION['session'];
  	$id = $_SESSION['login'];
	
  	if($exp_time = $conn->query("SELECT expiration FROM users WHERE id = '" . $id . "'")->fetch_assoc()['expiration']) {
		$nowdate = date('Y-m-d H:i:s');
		$time_exp = date_create($exp_time);
		$datenow = date_create($nowdate);
		if($time_exp < $datenow) {
			
			$conn->query('UPDATE users SET crawl=0 WHERE id = ' . $id);
			header('Location: ../login/login.php');
			
		} 
		else {
			if($ss_token = $conn->query("SELECT session FROM users WHERE id = '" . $id . "'")->fetch_assoc()['session']) {
				if(strcmp($token, $ss_token)!=0) {
					
					$conn->query('UPDATE users SET crawl=0 WHERE id = ' . $id);
					header('Location: ../login/login.php');
					
				}
			}
		}
  	}
}
else {
	
	$conn->query('UPDATE users SET crawl=0 WHERE id = ' . $id);
    header('Location: ../login/login.php');
	
}
?>
