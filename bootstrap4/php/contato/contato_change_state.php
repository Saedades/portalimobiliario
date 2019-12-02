<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
$contato_id = $_POST['contato'];
$state = $_POST['state'];
if(strlen($conn->query('SELECT completed FROM contatos WHERE id = ' . $contato_id)->fetch_assoc()['completed'])>1) {
  $conn->query('UPDATE contatos SET completed = NULL');
  echo '0';
}
else {
  $conn->query('UPDATE contatos SET completed = "' . date('Y-m-d')  . '" WHERE id = ' . $contato_id);
  $contato_date = $conn->query("SELECT date FROM contatos WHERE id = " . $contato_id)->fetch_assoc()['date'];
  //$conn->query("INSERT INTO contatos_details(id, scheduled, completed) VALUES (" . $contato_id . ',"' . $contato_date . '","' . date('Y-m-d') . '")');
  echo '1';
}
