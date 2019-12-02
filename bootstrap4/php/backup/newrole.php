<?php
//echo print_r($_POST, true);
require_once 'connectDB.php';
include_once 'validate_session.php';

$user_id = $_POST['id_user'];
$team_id = $_POST['id_team'];
$role_id = $_POST['id_role'];

$isadmin = $conn->query("SELECT admin FROM foco20.users WHERE idusers=" . $user_id);

if($isadmin) {
    $isinteam = $conn->query("SELECT * FROM users_equipas WHERE user=". $user_id . " AND equipa=" . $team_id)->num_rows;
    if($isinteam) {
        $isinrole = $conn->query("SELECT * FROM users_cargos WHERE users=". $user_id . " AND equipa=" . $team_id . " AND cargos=" . $role_id)->num_rows;
        if($isinrole){
            $role_title = $conn->query("SELECT titulo FROM cargos WHERE idcargos=" . $role_id)->fetch_assoc()['titulo'];
            $team_title = $conn->query("SELECT titulo FROM equipas WHERE idequipas=" . $team_id)->fetch_assoc()['titulo'];
            echo "1: You already are " . $role_title . " in team " .$team_title . ".";
        }
        else {
            if($conn->query("INSERT INTO users_cargos(users, equipa, cargos ) VALUES(". $user_id . " , " . $team_id . " , " . $role_id . ")"))
                echo "New role in your profile!";
            else
                echo "0";
        }
    }
    else {
        $conn->query("INSERT INTO users_equipas(user, equipa) VALUES(". $user_id . " , " . $team_id . ")");
        $isinrole = $conn->query("SELECT * FROM users_cargos WHERE users=". $user_id . " AND equipa=" . $team_id . " AND cargos=" . $role_id);
        if($isinrole){
            $role_title = $conn->query("SELECT titulo FROM cargos WHERE idcargos=" . $role_id)->fetch_assoc()['titulo'];
            $team_title = $conn->query("SELECT titulo FROM equipas WHERE idequipas=" . $team_id)->fetch_assoc()['titulo'];
            echo "2: You already are " . $role_title . " in team " .$team_title . ".";
        }
        else {
            if($conn->query("INSERT INTO users_cargos(users, equipa, cargos) VALUES(". $user_id . " , " . $team_id . " , " . $role_id . ")"))
                echo "New role in your profile!";
            else
                echo "0";
        }
    }
}
else
{
    echo "You are not admin";
}

?>