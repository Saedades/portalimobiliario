<?php

    require_once '../partials/connectDB.php';
    include_once '../partials/validate_session.php';


if(isset($_POST['action']) AND $_POST['action']==2) {

   $entity_notes = ($result = $conn->query('SELECT * FROM entidades_notas WHERE entidade=' . $_POST['entity'] . ' ORDER BY criado DESC') ) ? $result->fetch_all(MYSQLI_ASSOC) : [] ;
   echo json_encode($entity_notes);

}
elseif(isset($_POST['action']) AND $_POST['action']==3) {

    $entity_tasks = $conn->query('SELECT * FROM contatos WHERE entidade=' . $_POST['entity'] . ' ORDER BY estado, agendado ASC')->fetch_all(MYSQLI_ASSOC);
    echo json_encode($entity_tasks);

}
elseif(isset($_POST['action']) AND $_POST['action']==4) {

    if($conn->query('INSERT INTO entidades_notas(entidade, descricao) VALUES('.$_POST['entity'] . ',"' .$_POST['descricao'].'")')) {
        $last_id = $conn->insert_id;
        $entity_note = $conn->query('SELECT * FROM entidades_notas WHERE id=' . $last_id)->fetch_assoc();
        echo json_encode($entity_note);
    }else {
        echo '0';
    }

}
elseif(isset($_POST['action']) AND $_POST['action']==5) {

    $seguimento_info = $conn->query('SELECT * FROM contatos WHERE id=' . $_POST['idseg'])->fetch_assoc();
    echo json_encode($seguimento_info);

}
elseif(isset($_POST['action']) AND $_POST['action']==6) {

    if($conn->query('INSERT INTO contatos_notas(contato, descricao) VALUES('.$_POST['idseg'] . ',"' .$_POST['descricao'].'")')) {
        $last_id = $conn->insert_id;
        $seg_note = $conn->query('SELECT * FROM contatos_notas WHERE id=' . $last_id)->fetch_assoc();
        echo json_encode($seg_note);
    }else {
        echo '0';
    }

}
elseif(isset($_POST['action']) AND $_POST['action']==7) {
    if($conn->query('UPDATE contatos SET estado = 1, completado="' . date("Y-m-d H:i:s") . '" WHERE id=' . $_POST['idseg'])) {
        if($result = $conn->query('SELECT entidade FROM contatos WHERE id =' . $_POST['idseg'])->fetch_assoc()['entidade']) {
            echo $result;
        }
    }
}
elseif(isset($_POST['action']) AND $_POST['action']==8) {
    $contatos_notas = $conn->query('SELECT * FROM contatos_notas WHERE contato=' . $_POST['idseg'] . ' ORDER BY criado DESC')->fetch_all(MYSQLI_ASSOC);
    echo json_encode($contatos_notas);
}
elseif(isset($_POST['action']) AND $_POST['action']==55) {
    $nome_entidade = $conn->query('SELECT CONCAT(nome, " ", apelido) as fullname, id FROM entidades WHERE id IN ( SELECT entidade FROM contatos WHERE id =' . $_POST['idseg'] . ')')->fetch_assoc();
    echo json_encode($nome_entidade);
}
else {

    $entidade = $conn->query('SELECT * FROM entidades WHERE id =' . $_POST['entity'])->fetch_assoc();
    $nome = $entidade['nome'];
    $apelido = $entidade['apelido'];
    $telemovel = $entidade['telemovel'];
    $email = $entidade['email'];
    $categoria  = 	( $result = $conn->query('SELECT nome FROM categorias WHERE id =' . $entidade['categoria']) ) 									? $result->fetch_assoc()['nome'] 		: 0 ;
    $rating     = 	( $result = $conn->query('SELECT CONCAT(nome, ": ", descricao) as nometotal FROM ratings WHERE id =' . $entidade['rating']) ) 	? $result->fetch_assoc()['nometotal']	: '';
    $lead     	= 	( $result = $conn->query('SELECT nome FROM leads WHERE id =' . $entidade['lead']) ) 											? $result->fetch_assoc()['nome'] 		: '';

    $arr = array(   'nome' => $nome, 'apelido' => $apelido, 'telemovel' => $telemovel, 'email' => $email, 'categoria' => $categoria, 'rating' => $rating,
                    'lead' => $lead);

    echo json_encode($arr);

}


?>
