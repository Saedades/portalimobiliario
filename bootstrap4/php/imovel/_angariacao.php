<?php

    require_once('../partials/connectDB.php');
    include_once '../partials/validate_session.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if($_POST['action_type']==1) {

        $has_imovel1_grouped = (($ang1 = $conn->query('SELECT angariacao FROM imoveis WHERE id=' . $_POST[ 'imovel1'])->fetch_assoc()['angariacao']) == 0) ? 0 : 1;
        $has_imovel2_grouped = (($ang2 =$conn->query('SELECT angariacao FROM imoveis WHERE id=' . $_POST[ 'imovel2'])->fetch_assoc()['angariacao']) == 0) ? 0 : 1;
    
        if($has_imovel1_grouped AND !$has_imovel2_grouped AND $ang1!=$ang2) {
            //group 2 to 1
            echo('2');
            $conn->query('UPDATE imoveis SET angariacao = ' . $ang1 . ' WHERE id = ' . $_POST[ 'imovel2']);
        }
        elseif(!$has_imovel1_grouped AND $has_imovel2_grouped AND $ang1!=$ang2) {
            //group 1 to 2
            echo('2');
            $conn->query('UPDATE imoveis SET angariacao = ' . $ang2 . ' WHERE id = ' . $_POST[ 'imovel1']);
        }
        elseif(!$has_imovel1_grouped AND !$has_imovel2_grouped) {
            //new group
            echo('1');
            $conn->query('UPDATE imoveis SET angariacao = ' . $_POST[ 'angariacao'] . ' WHERE id = ' . $_POST[ 'imovel1'] . ' OR id = ' . $_POST[ 'imovel2']);
        }
        else {
            //error
            echo('-1');
    
        }
    }
    elseif($_POST['action_type']==2) {
        if($conn->query('UPDATE imoveis SET angariacao=0 WHERE id=' . $_POST['imovel'])) {
            echo 1;
        }
        else {
            echo -1;
        }
    }
    elseif($_POST['action_type']==3) {
        if($conn->query('UPDATE imoveis SET angariacao=' . $_POST['angariacao'] . ' WHERE id=' . $_POST['imovel1'])) {
            echo 1;
        }
        else {
            echo -1;
        }
    }
    elseif($_POST['action_type']==4) {
        $max_ang = intval($conn->query('SELECT MAX(angariacao) as maxval FROM imoveis')->fetch_assoc()['maxval']) + 1;
        if($conn->query('UPDATE imoveis SET angariacao=' . $max_ang . ' WHERE id=' . $_POST['imovel1'])) {
            echo 1;
        }
        else {
            echo -1;
        }
    }
    elseif($_POST['action_type']==5) {
        $ang = intval($conn->query('SELECT angariacao FROM imoveis WHERE id =' . $_POST['imovel1'])->fetch_assoc()['angariacao']);
        if($conn->query('UPDATE imoveis SET angariacao=0 WHERE angariacao=' . $ang)) {
            echo 1;
        }
        else {
            echo -1;
        }
    }
    

    
