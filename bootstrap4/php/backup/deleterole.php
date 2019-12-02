<?php
//echo print_r($_POST, true);
require_once 'connectDB.php';
include_once 'validate_session.php';

$user_id = $_POST['id_user'];
$team_id = $_POST['id_team'];
$role_id = $_POST['id_role'];

$role_title = $conn->query("SELECT titulo FROM cargos WHERE idcargos=" . $role_id)->fetch_assoc()['titulo'];
$team_title = $conn->query("SELECT titulo FROM equipas WHERE idequipas=" . $team_id)->fetch_assoc()['titulo'];

$isadmin = $conn->query("SELECT admin FROM foco20.users WHERE idusers=" . $user_id);

if($isadmin) {
    $isinteam = $conn->query("SELECT * FROM users_equipas WHERE user=". $user_id . " AND equipa=" . $team_id)->num_rows;
    if($isinteam) {
        $isinrole = $conn->query("SELECT * FROM users_cargos WHERE users=". $user_id . " AND equipa=" . $team_id . " AND cargos=" . $role_id)->num_rows;
        if($isinrole){
            if($conn->query("DELETE FROM users_cargos WHERE users=". $user_id . " AND equipa=" . $team_id . " AND cargos=" . $role_id))
                echo "You deleted your role as " . utf8_encode($role_title) . " in team " .$team_title . ".";
            else
                echo "Database error.";
        }
        else {
            echo "1: You're not a " . $role_title . " on " . $team_title ;
        }
    }
    else {
        echo "2: You're not even on that team.";
    }
}
else
{
    echo "You are not admin";
}

?>