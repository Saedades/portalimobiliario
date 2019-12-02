<?php
require_once '../partials/connectDB.php';
$email = $_POST['email'];
$password = $_POST['password'];
$status = $conn->query('SELECT status FROM users WHERE email LIKE "' . $email . '"')->fetch_assoc()['status'];

if ($status !=1) {
    //conta desativa
    header('Location: login.php');
    //conta ativa
} else {
    //password é igual
    $pwdselect = $conn->query("SELECT password FROM users WHERE email LIKE '" . $email . "'")->fetch_assoc()['password'];

    //if (password_verify($pwd,$pwdselect)) {
    if (strcmp($password,$pwdselect)==0) {
        $length = 64;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
		
		$userid = $conn->query("SELECT id FROM users WHERE email LIKE '" . $email . "'")->fetch_assoc()['id'];
		$expiry = $conn->query("SELECT expiration FROM users WHERE email LIKE '" . $email . "'")->fetch_assoc()['expiration'];
		echo $expiry . '<br>';
		$delta_time = strtotime($expiry) - time();
		$logged_in = $conn->query('SELECT crawl FROM users WHERE email LIKE "' . $email . '"')->fetch_assoc()['crawl'];
		
		if($logged_in==1 && $delta_time>0) {
			//error, user currently logged in
			$conn->query('INSERT INTO atividade(accao, user, remote_addr, http_x_forwarder_for) VALUES(-1,'.$userid.',"'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['HTTP_X_FORWARDED_FOR'].'")');
			header('Location: login.php');
			exit();
		}
		else {
			//successfully logged in
			$conn->query('INSERT INTO atividade(accao, user, remote_addr, http_x_forwarder_for) VALUES(1,'.$userid.',"'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['HTTP_X_FORWARDED_FOR'].'")');
			$conn->query('UPDATE users SET crawl=1 WHERE id=' . $userid);
		}

        if($conn->query("UPDATE  users  SET session = '" . $token . "' WHERE email LIKE '" . $email . "'")) {

            if(!$conn->query("UPDATE users SET expiration = '" . date("Y-m-d H:i:s", strtotime("+15 minutes")) . "' WHERE email LIKE '" . $email . "'")) {
            }
            session_start();
            $_SESSION['session'] = $token;
            $_SESSION['login'] = $conn->query("SELECT id FROM users WHERE email LIKE '" . $email . "'")->fetch_assoc()['id'];
            header('Location: ../profile/profile.php');

        }
        else {
            header('Location: login.php');
        }

    } 
	else {
      	header('Location: login.php');
    }
}

?>
