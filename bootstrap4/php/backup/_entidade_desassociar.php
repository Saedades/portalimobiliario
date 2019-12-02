<?php
require_once 'connectDB.php';
include_once 'validate_session.php';

$id = $_POST['id_of_relation'];
$conn->query('DELETE FROM entidades_assoc WHERE id = ' . $id);

?>
