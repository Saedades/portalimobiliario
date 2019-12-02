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
	$email = (isset($post['domemail'])) ? $post['domemail'] : "";
  $phnr = (isset($post['domphnr'])) ? $post['domphnr'] : "";
  $phnr = (strlen($phnr)>=3) ? $phnr : 0;
	$state = (isset($post['estado'])) ? $post['estado'] : 0;
  $birth = (isset($post['dombirth'])) ? $post['dombirth'] : "";
  $tipo = (isset($post['domtipo'])) ? $post['domtipo'] : 0;
  $interesse = (isset($post['dominteresse'])) ? $post['dominteresse'] : 0;
  if(strlen($birth)<8) {
    $birth = "NULL";
    $birthday = "NULL";
  }
  else {
    $birthday = '"' . date("Y") . '-' . date("m",strtotime($birth)) . '-' . date("d",strtotime($birth)) . '"';
    $birth = '"' . $birth . '"';
  }

  //-------------------quuuuueries-------------------------//
  $selection_query = "SELECT * FROM entidades WHERE id = " . $id;
	$update_query = 'UPDATE entidades SET
					nome ="' . $name . '",
					telemovel="' . $phnr . '",
					morada="' . $addr . '",
					email="'. $email . '"' . ',
					nascimento='. $birth . ',
					local="' . $local . '",
					zip1=' . $zip1 . ',
					zip2=' . $zip2 . ',
					nif=' . $nif . ',
          estado=' . $state . ',
          tipo=' . $tipo . ',
          interesse=' . $interesse . '
			    WHERE id=' . $id;
	$insert_query = 'INSERT INTO entidades(nome,telemovel,pertence_a,morada,email,nascimento,local,zip1,zip2,nif,estado,tipo,interesse) VALUES(
					"' . $name . '",
          "' . $phnr . '",
          '  . $user . ',
					"' . $addr . '",
					"'. $email . '"' . ',
					' . $birth . ',
					"' . $local . '",
					' . $zip1 . ',
					' . $zip2 . ',
					' . $nif . ',
          ' . $state . ',
          ' . $tipo . ',
          ' . $interesse .
      ')';



  //---------------------------------------------LOGIC--------------------------------------------------//
  if($conn->query($selection_query)->num_rows>0) {
    echo "updating";
    if($conn->query($update_query)) {
      if(strcmp($birth, 'NULL')!=0){
        $result = $conn->query("SELECT * FROM `contatos` where titulo like 'ANIVERSÁRIO' AND identidade=" . $id);
        if($result->num_rows>0) {
          $conn->query("UPDATE `contatos` SET date = " . $birthday . " WHERE id=" . $result->fetch_assoc()['id']);
        }
        else {
          $conn->query('INSERT INTO contatos(description, identidade, iduser, idimovel, titulo, date, completed, hour, duration)
                        VALUES(" ",' . $id . ',' . $_SESSION['login']. ', 0 , "ANIVERSÁRIO",' . $birthday . ',0,0,0)');
        }
      }
      header('Location: entidade.php?id=' . $id . "&code=1");
    } else {
      echo "ERROR: Cannot update.<br>" . mysqli_error($conn);
    }
    echo "<br>end of update<br>";
  }
  else {
    echo "<p>INSERT QUERY:<br></p>" . $insert_query . "<br>";
    if($conn->query($insert_query)) {

			$id_novo = $conn->insert_id;
			if(strcmp($birth, 'NULL')!=0){
				$conn->query('INSERT INTO contatos(description, identidade, iduser, idimovel, titulo, date, completed, hour, duration)
						  VALUES(" ",' . $id_novo . ',' . $id . ',0,"ANIVERSÁRIO",' . $birthday . ',0,0,0)');
			}
			  header('Location: entidade.php?id=' . $id_novo . "&code=2");
    }
		else {
      echo "ERROR: Cannot insert.<br>" . 'INSERT INTO contatos(description, identidade, iduser, idimovel, titulo, date, completed, hour, duration)
      VALUES(" ",' . $id_novo . ',' . $id . ',0,"ANIVERSÁRIO",' . $birthday . ',0,0,0)';
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
		default: echo "<p>case default</p>"; break;
	}
}
else {
	echo '<h2>No input values</h2>';
}
