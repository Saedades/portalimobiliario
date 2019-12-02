<?php

    require_once('../partials/connectDB.php');
    include_once '../partials/validate_session.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $obj_raw = $conn->query("SELECT * FROM contatos WHERE id = " . $_POST['id'])->fetch_assoc();
    $dt_agendado = new DateTime($obj_raw['agendado']);
    $obj = array(
        'd_agendado'  => $dt_agendado->format('Y-m-d') , 
        't_agendado'  => $dt_agendado->format('H:i'), 
        'descricao' => $obj_raw['descricao'], 
        'estado'      => $obj_raw['estado'], 
        'completado'  => $obj_raw['completado'],
        'tipo'      => $obj_raw['tipo']);
    //$obj = array('nome' => '23/01/2019', 'apelido' => '13:55');

    echo json_encode($obj);
   