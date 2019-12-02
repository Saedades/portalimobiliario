<?php
require_once 'connectDB.php';
include_once 'validate_session.php';

/*
'id_relation'
'id_contact'
'id_base_contact'
*/

function opposite_relation($Arel) {
	switch($Arel) {
		case 2: return 8; break;
		case 8: return 2; break;
		default: return $Arel;
	}
}

$relation = $_POST['id_relation'];
$contactA = $_POST['id_base_entidade'];
$contactB = $_POST['id_entidade'];


// ------------------------------- CONTACT B IS A RELATION TO BASE CONTACT ----------------------------------- //
$exists_same = $conn->query("SELECT * FROM entidades_assoc WHERE
						entidadeA = " . $contactB . " AND entidadeB = " . $contactA . " AND relacao = " . $relation)->num_rows;

$exists_opposite = $conn->query("SELECT * FROM entidades_assoc WHERE
            entidadeA = " . $contactA . " AND entidadeB = " . $contactB . " AND relacao = " . opposite_relation($relation))->num_rows;

if($exists_same==0 AND $exists_opposite==0) {
	$conn->query('INSERT INTO entidades_assoc(entidadeA, entidadeB, relacao) VALUES (' . $contactB . ',' . $contactA . ',' . $relation . ')');
}
else
  echo "New relationship added";

?>
