<?php
	require_once '../partials/connectDB.php';
	include_once '../partials/validate_session.php';
	//echo '<pre>' . var_export($_POST, true) . '</pre>';

	if(isset($_POST['id_delete'])) {
		if($conn->query('UPDATE imoveis SET estado=100 WHERE id=' . $_POST['id_delete'])) {
			echo 1;
		}
		else {
			echo 0;
		}
	}

	if(isset($_POST['id_permanent_delete'])) {
		if($conn->query('DELETE FROM imoveis WHERE id=' . $_POST['id_permanent_delete'])) {
			//$missing_list = 
			//($result = $conn->query('SELECT a.id+1 AS start, MIN(b.id) - 1 AS end FROM imoveis AS a, imoveis AS b WHERE a.id < b.id GROUP BY a.id HAVING start < MIN(b.id)')) ? 
			//$result->fetch_all(MYSQLI_ASSOC) : [];
			$conn->query('DELETE FROM imoveis_entidades WHERE imovel=' . $_POST['id_permanent_delete']);
			$images = ($result = $conn->query('SELECT * FROM imoveis_fotos WHERE imovel=' . $_POST['id_permanent_delete'])) ? $result->fetch_all(MYSQLI_ASSOC) : [];
			foreach($images as $img) {
				unlink($img['url']);
			}
			$conn->query('DELETE FROM imoveis_ficheiros WHERE imovel=' . $_POST['id_permanent_delete']);
			$files = ($result = $conn->query('SELECT * FROM imoveis_ficheiros WHERE imovel=' . $_POST['id_permanent_delete'])) ? $result->fetch_all(MYSQLI_ASSOC) : [];
			foreach($files as $file) {
				unlink('../../' . $file['url']);
			}
			$conn->query('DELETE FROM imoveis_ficheiros WHERE imovel=' . $_POST['id_permanent_delete']);
			echo 1;
		}
		else {
			echo 0;
		}
	}