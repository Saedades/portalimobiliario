<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




// ----------------------------------------//
// Inactiva as relacoes familiares e
// profissionais de uma entidade pelo
// ID de entidade
//-----------------------------------------//
function apagar_relacoes($conn, $id) {
	if($conn->query("UPDATE entidades_assoc SET estado = 0 WHERE entidadeA = " . $id . " OR entidadeB = " . $id ))
		echo 1;
	else
		echo 0;
}

// ----------------------------------------//
// Inactiva uma relacao pelo ID de relacao
//-----------------------------------------//
function apagar_relacoes_ID($conn, $post) {
  $id = $post['id_of_relation'];
  $conn->query('UPDATE entidades_assoc SET estado=0 WHERE id = ' . $id);
}



// ----------------------------------------//
// Apaga permanentemente uma relacao pelo
// ID de relacao
//-----------------------------------------//
function esquecer_relacoes($conn, $id) {
	if($conn->query("DELETE FROM entidades_assoc WHERE entidadeA = " . $id . " OR entidadeB = " . $id ))
		echo 1;
	else
		echo 0;
}



// ----------------------------------------//
// Inativa uma relacao pelo ID de entidade
//-----------------------------------------//
function apagar_entidade($conn, $id) {
	if($conn->query("UPDATE entidades SET estado = 0 WHERE id = " . $id))
		echo 1;
	else
		echo 0;
}


