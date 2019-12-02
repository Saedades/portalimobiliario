<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function opposite_rel($id_relation, $conn) {
	$opposite = $conn->query('SELECT id FROM relacoes_familiares WHERE grupo IN (SELECT grupo FROM relacoes_familiares WHERE id =' . $id_relation.') AND id <> '. $id_relation)->fetch_assoc()['id'];
	
    /*if($id_relation>=19)
      return $id_relation;
    if($id_relation % 2 == 0 AND $id_relation!=0) {
      return $id_relation--;
    }
    else {
      return $id_relation++;
    }*/
	return $opposite;
}







if(isset($_POST['idrel'])) {
    echo '<h3>delete relacao</h3>';
    $exists = ($result = $conn->query('SELECT * FROM entidades_relacoes WHERE id =' . $_POST['idrel'])) ? 1 : 0;
    $success = ($exists==1) ? (($conn->query('DELETE FROM entidades_relacoes WHERE id =' . $_POST['idrel'])) ? 1 : -1 ) : 0;
    switch($success) {
        case 0:     $output = 1;    break;
        case 1:     $output = 2;    break;
        case -1:    $output = 0;   break;
    }
	echo $output;
}
elseif(isset($_POST['domfamiglia'])) {
    echo '<h3>adicionar relacao de familia</h3>';
    $exists = ($result = $conn->query(  'SELECT * FROM entidades_relacoes where (
                                        (objetoA='.$_POST['entidadeA'].' AND objetoB='.$_POST['domfamiglia'].' AND relacao='.$_POST['domrelfam'].') 
                                        OR 
                                        (objetoA='.$_POST['domfamiglia'].' AND objetoB='.$_POST['entidadeA'].' AND relacao='. opposite_rel($_POST['domrelfam'], $conn) .')) 
                                        AND tipo=1 AND apagado IS NULL')) ? $result->num_rows : 0;
	echo '<p>exists: '. $exists . '</p>';
    $success = ($exists==0) ? (($conn->query('INSERT INTO entidades_relacoes(tipo, objetoA, objetoB, relacao) 
                                            VALUES(1, '.$_POST['entidadeA'].', '.$_POST['domfamiglia']. ', ' . $_POST['domrelfam'] . ')')) ? 1 : -1) : 0;
    switch($success) {
        case 0:     $output = 3;    break;
        case 1:     $output = 4;    break;
        case -1:    $output = 0;   break;
    }
}
elseif(isset($_POST['domsocieta'])) {
    echo '<h3>adicionar relacao empresarial</h3>';
    echo 'SELECT * FROM entidades_relacoes where 
    objetoA='.$_POST['entidadeA'].' AND objetoB='.$_POST['domsocieta'].' AND relacao='.$_POST['domrelsoc'].' AND tipo=2 AND apagado IS NULL';
    $exists = ($result = $conn->query(  
        'SELECT * FROM entidades_relacoes where 
        objetoA='.$_POST['entidadeA'].' AND objetoB='.$_POST['domsocieta'].' AND relacao='.$_POST['domrelsoc'].' AND tipo=2 AND apagado IS NULL')) ? $result->num_rows : 0;
    $success = ($exists==0) ? (($conn->query('INSERT INTO entidades_relacoes(tipo, objetoA, objetoB, relacao, modificado) 
                VALUES(2, '.$_POST['entidadeA'].', '.$_POST['domsocieta']. ', ' . $_POST['domrelsoc'] . ',"' . date("Y-m-d h:i:s") . '")')) ? 1 : -1) : 0;
    switch($success) {
        case 0:     $output = 5;    break;
        case 1:     $output = 6;    break;
        case -1:    $output = 0;   break;
    }
}

if(isset($_POST['get_gender'])) {
	if($result = $conn->query('SELECT genero FROM entidades WHERE id ='. $_POST['entidade'])){
		echo $result->fetch_assoc()['genero'];
	}
	else {
		echo 'SELECT genero FROM entidades WHERE id ='. $_POST['entidade'];
	}
}
elseif(isset($success)) {
    header('Location: entidade.php?id=' . $_POST['entidadeA'] . '&tt=2' . '&op=' . $output);
}else{
    header('Location: ../partials/404.php');
}
