<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

function color_inverse($color){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return '000000'; }
    $rgb = '';
    for ($x=0;$x<3;$x++){
        $c = 255 - hexdec(substr($color,(2*$x),2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }
    return '#'.$rgb;
}

mysqli_set_charset($conn,"utf8");

$isadmin=$conn->query("SELECT admin FROM foco200.users WHERE id=" . $id)->fetch_assoc()['admin'];
$name=$conn->query("SELECT name FROM users WHERE id = " . $id)->fetch_assoc()['name'];
$email=$conn->query("SELECT email FROM users WHERE id = " . $id)->fetch_assoc()['email'];
$status=$conn->query("SELECT status FROM users WHERE id = " . $id)->fetch_assoc()['status'];
$profile = 'php/' . $conn->query("SELECT photo FROM users WHERE id = " . $id)->fetch_assoc()['photo'];
/*
$teams = $conn->query("SELECT EE.idequipas as idequipa, EE.titulo as name, EE.cor as color, CC.idcargos as idcargo, CC.titulo as cargo FROM foco200.equipas as EE
                              INNER JOIN foco200.users_equipas as UE ON UE.equipa = EE.idequipas
                              INNER JOIN foco200.users_cargos as UC ON UC.equipa = EE.idequipas
                              INNER JOIN foco200.cargos as CC ON CC.idcargos = UC.cargos
                              WHERE UE.user = " . $id . " AND CC.idcargos <> 1 order BY EE.titulo")->fetch_all(MYSQLI_ASSOC);*/
$teams = array();

//$teams_u = $conn->query("SELECT idequipas as id, titulo as name, cor as color FROM foco200.equipas")->fetch_all(MYSQLI_ASSOC);
//$roles_u = $conn->query("SELECT idcargos as id, titulo as name FROM foco200.cargos")->fetch_all(MYSQLI_ASSOC);
$teams_u = array();
$roles_u =array();


$entidadeslista = $conn->query("SELECT * FROM entidades WHERE user=" . $id . " AND apagado IS NULL")->fetch_all(MYSQLI_ASSOC);


/*$contatos = $conn->query("SELECT * FROM (SELECT rt.* , rdt.completed as detail_completed, MAX(rdt.created_at) as created_at FROM foco200.contatos rt
                                    LEFT JOIN contatos_details as rdt ON rdt.idcontato = rt.id WHERE rt.iduser=" . $_SESSION['login'] . " GROUP BY rt.id) AS new_table ORDER BY completed ASC, date")->fetch_all(MYSQLI_ASSOC);
*/
$contatos=array();
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Foco 20</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">



    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>

        .to_complete{
            background-color: #ffffff!important;
        }

        .hoveroptions{

        }

        #plusrole {
            height:4vh;
            width:4vh;
            margin:1vh;
            color:blue;
            font-size:4vh
        }

        #plusrolecontact {
            color:blue;
            font-size:3vh;
            line-height:1vh;
            margin-left:1vw;
        }

        #plusrole:hover {
            cursor: pointer;
            color: #5e86d0;
        }

        #plusrolecontact:hover {
            cursor: pointer;
            color: #5e86d0;
        }

        #savenewrole {
            color:darkgreen;
            font-size: 3vh;
            position:absolute;
            top:-2.3vh;
        }

        #savenewrole:hover, #savenewrole:focus {
            cursor: pointer;
            color: #31855c;
        }

        .a_contact {
            background-color:white;
        }

        .a_contact:hover {
            background-color: rgba(77, 117, 224, 0.2);
        }

        .contato_click:hover {
            color: white;
            background-color: #8397d1 !important;
            cursor: pointer;
        }

        .contato_click:active {
            color: white;
            background-color: #6b86d3 !important;
        }

        .a_contact:hover {
            color: white;
            background-color: #8397d1;
            cursor: pointer;
        }

        .a_contact:active {
            color: white;
            background-color: #6b86d3;
        }

		#profilePic {
			cursor: pointer;
        }

        .eqb:hover {
            color: white;
            background-color: #8397d1;
            cursor: pointer;
        }

        .eqb:active {
            color: white;
            background-color: #6b86d3;
        }

        .eqa {
            color: inherit;
            text-decoration: inherit;
            text-decoration: none;
        }
        .eqa:hover {
            color: inherit;
            text-decoration: inherit;
            text-decoration: none;
        }
    </style>

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <?php include '../partials/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include '../partials/topbar.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">


                <form style="unset:all" id="theform" enctype="multipart/form-data" action="update_profile.php" method="POST">

                    <div class="row">
                        <div class="card shadow" style="width: 12vw">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary"><?php echo $name;?></h6>
                            </div>
                            <div class="form-group">
                                <input type="file" class="form-control-file" id="profilePicInput" name="profpic" hidden>
                                <div class="card-body" >
                                    <div id="profilePic" title="Alterar Imagem" style="min-height:20vh;background-image:url('php/<?php echo $profile ?>');background-size: cover; background-position:center "></div>
                                </div>
                            </div>
                        </div>





                        <div class="col-4 border-left-primary m-lg-3">
                            <div class="form-group">
                                <label for="domname">Nome</label>
                                <input type="text" class="form-control" id="domname" name="domname" value="<?php echo $name;?>">
                            </div>
                            <div class="form-group">
                                <label for="domemail">Endereço de E-mail</label>
                                <input type="email" class="form-control" id="domemail" name="domemail" aria-describedby="emailHelp" value="<?php echo $email;?>">
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="domactive" name="domactive" <?php if($status) echo 'checked';?>>
                                <label class="form-check-label" for="domactive">Ativo</label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>






                    <div class="col border-left-primary m-lg-3" style="max-height:30vh; overflow-y: auto;">


                            <!--- // ---------------------------- ADD A ROLE ----------------------------- // --->
                            <a id="plusrole" class="closed" <?php if($isadmin==0) echo "hidden";?> >+ <span style="font-size:3vh">Equipas</span></a>


                            <div id="addingCard" class="card-body shadow" style="display: none; background-color:white; border-radius: 10px; height:8vh;margin-bottom:1vh;">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-4">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:black">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="dropSelection">Equipa
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu" style="position:relative; z-index: 1000;">
                                                    <?php
                                                    foreach($teams_u as $team) {
                                                        echo '<li class="selectteam eqb" id="' . $team['id'] . '"><a href="#" class="eqa" id="' . $team['name'] . '">' . $team['name'] . '</a></li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" hidden>
                                           <a href="#" class="btn btn-danger btn-circle btn-sm" style="z-index:1000; position:absolute; margin-left:5px; margin-bottom:5px; height:2.5vh; width:2.5vh;" >
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                        <span id="resultselectteam" hidden></span>
                                    </div>

                                    <div class="col-7" id="rolecol">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:black">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="dropSelectionRole">Cargo
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu" style="position:relative; z-index: 1000;">
                                                    <?php
                                                    foreach($roles_u as $role) {
                                                        echo '<li class="selectrole eqb" id="' . $role['id'] . '"><a href="#" class="eqa" id="' . $role['name'] . '">' . $role['name'] . '</a></li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" hidden>
                                            <a href="$" class="btn btn-danger btn-circle btn-sm" style="z-index:1000; position:absolute; margin-left:5px;  height:2.5vh; width:2.5vh;" >
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                        <span id="resultselectrole" hidden></span>
                                    </div>

                                    <div class="col-1" id="savecol">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:black">
                                            <i class="fas fa-check-circle" id="savenewrole"></i>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <!--- // -------------------------------------------------------------------- // --->

                            <?php
                            $maincount=0;
                            $first = 0;
                            $titulo="";
                            $cargo_teams=[];

                            foreach($teams as $team) {
                                if($first==0) {

                                    $titulo = $team['name'];
                                    $cargo_teams[$maincount] = ['id'=>$team['idequipa'], 'titulo'=>$team['name'] , 'color'=>$team['color'], 'cargos'=>[]];
                                    $cargo_teams[$maincount]['cargos'][]=['id'=>$team['idcargo'], 'nome' =>$team['cargo']];
                                    $first=1;

                                }
                                elseif(strcmp($titulo, $team['name'])!=0){
                                    $titulo = $team['name'];
                                    $maincount++;
                                    $cargo_teams[$maincount] = ['id'=>$team['idequipa'], 'titulo'=>$team['name'] , 'color'=>$team['color'], 'cargos'=>[]];
                                    $cargo_teams[$maincount]['cargos'][]=['id'=>$team['idcargo'], 'nome' => $team['cargo']];

                                }
                                else {
                                    array_push($cargo_teams[$maincount]['cargos'],['id'=>$team['idcargo'], 'nome' => $team['cargo']]);
                                }
                            }



                            foreach($cargo_teams as $team) {
                                echo '
                                <div class="card-body shadow" style="background-color:'.$team['color'].'; border-radius: 10px;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="font-weight-bold text-uppercase mb-1" style="color: #ffffff; font-size: 12.5px;">'.
                                            $team['titulo'].
                                            '</div>';
                                            $thisteamid = $team['id'];
                                            foreach($team['cargos'] as $cargo) {
                                                echo '<div class="h5 mb-0 font-weight-bold text-gray-900">';
                                                echo $cargo['nome'];
                                                if($isadmin) {
                                                    echo ' <div id="'. $thisteamid . "-" . $cargo['id'] . "-" . $_SESSION['login'] . '" class="btn btn-danger btn-circle btn-sm delete_cargo" style="position:absolute; margin-left:5px; margin-bottom:5px; height:2.5vh; width:2.5vh;" >
                                                                <i class="fas fa-trash"></i>
                                                            </div>';
                                                }
                                                echo '</div>';

                                            }
                                            echo'
                                        </div>
                                        <div class="col-auto hoveroptions">
                                            <i class="fas fa-flag fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <br>';
                            }
                            ?>
                        </div>









                        <div class="col-2 m-lg-3" style="max-height:30vh; padding-top:6vh;">

                        <a href="../calendario/calendarpage.php" class="btn btn-info  btn-icon-split col-12" style="float:right; width:11vw; margin-bottom: 3vh;">
                                <span class="icon text-white-50" style="position:absolute; right:9vw">
                                  <i class="fas fa-calendar"></i>
                                </span>
                                <span class="text">Calendário</span>
                            </a>

                            <a href="statistics.php" class="btn btn-info btn-icon-split col-12" style="float:right; width:11vw; margin-bottom: 3vh; pointer-events: none;">
                                <span class="icon text-white-50" style="position:absolute; right:9vw;">
                                  <i class="fas fa-chart-bar"></i>
                                </span>
                                <span class="text">Estatística</span>
                            </a>

                            <a href="history.php" class="btn btn-info btn-icon-split col-12" style="float:right; width:11vw; pointer-events: none;">
                                <span class="icon text-white-50" style="position:absolute; right:9vw">
                                  <i class="fas fa-history"></i>
                                </span>
                                <span class="text">História</span>
                            </a>
                        </div>












                    </div>

                </form>


                <div class="row" style="margin-top:2vh; margin-bottom:2vh;">
                    <!----------REPORTS & TASKS-------------->
                    <div class="card shadow m-2 col-md-6" >
                        <div class="card-header py-1 row" style="width:auto; padding-right:0">
                            <h6 style="" class="m-2 font-weight-bold text-primary col-3">Lista de contatos</h6>
                            <a href="contato.php" style="" class="btn btn-primary btn-icon-split col-3">
                                <span class="icon text-white-50" style="position:absolute; left:0;">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text" style="padding-left: 2vw;">Contato</span>
                            </a>
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control-file" id="profilePicInput" name="profpic" hidden>
                            <div class="card-body" >
                                <table id="schedule_table">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Título</th>
                                            <th>Entidade</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($contatos as $contato) {
                                        if( $contato['completed'] == 1) {
                                            $date_completed = $conn->query("SELECT completed FROM contatos_details WHERE idcontato =" . $contato['id'] . " ORDER BY created_at DESC LIMIT 1")->fetch_assoc()['completed'];
                                            $date_scheduled = $conn->query("SELECT date FROM contatos WHERE id =" . $contato['id'] . " LIMIT 1")->fetch_assoc()['date'];
                                            $datedifference = strtotime($date_completed) - strtotime($date_scheduled);
                                            $days_diff = round($datedifference/(3600)/24);
                                            if($days_diff==1)
                                                $time_alert = "1 dia atrasado";
                                            elseif($days_diff==-1)
                                                $time_alert = "1 dia adiantado";
                                            elseif($days_diff>1)
                                                $time_alert = abs($days_diff) . " dias atrasado";
                                            elseif($days_diff<-1)
                                                $time_alert = abs($days_diff) . " dias adiantado";
                                            elseif($days_diff==0)
                                                $time_alert = "É hoje!";
                                        }
                                        else {
                                            $date_scheduled = $conn->query("SELECT date FROM contatos WHERE id =" . $contato['id'] . " LIMIT 1")->fetch_assoc()['date'];
                                            $datedifference =  time() - strtotime($date_scheduled);
                                            $days_diff = round($datedifference/(3600)/24);
                                            if($days_diff==1)
                                                $time_alert = "1 dia atrasado";
                                            elseif($days_diff==-1)
                                                $time_alert = "1 dia adiantado";
                                            elseif($days_diff>1)
                                                $time_alert = abs($days_diff) . " dias atrasado";
                                            elseif($days_diff<-1)
                                                $time_alert = abs($days_diff) . " dias adiantado";
                                            elseif($days_diff==0)
                                                $time_alert = "É hoje!";
                                        }
                                        echo "<tr ";
                                        if($days_diff>0 AND $contato['completed'] == 0) {
                                            echo ' style="background-color: #fbdada" ';
                                        }
                                        elseif($days_diff == 0 AND $contato['completed'] == 0) {
                                            echo ' style="background-color:#fbffca" ';
                                        }
                                        elseif ($contato['completed'] == 1) {
                                            //echo ' style="background-color:#cae4ca" ';
                                        }
                                        echo " class='contato_click' data-contatoid='".$contato['id']."'>";
                                            echo "<td>";
                                                echo $contato['date'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo utf8_decode(utf8_encode($contato['titulo']));
                                            echo "</td>";
                                            echo "<td>";
                                                echo utf8_decode($conn->query("SELECT nome FROM entidades WHERE id =" .$contato['id'])->fetch_assoc()['nome']);
                                            echo "</td>";
                                            echo "<td>";
                                                echo '<div class="form-check form-check-inline">
                                                <div class="pretty p-default p-round">
                                                    <input class="completed_check" type="checkbox" data-id="' . $contato['id'] . '"  ';
                                                    if($contato['completed']==1) {
                                                        echo " checked";
                                                    }
                                                    echo '>';
                                                echo '
                                                    <div class="state p-success-o">
                                                        <label>' . $time_alert . '</label>
                                                    </div>
                                                </div>';
                                                echo '</div>';
                                            echo "</td>";
                                            echo "<td>" . '<i class="fas fa-times delete_evento"></i>' . "</td>";
                                        echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!----------END OF REPORTS & TASKS-------------->

                    <!----------CONTACT LIST-------------->
                    <div class="card shadow col m-2">
                        <div class="card-header py-1 row" style="width:auto; padding-right:0">
                            <h6 style="" class="m-2 font-weight-bold text-primary col-3">Lista de entidades</h6>
                            <a href="entidade.php" style="" class="btn btn-primary btn-icon-split col-3">
                                <span class="icon text-white-50" style="position:absolute; left:0;">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text" style="padding-left: 2vw;">Entidade</span>
                            </a>
                        </div>
                        <div class="form-group">
                            <div class="card-body" >
                                <table id="contacts">
                                    <thead>
                                        <th style="width:50%">Nome</th>
                                        <th style="width:50%">Telemóvel</th>
                                        <th style="width:50%">Entidade</th>
                                        <th style="width:50%"></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($entidadeslista as $entidade) {
                                        echo '<tr class="a_contact" data-theid="' . $entidade['id'] . '">';
                                            echo '<td style="width:50%">'.$entidade['nome']."</td>";
                                            echo '<td style="width:20%">'.$entidade['telemovel']."</td>";
                                            echo '<td style="width:20%">'.$entidade['email']."</td>";
                                            echo "<td>" . '<i class="fas fa-times delete_entidade"></i>' . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!----------END OF CONTACT LIST-------------->

                </div>


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include_once '../partials/footer.php'; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<?php include '../partials/modalandscroll.php' ?>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var filename = $("#profilePicInput").val();
            filename = filename.substring(filename.lastIndexOf('\\')+1);
            reader.onload = function(e) {
                $('#profilePic').css('background-image', "url(" + e.target.result + ")");
            }
            reader.readAsDataURL(input.files[0]);

        }
    }

    $(document).ready(function(){
        $('#rolecol').hide();
        $('#savecol').hide();
        $('#addingCard').hide();


        $('#profilePic').click(function(){
            $('#profilePicInput').trigger('click');
        });

        $("#profilePicInput").change(function(event) {
            readURL(this);
        });

        $('#plusrole').click(function(){
           $closed = $(this).hasClass("closed");
           if($closed) {
               $('#addingCard').show();
               $(this).removeClass("closed");
               $(this).html('- <span style="font-size:3vh">Equipas</span>');
           }
           else {
               $('#addingCard').hide();
               $(this).addClass("closed");
               $(this).html('+ <span style="font-size:3vh">Equipas</span>');
           }
        });

        $('.selectteam').click(function(){
            var team_id = $(this).attr('id');
            var team_name = $(this).children('a').first().attr('id');
            $('#dropSelection').html(team_name);
            $('#rolecol').show();
            $('#resultselectteam').html(team_id);
        });

        $('.selectrole').click(function(){
            var role_id = $(this).attr('id');
            var role_name = $(this).children('a').first().attr('id');
            $('#dropSelectionRole').html(role_name);
            $('#savecol').show();
            $('#resultselectrole').html(role_id);
        });

        $('#savenewrole').click(function () {
           var user_id = <?php echo $_SESSION['login']; ?>;
           var team_id = $('#resultselectteam').html();
           var role_id = $('#resultselectrole').html();
           var success = 0;
           var newresult = '';
           var confirmation = '';
           $.ajax({
                url: "php/newrole.php",
                method: 'POST',
                async: false,
                data: {
                    'id_team' : team_id,
                    'id_role' : role_id,
                    'id_user' : user_id
                },
                success: function(result) {
                    success=1;
                    newresult=result;
                },
                error:  function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
            if(success) {

            }
            confirmation = confirm(newresult);
            if(confirmation){
                location.reload();
            }

        });

        $('.delete_cargo').click(function(){
            var confirmation = confirm("Pretende realemnte apagar este cargo?");
            if (confirmation) {

                var string_id = $(this).attr('id').split('-');
                var success = 0;
                var newresult = '';

                $.ajax({
                    url: "php/deleterole.php",
                    method: 'POST',
                    azync: false,
                    data: {
                        'id_team' : string_id[0],
                        'id_role' : string_id[1],
                        'id_user' : string_id[2]
                    },
                    success: function(result) {
                        success = 1;
                        newresult = result;
                        if(success == 1) {
                            var confirmation2 = confirm(newresult);
                            if(confirmation2){
                                location.reload();
                            }
                        }
                        else {
                            alert("ajax error!");
                        }
                    },
                    error:  function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        });

        $('.a_contact').dblclick(function () {
            var the_id = $(this).data("theid");
            window.location.replace('entidade.php?id=' + the_id);
        });


        $('#contacts').DataTable({
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "bInfo" : false
        } );

        $('#schedule_table').DataTable({
            "order": [],
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "bInfo" : false
        } );

        $('.completed_check').click(function () {
            var contato_id = $(this).data('id');
            var state = 0;
            var resp = 0;
            if($(this).prop("checked")) {
                state = 1;
            }
            $.ajax({
                url: 'php/completed_report.php',
                type: 'post',
                async: false,
                data: {
                    'report': contato_id,
                    'state': state
                },
                success: function( data, textStatus, jQxhr ){
                    resp = 1;
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert(errorThrown);
                }
            });
            $(this).closest('tr').toggleClass('to_complete');
        });


        $('.contato_click').dblclick(function () {
            var theid = $(this).data('contatoid');
            window.location.replace('contato.php?id=' + theid);
        });


        $('.delete_evento').click(function () {
                swal({
                    title: "Tem a certeza?",
                    text: "Depois de apagado, não será possível recuperar este registo!",
                    icon: "warning",
                    buttons: ["Cancelar", "Apagar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {

                        var theid = $(this).parent().parent().data('contatoid');
                        //var datats = thedad.data('fichodulos').toString();

                        $.ajax({
                            type: "POST",
                            url: "php/_delete.php",
                            data: {
                                id_delete: theid,
                                the_type: 1
                            },
                            cache: false,
                            success: function (data) {

                                swal({
                                    text: "Registo apagado com sucesso! " + data,
                                    icon: "success",
                                }).then(function () {
                                    location.reload();
                                });
                            },
                            error: function (jqXhr, textStatus, errorMessage) {
                                alert('Error: ' + errorMessage);
                            }
                        });
                    }
                });
            });

            $('.delete_entidade').click(function () {
                swal({
                    title: "Tem a certeza?",
                    text: "Depois de apagado, não será possível recuperar este registo!",
                    icon: "warning",
                    buttons: ["Cancelar", "Apagar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {

                        var theid = $(this).parent().parent().data('theid');
                        //var datats = thedad.data('fichodulos').toString();

                        $.ajax({
                            type: "POST",
                            url: "php/_delete.php",
                            data: {
                                id_delete: theid,
                                the_type: 2
                            },
                            cache: false,
                            success: function (data) {
                                swal({
                                    text: "Registo apagado com sucesso! " + data,
                                    icon: "success",
                                }).then(function () {
                                    location.reload();
                                });
                            },
                            error: function (jqXhr, textStatus, errorMessage) {
                                alert('Error: ' + errorMessage);
                            }
                        });
                    }
                });
            });
    });
</script>

</body>

</html>