// ----------------------------------------//
// Apaga permanentemente uma relacao pelo
// ID de entidade
//-----------------------------------------//
function esquecer_entidade($conn, $id) {
	if($conn->query('UPDATE entidades SET
                  nome ="",
                  telemovel=NULL,
                  morada="",
                  email="",
                  nascimento=NULL,
                  local="",
                  zip1=0,
                  zip2=0,
                  nif=0,
                  estado=-1
                  WHERE id = ' . $id))
		echo 1;
	else
		echo 'UPDATE entidades SET
    nome ="",
    telemovel=NULL,
    morada="",
    email="",
    nascimento=NULL,
    local="",
    zip1=0,
    zip2=0,
    nif=0,
    estado=-1
    WHERE id = ' . $id;
}



// ----------------------------------------//
// Adiciona uma entidade e cria
//-----------------------------------------//
function add_entidade($conn, $post, $user) {

	  	$id = (isset($post['domid'])) ? $post['domid'] : 0;
	  	$nif = (isset($post['domnif'])) ? $post['domnif'] : 0;
	  	$nif = (strlen($nif)>=3) ? $nif : 0;
	  	$zip1 = (isset($post['domzip1'])) ? $post['domzip1'] : 0;
	  	$zip1 = (strlen($zip1)>=3) ? $zip1 : 0;
	  	$zip2 = (isset($post['domzip2'])) ? $post['domzip2'] : 0;
	  	$zip2 = (strlen($zip2)>=2) ? $zip2 : 0;
		$local = (isset($post['domlocal'])) ? $post['domlocal'] : 0;
		$addr = (isset($post['domaddr'])) ? $post['domaddr'] : "";
	  	$name = (isset($post['domname'])) ? $post['domname'] : "";
	  	$ape = (isset($post['domape']) AND strlen($post['domape'])>2) ? $post['domape'] : "";
	  	$nick = (isset($post['domnick']) AND strlen($post['domnick'])>2) ? $post['domnick'] : "";
		$email = (isset($post['domemail'])) ? $post['domemail'] : "";
	  	$phnr = (isset($post['domtele'])) ? $post['domtele'] : "";
	  	$phnr = (strlen($phnr)>=3) ? $phnr : 0;
		$state = (isset($post['estado'])) ? $post['estado'] : 0;
	  	$birth = (isset($post['domnasc']) AND strlen($post['domnasc'])>2 ) ? $post['domnasc'] . ' 00:00:00' : "";
	  	$state = (isset($post['domestado']) AND strlen($post['domestado'])>2) ? $post['domestado'] : 0;
	  	$tipo = (isset($post['domtipo'])) ? $post['domtipo'] : 0;
	  	$categoria = (isset($post['domcategoria'])) ? $post['domcategoria'] : 1;
	  	$lead = (isset($post['domlead'])) ? $post['domlead'] : 0;
	  	$ident = (isset($post['domident'])) ? $post['domident'] : "";
	  	$valid = (isset($post['domvalid']) AND strlen($post['domvalid'])>2) ? '"' . $post['domvalid'] . ' 00:00:00"' : 'NULL';
	  	$filhos = (isset($post['domfilhos']) AND strlen($post['domfilhos'])>0) ? $post['domfilhos'] : 0;
	  	$genero = (isset($post['domgen'])) ? $post['domgen'] : 3;
	  	$rating = (isset($post['domrating'])) ? $post['domrating'] : 'NULL';
		$classificador = (isset($post['domclassificador'])) ? $post['domclassificador'] : 0;
	
		if(strlen($birth)<8) {
			$birth = "NULL";
			$birthday = "NULL";
		}
		else {
			$birthday = '"' . date("Y") . '-' . date("m",strtotime($birth)) . '-' . date("d",strtotime($birth)) . ' 00:00:00"';
			$birth = '"' . $birth . '"';
		}

  //-------------------quuuuueries-------------------------//
  $selection_query = "SELECT * FROM entidades WHERE id = " . $id;
	$update_query = 'UPDATE entidades SET
          nome ="' . $name . '",
          apelido ="' . $ape . '",
          nickname ="' . $nick . '",
			telemovel="' . $phnr . '",
			morada="' . $addr . '",
			email="'. $email . '"' . ',
			nascimento='. $birth . ',
			local="' . $local . '",
			zip1=' . $zip1 . ',
			zip2=' . $zip2 . ',
			nif=' . $nif . ',
          tipo=' . $tipo . ',
          categoria= ' . $categoria . ',
          lead=' .$lead. ',
          identificacao="' . $ident . '",
          validade=' . $valid . ',
          filhos=' . $filhos . ',
          genero=' . $genero . ',
          rating=' . $rating . ',
		  classificador=' . $classificador . '
          WHERE id=' . $id;
  echo $update_query;
	$insert_query = 'INSERT INTO entidades(nome,apelido,nickname, telemovel,user,morada,email,nascimento,local,zip1,zip2,nif,tipo,categoria,lead, identificacao, validade, filhos, genero, rating) VALUES(
          "' . $name . '",
          "' . $ape . '",
          "' . $nick . '",
          "' . $phnr . '",
          '  . $user . ',
					"' . $addr . '",
					"'. $email . '"' . ',
					' . $birth . ',
					"' . $local . '",
					' . $zip1 . ',
					' . $zip2 . ',
					' . $nif . ',
          ' . $tipo . ',
          ' . $categoria . ',
          ' . $lead . ',
          "' . $ident . '",
          ' . $valid . ',
          ' . $filhos . ',
          ' . $genero . ',
          ' . $rating .
      ')';



    //---------------------------------------------LOGIC--------------------------------------------------//

    //-- if entity already exists entity --//
    if($conn->query($selection_query)->num_rows>0) {
        //-- update entity --//
        if($conn->query($update_query)) 
        {
            //-- if birth is set --//
            if(strcmp($birth, 'NULL')!=0)
            {
                $result = $conn->query("SELECT * FROM alertas where entidade=" . $id);
                
                if($result->num_rows>0) {
                    //-- if an anniversary alert already exists --//
                    echo '<p>UPDATE alertas SET alertar_em = ' . $birthday . ' WHERE id=' . $result->fetch_assoc()['id'] . '</p>';
                    if($conn->query('UPDATE alertas SET alertar_em = ' . $birthday . ' WHERE id="' . $result->fetch_assoc()['id'] . '"'))
                        echo '<p>Success A</p>';
                    else
                        echo '<p>Error A! '.mysqli_error($conn).'</p>';
                }
                else {
                    //-- if an anniversary alert doesnt exist --//
                    echo '<p>INSERT INTO alertas(entidade, user, titulo, alertar_em) VALUES('. $id . ',' . $_SESSION['login'] . ',"Aniversário",' . $birthday . ')' . '</p>';
                    if($conn->query('INSERT INTO alertas(entidade, user, titulo, alertar_em)
                                VALUES('. $id . ',' . $_SESSION['login'] . ',"Aniversário",' . $birthday . ')'))
                        echo '<p>Success B</p>';
                    else
                        echo '<p>Error B! '.mysqli_error($conn).'</p>';
                }
            }
            header('Location: entidade.php?id=' . $id . "&code=1");
        } 
        else 
        {
            echo "ERROR: Cannot update.<br>" . mysqli_error($conn);
        }
        echo "<br>end of update<br>";
    }
    else {
        echo "<p>INSERT QUERY:<br></p>" . $insert_query . "<br>";
        if($conn->query($insert_query)) 
        {
            $id_novo = $conn->insert_id;
            if(strcmp($birth, 'NULL')!=0)
            {
                if($conn->query('INSERT INTO alertas(entidade, user, titulo, alertar_em)
                VALUES('. $id_novo . ',' . $_SESSION['login'] . ',"Aniversário",' . $birthday . ')'))
                    echo '<p>Success C</p>';
                else
                    echo '<p>Error! C'.mysqli_error($conn).'</p>';
            }
            header('Location: entidade.php?id=' . $id_novo . "&code=2");
        }
        else 
        {
            header('Location: entidade.php');
        }
    }


  //-------------------------------------END-OF-LOGIC--------------------------------------------------//
}

function opposite_relation($Arel) {
	switch($Arel) {
		case 2: return 8; break;
		case 8: return 2; break;
		default: return $Arel;
	}
}

function adicionar_relacao($conn, $post) {

  $relation = $post['id_relation'];
  $contactA = $post['id_base_entidade'];
  $contactB = $post['id_entidade'];

  // ------------------------------- CONTACT B IS A RELATION TO BASE CONTACT ----------------------------------- //
  $exists_same = $conn->query("SELECT * FROM entidades_assoc WHERE
              entidadeA = " . $contactB . " AND entidadeB = " . $contactA . " AND relacao = " . $relation)->num_rows;

  $exists_opposite = $conn->query("SELECT * FROM entidades_assoc WHERE
              entidadeA = " . $contactA . " AND entidadeB = " . $contactB . " AND relacao = " . opposite_relation($relation))->num_rows;

  if($exists_same>0) {
    echo "UPDATE entidades_assoc SET estado=1 WHERE entidadeA = " . $contactB . " AND entidadeB = " . $contactA . " AND relacao = " . $relation;
    $conn->query("UPDATE entidades_assoc SET estado=1 WHERE entidadeA = " . $contactB . " AND entidadeB = " . $contactA . " AND relacao = " . $relation);
  }
  elseif($exists_opposite>0) {
    $conn->query("UPDATE entidades_assoc SET estado=1 WHERE entidadeA = " . $contactA . " AND entidadeB = " . $contactB . " AND relacao = " . opposite_relation($relation))->num_rows;
  }
  elseif($exists_same==0 AND $exists_opposite==0) {
    $conn->query('INSERT INTO entidades_assoc(entidadeA, entidadeB, relacao) VALUES (' . $contactB . ',' . $contactA . ',' . $relation . ')');
  }

}

function add_entidades_assoc_por_entidade($conn,$id) {
	if($conn->query("UPDATE entidades_assoc SET estado = 0 WHERE entidadeA = " . $id . " OR entidadeB = " . $id))
		echo 1;
	else
		echo 0;
}

function add_entidades_assoc_por_id($conn,$id) {
	if($conn->query("UPDATE entidades_assoc SET estado = 0 WHERE id = " . $id))
		echo 1;
	else
		echo 0;
}

function add_entidades_imoveis_por_entidade($conn,$id) {
	if($conn->query("UPDATE entidades_imoveis SET estado = 0 WHERE entidade = " . $id))
		echo 1;
	else
		echo 0;
}

function delete_entidades_imoveis_por_id($conn,$id) {
	if($conn->query("UPDATE entidades_imoveis SET estado = 0 WHERE id = " . $id))
		echo 1;
	else
		echo 0;
}

function add_imovel_entidades_relation($conn,$imovel,$entidade) {
  $test_query = 'SELECT * FROM  imoveis_entidades WHERE imovel=' . $imovel . ' AND entidade = ' . $entidade;
  if($conn->query($test_query)->num_rows>0) {
    $query = 'UPDATE imoveis_entidades SET date_deleted=null WHERE imovel=' . $imovel . ' AND entidade = ' . $entidade;
  }
  else {
    $query = 'INSERT INTO imoveis_entidades(entidade, imovel) VALUES(' . $entidade . ',' . $imovel . ')';
  }

  if($conn->query($query))
    echo 1;
  else
    echo 0;

}

function delete_imovel_entidades_relation($conn,$imovel,$entidade) {
  $test_query = 'SELECT * FROM  imoveis_entidades WHERE imovel=' . $imovel . ' AND entidade = ' . $entidade;
  if($conn->query($test_query)->num_rows>0) {
    $query = 'UPDATE imoveis_entidades SET date_deleted="' . date("Y-m-d\TH:i:s") . '" WHERE imovel=' . $imovel . ' AND entidade = ' . $entidade;
  }

  if($conn->query($query))
    echo 1;
  else
    echo $query;

}

//-------------------------------------------THIS IS LIKE THE FUNCTION MAIN------------------------------------------//

if(isset($_REQUEST['action_type'])) {
  echo "controller";
	$type = $_REQUEST['action_type'];
	//$id = $_REQUEST['id'];
	switch($type){
		//case 1: delete_entidades_por_id($conn,$id); /*delete_entidades_assoc_por_entidade($conn,$id);*/ echo "<p>case 12</p>"; break;
    //case 2: delete_entidades_assoc_por_entidade($conn,$id);  break;
		case 3: add_entidade($conn, $_POST, $_SESSION['login']);  break;
		case 4: apagar_entidade($conn, $_POST['id_delete']); /*apagar_relacoes($conn, $_POST['id_delete']);*/ break;
		case 5: esquecer_entidade($conn, $_POST['id_delete']);  esquecer_relacoes($conn, $_POST['id_delete']); break;
		case 6: echo adicionar_relacao($conn, $_POST); echo "what"; break;
		case 7: apagar_relacoes_ID($conn, $_POST); break;
		case 10: add_imovel_entidades_relation($conn,$_POST['id_imovel'],$_POST['id_entidade']); break;
		case 11: delete_imovel_entidades_relation($conn,$_POST['id_imovel'],$_POST['id_entidade']); break;
		case 49: 
			$conn->query('DELETE FROM entidades WHERE id = '.$_POST['id_delete']); 
			$conn->query('DELETE FROM contatos WHERE entidade = '.$_POST['id_delete']);
			$conn->query('DELETE FROM entidades_users WHERE entidade = '.$_POST['id_delete']);
			$conn->query('DELETE FROM contatos_notas WHERE contato = '.$_POST['id_delete']);
			break;
		default: echo "<p>case default</p>"; break;
	}
}
else {
	echo '<h2>No input values</h2>';
}
