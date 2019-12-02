<?php
require_once('../partials/connectDB.php');
include_once '../partials/validate_session.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

var_dump($_POST);

$query = 'UPDATE entidades SET ' .
((isset($_POST['dompais']) AND strlen($_POST['dompais'])>1) ? ('pais=' . $_POST['dompais'] . ',') : '')
. 
((isset($_POST['domaddr']) AND strlen($_POST['domaddr'])>1) ? ('morada="' . $_POST['domaddr'] . '",') : '')
. 
((isset($_POST['domlocal']) AND strlen($_POST['domlocal'])>1) ? ('local="' . $_POST['domlocal'] . '",') : '')
. 
((isset($_POST['domzip1']) AND strlen($_POST['domzip1'])>1) ? ('zip1=' . $_POST['domzip1'] . ',') : '')
. 
((isset($_POST['domzip2']) AND strlen($_POST['domzip2'])>1) ? ('zip2=' . $_POST['domzip2'] . ',') : '')
.
' WHERE id =' . $_POST['entidade'];
$query = str_replace(", WHERE"," WHERE",$query);
echo $query;

$result = $conn->query($query);
                
echo $result;

header('Location: entidade.php?id=' . $_POST['entidade'] . '&tt=3');
