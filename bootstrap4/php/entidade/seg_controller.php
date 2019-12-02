<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';

// segid ; segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//

$update_query = 'UPDATE 	contatos SET
                            titulo="'.$_POST['segtitle'].'",
                            descricao="'.$_POST['segdescricao'].'",
                            agendado="'.$_POST['segdata']. ' ' . (isset($_POST['seghour']) ? $_POST['seghour'] : '00:00:00') . '",
                            completado='.(isset($_POST['segestado']) ? '"' . date('Y-m-d H:i:s') . '"' : 'NULL') .',
                            modified="'.date('Y-m-d H:i:s').'",
                            estado='.$_POST['segestado'].',
                            tipo='.$_POST['segtipo'].',
							entidade='.$_POST['entidade'].',
                            tipohora='.$_POST['segtypehour'].',
							user='.$_POST['user'].'
                WHERE id=' . $_POST['segid'];

$insert_query = 'INSERT INTO contatos(titulo,descricao,agendado,completado,modified,estado,tipo, entidade, tipohora, user)
                 VALUES(
                     "'.$_POST['segtitle'].'",
                    "'.$_POST['segdescricao'].'",
                    "'.$_POST['segdata']. ' ' . (isset($_POST['seghour']) ? $_POST['seghour'] : '00:00:00') . '", 
					'.(isset($_POST['segestado']) ? '"' . date('Y-m-d H:i:s') . '"' : 'NULL') .',
                    "'.date('Y-m-d H:i:s').'",
                    '.$_POST['segestado'].',
                    '.$_POST['segtipo'].',
                    '.$_POST['entidade'].',
                    '.$_POST['segtypehour'].',
					'.$_POST['user'].')';

if(isset($_POST['segid']) && $_POST['segid']!=0) {
  //echo 'yas';
    $result = $conn->query($update_query);
    if($result) {
        //header('Location: listaentidades.php');
        echo $conn->query('SELECT entidade FROM contatos WHERE id = ' . $_POST['segid'])->fetch_assoc()['entidade'];
		//echo $update_query;
    }
    else {
		echo $update_query;
        //echo 'no<br>' . mysqli_error($conn);
    }
}
elseif(isset($_POST['segid'])) {
    //echo 'nas';
    $result = $conn->query($insert_query);
    if($result) {
		//echo $conn->insert_id;
		echo $conn->query('SELECT entidade FROM contatos WHERE id = ' . $conn->insert_id)->fetch_assoc()['entidade'];
	}
    else
		echo $insert_query;
      //echo mysqli_error($conn);
    //echo $conn->query('SELECT entidade FROM contatos WHERE id = ' . $conn->insert_id)->fetch_assoc()['entidade'];
}
