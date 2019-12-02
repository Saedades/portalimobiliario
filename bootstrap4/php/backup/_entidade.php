<?php
require_once 'connectDB.php';
include_once 'validate_session.php';

if(isset($_POST['sbmtd'])) {
	
	if (isset($_POST['domnif'])) {
        $nif = $_POST['domnif'];
    }
    else {
        $nif = 0;
    }
	
	if (isset($_POST['domzip1'])  && $_POST['domzip2'].length>3) {
        $zip1 = $_POST['domzip1'];
    }
    else {
        $zip1 = 0;
    }
	
	if (isset($_POST['domzip2']) && $_POST['domzip2'].length>2) {
        $zip2 = $_POST['domzip2'];
    }
    else {
        $zip2 = 0;
    }
	
	if (isset($_POST['domlocal'])) {
        $local = $_POST['domlocal'];
    }
    else {
        $local = " ";
    }
	
	if (isset($_POST['domaddr'])) {
        $addr = $_POST['domaddr'];
    }
    else {
        $addr = " ";
    }

    if (isset($_POST['domname'])) {
        $name = $_POST['domname'];
    }
    else {
        $name = " ";
    }

    if (isset($_POST['domemail'])) {
        $email = $_POST['domemail'];
    }
    else {
        $email = " ";
    }

    if (isset($_POST['domphnr'])) {
        $phnr = $_POST['domphnr'];
    }
    else {
        $phnr = " ";
    }
	
	if (isset($_POST['state'])) {
        $state = $_POST['state'];
    }
    else {
        $state = '1';
    }


    if (isset($_POST['dombirth'])) {
        $birth = $_POST['dombirth'];
        if(strlen($birth)<8) {
            $birth = "NULL";
        }
        else {
			$birthday = '"' . date("Y") . '-' . date("m",strtotime($birth)) . '-' . date("d",strtotime($birth)) . '"';
            $birth = '"' . $birth . '"';
        }
    }
    else {
        $birth = "NULL";
    }
	echo $_POST['domid'];
	echo 'state: ' . $state;

    if($conn->query("SELECT * FROM entidades WHERE id = '" . $_POST['domid'] . "'")->num_rows) {
        $query = 'UPDATE entidades SET nome ="' . $name . '", telemovel="' . $phnr . '", morada="' . $addr . '", email="'. $email . '"' . ', nascimento='. $birth . ', 
		local="' . $local . '", zip1=' . $zip1 . ', zip2=' . $zip2 . ', nif=' . $nif . ', status=' . $state .
			' WHERE id=' . $_POST['domid'];
        if($conn->query($query)) {
			if(strcmp($birth, 'NULL')!=0){
				if($result = $conn->query("SELECT * FROM `contatos` where titulo like 'ANIVERSÁRIO' AND identidade=" . $_POST['domid']) ) {
					if($result->num_rows>0) {
						echo $birthday;
						$conn->query("UPDATE `contatos` SET date = " . $birthday . " WHERE id=" . $result->fetch_assoc()['id']);
					}
					else {
						$conn->query('INSERT INTO contatos(description, identidade, iduser, idimovel, titulo, date, completed, hour, duration) 
						  VALUES(" ",' . $_POST['domid'] . ',' . $id . ',0,"ANIVERSÁRIO",' . $birthday . ',0,0,0)');
					}
				}
			}
            header('Location: ../entidade.php?id=' . $_POST['domid'] . "&code=1");
        }
        echo "ERROR: Cannot update.";
    }
    else {
		echo 'INSERT INTO entidades(nome, telemovel, email, pertence_a, status, nascimento, morada, local, zip1, zip2, nif) 
				VALUES ("' . $name . '", "' . $phnr . '", "'. $email . '", ' . $_SESSION['login'] . ', 1, '. $birth . ',"' . $addr . '","' . $local . '" ,' . 
							 $zip1 . ',' . $zip2 . ',' . $nif . ')';
        if($newid = $conn->query('INSERT INTO entidades(nome, telemovel, email, pertence_a, status, nascimento, morada, local, zip1, zip2, nif) 
								  VALUES ("' . $name . '", "' . $phnr . '", "'. $email . '", ' . $_SESSION['login'] . ', 1, '. $birth . ',"' . $addr . '","' . $local . '" ,' . 
								 $zip1 . ',' . $zip2 . ',' . $nif . ')')) {
			$id_contato = $conn->insert_id;
			echo $birth;
			if(strcmp($birth, 'NULL')!=0){
				$conn->query('INSERT INTO contatos(description, identidade, iduser, idimovel, titulo, date, completed, hour, duration) 
						  VALUES(" ",' . $id_contato . ',' . $id . ',0,"ANIVERSÁRIO",' . $birthday . ',0,0,0)');
				echo "not null";
			}
			else {
				echo "null";
			}
			header('Location: ../entidade.php?id=' . $id_contato . "&code=2");
            
        }
		else {
        	echo "ERROR: Cannot insert.";
		}
    }
}
else {
    header('Location: ../404.php');
}

