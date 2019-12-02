<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['action'])) {

	switch($_POST['action']) {
		// GET ALL TAGS
		case 1:  
			$result = ($temp = $conn->query('SELECT * FROM classificadores')->fetch_all(MYSQLI_ASSOC)) ? $temp : [];
			break;
		case 2:  
			$test=0;
			if( $_POST['entidade'] != 0 ) {
				$test=$test+10;
				if($conn->query('DELETE FROM entidades_tags WHERE entidade =' . $_POST['entidade'])) {
					$test=$test+10;
					$tags = $_POST['tags'];
					foreach( $tags as $item) {
						$test=$test+1;
						$result = $conn->query('INSERT INTO entidades_tags(entidade, classificador) VALUES('.$_POST['entidade'].','.$item.')');
						
					}
				}
			}
			json_encode([$test, 'DELETE FROM entidades_tags WHERE entidade =' . $_POST['entidade'], 'INSERT INTO entidades_tags(entidade, classificador) VALUES('.$_POST['entidade'].','.$item.')']);
			break;
	}

	echo json_encode($result);
}