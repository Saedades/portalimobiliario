<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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

$isadmin=$conn->query("SELECT admin FROM users WHERE id=" . $id)->fetch_assoc()['admin'];
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

$listautilizadores = $conn->query('SELECT * FROM users')->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Imobiliária</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">



    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
		
		.btn-nao-efetuado {
		  background-color:#ffe3e3;
		}

		.btn-efetuado {
		  background-color:#e7f2e8;
		}

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
		
		 .greentask {
      background-color:#e7f2e8;
    }

    .yellowtask {
      background-color:#fffeeb;
    }

    .redtask {
      background-color:#ffe3e3;
    }
	.whitetask {
      background-color:#ffffff;
    }
		
	.ang_modal_titles {
		margin-left: 1vh;
		font-size: 22px;
		color: #478dd5;
		border-right: #478dd5 solid 2px;
		padding-right: 1vh;
	}
		
	.ang_modal_sub {
		font-size: 16px;
		color: #478dd5;
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


				<div class="row" style="height:80vh;">
					<div class="col-4">
						<h4 style="text-transform: uppercase;">Seguimentos</h4>
						
						<div class="form-group" id="associar_utilizador">
							<select class=" form-control js-example-basic-single" id="domutilizador" name="domutilizador">
								<option value="0" selected>Todos os consultores</option>
								<option value="-1" >Nenhum atribuído</option>
								<?php
								foreach($listautilizadores as $utilizador) {
									echo '<option style="font-size:1.5vh" value="' . $utilizador['id'] . '">' . $utilizador['name'] . '</option>';
								}
								?>
							</select>
						</div>
						
						<div class="list-group" style="font-size:1.5vh;max-height:700px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;" id="div_entity_tasks">
							
							<?php

							if($isadmin==2) {
								$tasks_not_modal =  $conn->query('SELECT * FROM contatos WHERE entidade IN (SELECT entidade FROM entidades_users WHERE user = ' . 
											 					((isset($_SESSION['login'])) ? $_SESSION['login'] : 0) . 
											 					') ORDER BY estado, agendado ASC LIMIT 80');
							}
							else {
								$tasks_not_modal =  $conn->query('SELECT * FROM contatos ORDER BY estado, agendado ASC LIMIT 80');
							}
							
							
							
							foreach($tasks_not_modal as $item) {
								
								$dtdb = $item['agendado'];
								$dtnow = date('Y-m-d h:m:s');
								if(
									date('Y',strtotime($dtdb)) === date('Y',strtotime($dtnow)) AND 
									date('n',strtotime($dtdb)) === date('n',strtotime($dtnow)) AND 
									date('j',strtotime($dtdb)) === date('j',strtotime($dtnow))) {
									$labeltime = 'HOJE';
								}
								else if(date('Y-m-d', strtotime($dtdb)) > date('Y-m-d', strtotime($dtnow))) {
									$labeltime = 'PRÓXIMO';
								}
								else if(date('Y-m-d', strtotime($dtdb)) < date('Y-m-d', strtotime($dtnow)) ) {
									$labeltime = 'POR FAZER';
								}
								else {
									$labeltime = 'DEFAULT';
								}
								
								if($item['estado']==1) {
									$labeltime = 'EFETUADO';
									$labelcolor = ' greentask';  //green
									$labelefetuar = '';
									$classtate = 'done';
								}
								else if($labeltime === 'POR FAZER') {
									$labelcolor = ' redtask'; //red
								}
								else if($labeltime === 'HOJE') {
									$labelcolor = ' yellowtask';    //yellow
								}
								else if($labeltime === 'PRÓXIMO') {
									$labelcolor = ' whitetask';    //white
								}
								
								
								$fullname = $conn->query('SELECT CONCAT(nome, " ", apelido) as fullname FROM entidades WHERE id IN (SELECT entidade FROM contatos WHERE id='.$item['id'].')')->fetch_assoc()['fullname'];
								$entidadeid = $conn->query('SELECT id FROM entidades WHERE id IN (SELECT entidade FROM contatos WHERE id='.$item['id'].')')->fetch_assoc()['id'];
								$consultores = $conn->query('SELECT * FROM users WHERE id IN (SELECT user FROM entidades_users WHERE entidade='.$entidadeid.')')->fetch_all(MYSQLI_ASSOC);
								
								
								echo '	<a href="#" class="list-group-item list-group-item-action task ' . $labelcolor . '" data-taskid="' . $item['id'] . '" data-users="';
								foreach($consultores as $consultor) {
									echo $consultor['id'];
									echo '-';
								}
								echo '">';
								
								echo '		<div class="row" style="font-size:1.5vh;">
												<div class="col">
													<p style="float:left;  margin:0; ">
													' . date('Y-m-d', strtotime(strtr($item['agendado'], '/', '-'))) . ' ' . 
														($item['tipohora']==1 ? ' Todo o dia' : date('h:m', strtotime(strtr($item['agendado'], '/', '-')))) .
													'</p>
												</div>
												<div class="col">
													<p style="float:right; margin:0; font-weight:600;">' . $labeltime .
													'</p>
												</div>
											</div>';
								echo '		<div class="row"><div class="col"><b>' . $item['titulo'] . '</b></div><div class="col"><span style="float:right">'.$fullname.'</span></div></div>';
								echo '		<div class="row"><div class="col">Atribuído: ';
								foreach($consultores as $consultor) {
									echo $consultor['name'];
									echo '; ';
								}
								echo '		</div></div>';
								
								echo '</a>';
							}
								
							?>
							
						</div>
						(E1)
					</div>
					
					<div class="col-4">
						(E2)
						<?php

						function build_calendar($month,$year, $conn, $isadmin) {

							$bottom='';


							$firstDayOfMonth = 1;
							$lastDayOfMonth = date("t", strtotime("'". $year . "'" . $month . "'" . '1' . "'"));
							$data_inicio = "'" . $year . "-" . $month . "-" . $firstDayOfMonth . "'";
							$data_fim = "'" . $year . "-" . $month . "-" . $lastDayOfMonth . "'";
							
							if($isadmin==1) {
								$reports_on_this_month = $conn->query("SELECT * FROM contatos WHERE agendado BETWEEN " . $data_inicio . " AND " . $data_fim )->fetch_all(MYSQLI_ASSOC);
							}
							else {
								$reports_on_this_month = $conn->query('SELECT * FROM contatos WHERE entidade IN (SELECT entidade FROM entidades_users WHERE user = ' . $_SESSION['login'] . ") AND agendado BETWEEN " . $data_inicio . " AND " . $data_fim )->fetch_all(MYSQLI_ASSOC);
							}
							
							
							//print_r($reports_on_this_month);
							$events = array();



							foreach($reports_on_this_month as $reports) {
								$jday = date("j", strtotime($reports['agendado']));
								if(!isset($events[$jday])) {
									$events[$jday] = array();
								}
								array_push($events[$jday] ,
										   [
											   'idreports' => $reports['id'],
											   'description' => $reports['descricao'],
											   'iduser' => $reports['user'],
											   'idcontato' => $reports['entidade'],
											   'titulo' => $reports['titulo'],
											   'completed' => $reports['estado']
										   ]);
							}


							$daysOfWeek = array('Seg','Ter','Qua','Qui','Sex','Sáb','Dom');
							$firstDayOfMonth = mktime(0,0,0,intval($month),1,intval($year));
							$numberDays = date('t',$firstDayOfMonth);
							$dateComponents = getdate($firstDayOfMonth);
							$monthName = $dateComponents['month'];
							$dayOfWeek = $dateComponents['wday'] - 1;
							$calendar = "<table class='calendar shadow' id='calendar'>";



							if($month==12) {
								$right_month = 1;
								$new_year = intval($year) + 1;
							}
							else {
								$right_month = intval($month) + 1;
								$new_year = $year;
							}

							if($month==1) {
								$left_month = 12;
								$new_year = intval($year) - 1;
							}
							else {
								$left_month = intval($month) - 1;
								$new_year = $year;
							}



							$calendar .= '
    <tr style="height: 1vh">
        <td class="above" colspan="12">
            <div class="container"  style="display: inline-block; width:100%; margin:auto;">
                <div id="monthyearspan" class="dropdown" style="float:left;">
                    <a href="profile.php?year=' . $new_year . '&month=' . $left_month . '" class="btn btn-primary btn-sm" id="leftmonth">
                      &larr;
                    </a>
                    <button type="button" class="btn btn-primary dropdown-toggle bmc" data-toggle="dropdown" data-iddate="'.date("n", mktime(0, 0, 0, intval($month), 1, intval($year))).'">
                        ';
							$new_month = date("n", mktime(0, 0, 0, intval($month), 1, intval($year)));
							if($new_month == 1 ) {
								$new_month_date="Janeiro";
							}
							if($new_month == 2) {
								$new_month_date="Fevereiro";
							}
							if($new_month == 3) {
								$new_month_date= "Março";
							}
							if($new_month ==  4) {
								$new_month_date="Abril";
							}
							if($new_month == 5) {
								$new_month_date="Maio";
							}
							if($new_month == 6) {
								$new_month_date="Junho";
							}
							if($new_month == 7) {
								$new_month_date="Julho";
							}
							if($new_month ==  8) {
								$new_month_date="Agosto";
							}
							if($new_month == 9) {
								$new_month_date="Setembro";
							}
							if($new_month == 10) {
								$new_month_date="Outubro";
							}
							if($new_month == 11) {
								$new_month_date="Novembro";
							}
							if($new_month == 12) {
								$new_month_date="Dezembro";
							}
							$calendar .= $new_month_date;
							$calendar .=	'
                    </button>
                    <div class="dropdown-menu">
                        <div class="mc" id="m1">Janeiro</div>
                        <div class="mc" id="m2">Fevereiro</div>
                        <div class="mc" id="m3">Março</div>
                        <div class="mc" id="m4">Abril</div>
                        <div class="mc" id="m5">Maio</div>
                        <div class="mc" id="m6">Junho</div>
                        <div class="mc" id="m7">Julho</div>
                        <div class="mc" id="m8">Agosto</div>
                        <div class="mc" id="m9">Setembro</div>
                        <div class="mc" id="m10">Outubro</div>
                        <div class="mc" id="m11">Novembro</div>
                        <div class="mc" id="m12">Dezembro</div>
                    </div>
                    <a href="profile.php?year=' . $new_year . '&month=' . $right_month . '" class="btn btn-primary btn-sm" id="rightmonth">
                        &rarr;
                    </a>
                </div>

                <div id="yearspan" class="dropdown" style="float:right;">
                    <a href="profile.php?year=' . (intval($year)-1) . '&month=' . $month . '" class="btn btn-primary btn-sm" id="leftyear">
                    &larr;
                    </a>
                    <button type="button" class="btn btn-primary dropdown-toggle byc" data-toggle="dropdown" data-iddate="'.date("Y", mktime(0, 0, 0, intval($month), 1, intval($year))).'">
                        '.date("Y", mktime(0, 0, 0, intval($month), 1, intval($year))).'
                    </button>
                    <div class="dropdown-menu" id="scroller">   ';

							$year0 = intval($year)-5;
							$year100 = intval($year)+5;
							while($year0<$year100) {
								$calendar .= '<div class="yc">'.$year0.'</div>';
								$year0++;
							}

							$calendar .= '
                    </div>
                    <a href="profile.php?year=' . (intval($year)+1) . '&month=' . $month . '" class="btn btn-primary btn-sm" id="rightyear">
                    	&rarr;
                    </a>
                </div>



            </div>
        </td>
    </tr>
  ';
							//$calendar .= "<caption class='monthyear'>$monthName $year</caption>";
							$calendar .= "<tr style='background-color: #113b42; color:white; height:1vh;'>";
							$currentDay = 1;

							foreach($daysOfWeek as $day) {
								$calendar .= "<th class='header'>$day</th>";
							}

							$calendar .= "</tr><tr style='height: 1vh'>";

							// The variable $dayOfWeek is used to
							// ensure that the calendar
							// display consists of exactly 7 columns.

							//get day of the week

							$start_day_of_week = date("w", strtotime(intval($year) . '-' . intval($month) . '-' . '1' ));
							if($start_day_of_week==0)
								$start_day_of_week=6;
							else
								$start_day_of_week=$start_day_of_week-1;



							if ($start_day_of_week > 0) {
								$calendar .= "<td colspan='" . $start_day_of_week . "'";
								$calendar .= " >&nbsp;</td>";
							}

							$month = str_pad($month, 2, "0", STR_PAD_LEFT);
							while ($currentDay <= $numberDays) {


								if ($start_day_of_week == 7) {
									$start_day_of_week = 0;
									$calendar .= "</tr><tr style='height: 1vh'>";

								}

								$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
								$date = "$year-$month-$currentDayRel";
								$calendar .= "<td class='day "; //inicio do td

								if(strcmp($date, date("Y-m-d"))==0)
									$calendar .= " today ";
								if($currentDay % 2 == 0) {
									$calendar .= ' even';
								}
								else
									$calendar .= ' odd';
								$calendar .= "' data-toggle='modal' data-target='#myModal". $currentDay ."' rel='$date'>$currentDay";






								if(isset($events[ $currentDay ])) {
									$calendar .= "";
									$event_horizon = $events[ $currentDay ];
									$max_event_counter = 0;
									$i = 0;
									$calendar .="<br>";
									$calendar .='<span style="width:3vh;">';
									while($i <  count($events[ $currentDay ])) {
										$i++;
										$calendar .= "º ";
										if($i==5)
											break;
									}
									$calendar .='</span>';
									$bottom .= '
                    <div id="myModal'.$currentDay.'" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <a href="../contato/contato.php?date=' . $year . "-" . $month . "-" . $currentDay . '" style="" class="btn btn-primary btn-icon-split col-4" hidden>
                                        <span class="icon text-white-50" style="position:absolute; left:0;">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text" style="padding-left: 2vw;">Seguimento</span>
                                    </a>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h5 class="modal-title" style="margin-left: 1vw;">Agendamentos para dia ' . $currentDay . '</h5>
                                </div>
                                <div class="modal-body" style="text-align: left">';
									
									if($isadmin==1) {
										$entity_tasks = 
											$conn->query('SELECT * FROM contatos 
											WHERE agendado LIKE "' . date('Y-m-d',strtotime($year . "-" . $month . "-" . $currentDay)) . '%"')->fetch_all(MYSQLI_ASSOC);
									}
									else {
										$entity_tasks = 
											$conn->query('SELECT * FROM contatos 
											WHERE agendado LIKE "' . date('Y-m-d',strtotime($year . "-" . $month . "-" . $currentDay)) . '%" AND entidade IN
											(SELECT entidade FROM entidades_users WHERE user = ' . $_SESSION['login'] . ')')->fetch_all(MYSQLI_ASSOC);
									}
									
									
									$string ='';

									foreach($entity_tasks as $task){

										$dtdb = $task['agendado'];
										$dtnow = date('Y-m-d h:i:s');
										$labeltime = '';
										$labelefetuar = '';
										$labelcolor = '';
										$classtate = 'undone';


										$labelefetuar = '<i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i>';


										if(date('Y',strtotime($dtdb)) === date('Y',strtotime($dtnow)) AND date('n',strtotime($dtdb)) === date('n',strtotime($dtnow)) AND date('j',strtotime($dtdb)) === date('j',strtotime($dtnow))) {
											$labeltime = 'HOJE';
										}
										else if(date($dtdb) > date($dtnow)) {
											$labeltime = 'PRÓXIMO';

										}
										else if(date($dtdb) < date($dtnow) ) {
											$labeltime = 'POR FAZER';
										}
										else {
											$labeltime = 'DEFAULT';
											//echo date('Y-m-d',$dtdb) + ' - ' + date('Y-m-d',$dtnow))
										}


										if($task['estado']==1) {
											$labeltime = 'EFETUADO';
											$labelcolor = ' greentask';  //green
											$labelefetuar = '';
											$classtate = 'done';
										}
										else if($labeltime === 'POR FAZER') {
											$labelcolor = ' redtask'; //red
										}
										else if($labeltime === 'HOJE') {
											$labelcolor = ' yellowtask';    //yellow
										}



										$string .= '<a  href="#" class="list-group-item list-group-item-action task' . $labelcolor . '"  data-taskid="' . $task['id'] . '"> ' . 
											'<div class="row">
											<div class="col-8">'.
												$task['titulo'] . '<br>' .
												'<span style="float:left;"><b>' .  $conn->query('SELECT CONCAT(nome, " ", apelido) as nooome FROM entidades WHERE id=' . $task['entidade'])->fetch_assoc()['nooome'] . '</b></span>' .
												'<br>' .
												'<span style="float:left; font-size:12px">Atribuido: <b>' .  $conn->query('SELECT name FROM users WHERE id IN (SELECT user FROM contatos WHERE id =' . $task['id'] . ')' )->fetch_assoc()['name'] . '</b></span>' .
											'<br></div>
											<div class="col-4">' .
												'<span style="width: 5vw;text-align:right;">' .  (($task['tipohora']==2) ? date('h:m', strtotime($task['agendado'])) : 'Todo o dia') . '</span>' .
											'</div></div></a>';


										/*$string .= 
											'<a  href="#" class="list-group-item list-group-item-action task ' . $labelcolor . ' ' . $classtate . '" data-taskid="' . $task['id'] . '"> ' . 
											'<div class="row" style="font-size:1+5vh;"> <div class="col"><p style="float:left;  margin:0; ">' . 
											$task['agendado'] + '</p></div><div class="col"><p style="float:right; margin:0; font-weight:600;">' . $labeltime . 
											'</p></div></div><div class="row"><div class="col-10">' . $task['titulo'] . '</div><div class="col">' . $labelefetuar . '</div></div></a>';*/

									}

									$bottom .=  $string;
									$bottom .=  '
								</div>
                            </div>
                        </div>
                    </div>
                   ';
								}
								else {
									$bottom .= '
                    <div id="myModal'.$currentDay.'" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <a href="../contato/contato.php?date=' . $year . "-" . $month . "-" . $currentDay . '" style="" class="btn btn-primary btn-icon-split col-3">
                                        <span class="icon text-white-50" style="position:absolute; left:0;">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text" style="padding-left: 2vw;">Contato</span>
                                    </a>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="margin-left: 1vw;">Agendamentos para dia ' . $currentDay . '</h4>
                                </div>
                                <div class="modal-body" style="text-align: left">

                                </div>
                            </div>
                        </div>
                    </div>
                   ';
								}








								$calendar .= "</td>";
								$currentDay++;
								$start_day_of_week++;



							}

							if ($start_day_of_week != 7) {
								$remainingDays = 7 - $start_day_of_week;
								$calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
							}



							$calendar .= "</tr>";
							$calendar .= "</table>";




							return $calendar . $bottom;


						}

						?>
						
							

							<div style="height: 60vh;width:80vw;margin:auto">
								Problemas com a geração do calendário.
								<?php
								$month = date("n");
								$year = date("Y");
								if(isset($_GET['month'])) {
									$month = $_GET['month'];
								}
								if(isset($_GET['year'])) {
									$year = $_GET['year'];
								}

								/*----------------------------------------------------------------------------------------------**/

								echo build_calendar($month,$year, $conn, $isadmin);
								?>
							</div>

							<div class="modal" role="dialog">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">Modal title</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close" >
												<span aria-hidden="true" style="margin-right:1vw;">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<p>Modal body text goes here.</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary">Salvar</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
										</div>
									</div>
								</div>
							</div>
						</html>
					</div>

					<div class="col-4">
						<h4 style="text-transform: uppercase;">Angariações</h4><br>
						<div class="list-group" style="font-size: 1.5vh; max-height: 700px; overflow: hidden scroll; height: 80vh;" id="div_angariacoes">
							<?php
							$angariacoes = ($result = $conn->query('SELECT * FROM imoveis WHERE angariacao<>0')) ? $result->fetch_all(MYSQLI_ASSOC) : [];
							//print_r($angariacoes);	
							foreach($angariacoes as $item) {
								echo '<a href="#" class="list-group-item list-group-item-action angariacao"><div class="row" style="font-size:1.5vh;"><div class="col"><img src="';
								if($result = $conn->query('SELECT url FROM imoveis_fotos WHERE imovel=' . $item['id'] )->fetch_assoc()['url']) {
									echo '../../img/uploads/imoveis/' . $result;
								}
								else {
									echo '../../img/default.jpg';
								}
								echo '" height="60" width="60"/></div><div class="col-9"><p style="float:right; margin:0; font-weight:600;">';
								echo $conn->query('SELECT nome FROM estados WHERE id = ' . $item['estado'])->fetch_assoc()['nome'];
								echo '	</p>
										<br>
										<p style="float:left;">' . $item['titulo'] . '</p>
										<p  style="float:right;  margin:0; ">ID imobiliária: <label class="p_ang">' . $item['kwid'] . '</label></p>
									</div>
								</div>
							</a>';
							}
							?>			
						</div>
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- End of Main Content -->

		
		
		
		
		<!-- The Modal -->
<div class="modal fade" id="entity">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title" id="entity_title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<label id="entidade" hidden></label>
				<label id="seguser" hidden></label>
			</div>

			<!-- Modal body -->
			<div class="modal-body">


				<div class="row" style="padding:1vh;">
					<input id="entidade_id" name="entidade_id" hidden />
					<div class="col"><h3 id="nomeapelido">NOME APELIDO</h3></div>
					<div class="col">
						<button type="button" class="btn btn-info"    style="float:right;margin-left:1vh;" id="vista_completa">Vista completa</button>
					</div>
				</div>


				<div class="row" style="padding:1vh;background-color:#eaeaea;">
					<div class="col" >
						<div class="row" style="padding:1vh;">
							<i class="fas fa-phone" style="float:left; line-height: 2.8vh;"></i><h5 style="float:left; padding-left:1vh; margin:0" id="telemovel"> +351 24783247 </h5>
						</div>
						<div class="row" style="padding:1vh;">
							<i class="fas fa-envelope" style="float:left; line-height: 2.8vh;"></i><p style="float:left; padding-left:1vh; margin:0" id="email"> sdanosd@gmail.com</p>
						</div>
					</div>
					<div class="col">
						<div class="row" style="padding:1vh;margin:0" >
							<label style="font-size:x-small; padding-right:1vh;">Categoria:</label>
							<p style="float:left; line-height: 2.8vh; padding-right:3vh; margin:0" id="categoria">Comprador/Vendedor</p>

						</div>
						<div class="row" style="padding:1vh;margin:0">
							<label style="font-size:x-small; padding-right:2.5vh;">Rating:</label>
							<p style="float:left; line-height: 2.8vh; padding-right:6vh; margin:0" id="rating">Rating A Conhecer</p>
							<label style="font-size:x-small; padding-right:1vh;">Lead:</label>
							<p style="float:left; line-height: 2.8vh;  padding-right:3vh; margin:0" id="lead">Estado Lead</p>
						</div>
					</div>
				</div>


				<div class="row" style="padding:1vh;">
					<div class="col">
						<h4>Seguimentos</h4>
					</div>
					<div class="col">
						<button style="float:right; margin-left:1vw;" class="btn btn-primary btn-sm" id="novoseg"><i class="fas fa-plus"> </i> Novo Seguimento</button>
						<button style="float:right; margin-left:1vw;" class="btn btn-primary btn-sm" id="novavis" hidden><i class="fas fa-plus"> </i> Nova Visita</button>
					</div>
				</div>


				<div style="padding:1vh;">

					<div class="row">

						<div class="col-8">

							<ul class="nav nav-tabs" id="segTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link tab-filter-tasks active" id="todosseg-tab" data-toggle="tab" href="#todosseg" role="tab" aria-controls="todosseg" aria-selected="true">Todos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link tab-filter-tasks " id="porfazer-tab" data-toggle="tab" href="#todosseg" role="tab" aria-controls="todosseg" aria-selected="false">Por Fazer</a>
								</li>
								<li class="nav-item">
									<a class="nav-link tab-filter-tasks " id="historico-tab" data-toggle="tab" href="#todosseg" role="tab" aria-controls="todosseg" aria-selected="false">Histórico</a>
								</li>
							</ul>

							<div class="tab-content" id="segTabContent">

								<div class="tab-pane fade show active" id="todosseg" role="tabpanel" aria-labelledby="todosseg-tab">
									<br>


									<div class="list-group" style="font-size:1.5vh;max-height:700px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;" id="div_entity_tasks">


									</div>


								</div>

								<!---
								<div class="tab-pane fade" id="porfazer" role="tabpanel" aria-labelledby="porfazer-tab">
								<br>
								<div class="list-group">
								<a href="#" class="list-group-item list-group-item-action">First item</a>
								<a href="#" class="list-group-item list-group-item-action">Second item</a>
								<a href="#" class="list-group-item list-group-item-action">Third item</a>
								</div>
								</div>

								<div class="tab-pane fade" id="historico" role="tabpanel" aria-labelledby="historico-tab">
								<br>
								<div class="list-group">
								<a href="#" class="list-group-item list-group-item-action">First item</a>
								<a href="#" class="list-group-item list-group-item-action">Second item</a>
								<a href="#" class="list-group-item list-group-item-action">Third item</a>
								</div>
								</div>
								--->

							</div>

						</div>

						<div class="col">
							<div class="list-group">
								<h5>Notas</h5>
								<ul class="list-group" style="font-size:1.5vh;max-height:800px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;" id="input_div_entity_notes">
									<div class="list-group-item" id="inputdiv_newnote">
										<input id="newnote" type="text" class="" style="width:95%;float: left;border:none"><i class="fas fa-check" id="addnote"></i>
									</div>
								</ul>
							</div>
						</div>

					</div>

				</div>
			</div>


			<!-- Modal footer -->
			<div class="modal-footer">

			</div>

		</div>
	</div>
</div>
		
		
		
		
		
		
		
		<!-- Modal seguimento -->
		<!-- segtitle ; segtipo ; segdata ; segtypehour ; seghour ; segestado --->
		<div class="modal fade " tabindex="-1" role="dialog" id="seguimento_modal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<form action="seg_controller.php" method="POST">
						<div class="modal-header">
							<h5 class="modal-title">Seguimento</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body">
							<div class="row">
								<div class="col">

									<label id="entidade" hidden></label>
									<input id="segid" name="segid" value="" hidden />
									<label id="seguser" hidden> </label>

									<div class="form-group">
										<label for="segtitle">Título</label>
										<input class="form-control" type="text" value="Titulo" id="segtitle" name="segtitle">
									</div>

									<div class="form-group">
										<label for="tiposeg_select">Tipo</label>
										<div class="form-group">
											<select class="form-control" id="segtipo" name="segtipo">
												<?php
												$tipos_taks = $conn->query('SELECT * FROM tipo_contato')->fetch_all(MYSQLI_ASSOC);
												foreach($tipos_taks as $item) {
													echo '<option value="' . $item['id'] . '">';
													echo ucfirst($item['nome']);
													echo '</option>';
												}
												?>
											</select>
										</div>
									</div>


									<div class="form-group">
										<label for="segdescricao">Descricao</label>
										<div class="form-group">
											<textarea class="form-control" id="segdescricao" name="segdescricao"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label id="segdatalabel" for="segdata">Data </label>
										<div class="input-group date" id="segdata_form" data-target-input="nearest">
											<input type="text" class="form-control datetimepicker-input" data-target="#segdata_form" id="segdata" name="segdata" value="<?php echo '2019-07-26'; ?>"/>
											<div class="input-group-append" data-target="#segdata_form" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="hourtype">Tipo de agendamento</label>
										<div class="form-group">
											<select class="form-control" id="hourtype" name="segtypehour">
												<option value="1">Todo o dia</option>
												<option value="2">Hora</option>
											</select>
										</div>
									</div>

									<div class="form-group" id="seghourform">
										<label id="seghourlabel" for="seghour">Hora </label>
										<div class="input-group date" id="seghour_form" data-target-input="nearest">
											<input type="text" class="form-control datetimepicker-input" data-target="#seghour_form" id="seghour" name="seghour" value="<?php echo '20:00'; ?>"/>
											<div class="input-group-append" data-target="#seghour_form" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-clock"></i></div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<br>
										<div class="btn btn-nao-efetuado" id="btn_segestado" name="btn_segestado">POR FAZER</div>
										<input type="checkbox" id="segestado" name="segestado" hidden>
									</div>

								</div>


								<div class="col">
									<div class="list-group">
										<h5>Notas</h5>
										<ul class="list-group" style="font-size:1.5vh;max-height:800px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;"
											id="input_div_seg_notes">
											<div class="list-group-item" id="seg_inputdiv_newnote">
												<input id="seg_newnote" type="text" class="" style="width:95%;float: left;border:none"><i class="fas fa-check" id="seg_addnote"></i>
											</div>
										</ul>
									</div>

								</div>


							</div>

						</div>
					</form>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="save_seg">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>

				</div>
			</div>
		</div>

		<!----saravig---->
		<div class="modal" tabindex="-1" role="dialog" id="ang_modal" style="width:100%;">
			<div class="modal-dialog" role="document" style="max-width:unset; margin-left:10vw; margin-right:10vw;">
				<div class="modal-content">
					<div class="modal-header">
						<h2 id="domid" style="margin-right:2vw"></h2>
						<a href="../imovel/imovel.php" type="button" class="btn btn-info" style="float:right;margin-left:1vh;float:right;color:white" id="vista_completa">Vista completa</a>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-3">
								<div id="ang_modal_foto" style="height:25vh; width:100%; background-image:url('../../img/default.jpg');background-size: cover;"></div>
								<br>
								<p class="ang_modal_sub">Proprietário</p>
							</div>
							<div class="col-9">
								<div class="row">
									<label class="ang_modal_titles" id="domtitle">Titulo</label> 
									<label id="domtipocasa" class="ang_modal_titles">Tipo</label> 
									<label id="domtipologia" class="ang_modal_titles">Tipologia</label> 
									<label class="ang_modal_titles" id="domnegocio">Negócio</label>
								</div>
								<div class="row">
									<div class="col-3">
										<p class="ang_modal_sub">Dados</p>
									</div>
									<div class="col-3">
										<p class="ang_modal_sub">Negócio</p>
										<b style="color:gray">Preço</b><br>
										<label style="color:gray" id="dompreco"></label> <label style="color:gray"> €</label>
									</div>
									<div class="col-3">
										<p class="ang_modal_sub">Áreas</p>
									</div>
									<div class="col-3">
										<p class="ang_modal_sub">Localização</p>
										<b style="color:gray">Distrito e CP</b><br>
										<label style="color:gray" id="domdistcp"></label> <label style="color:gray"> €</label>
										<br>
										<b style="color:gray">Concelho</b><br>
										<label style="color:gray" id="domconcelho"></label>
										<br>
										<b style="color:gray">Freguesia</b><br>
										<label style="color:gray" id="domfreguesia"></label>
										<br>
										<b style="color:gray">Rua</b><br>
										<label style="color:gray" id="domrua"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		
		
		
		
		
		
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
<link rel="stylesheet" href="sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

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

		/*
        $('.a_contact').dblclick(function () {
            var the_id = $(this).data("theid");
            window.location.replace('entidade.php?id=' + the_id);
        });*/


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
		
		

		$('#calendar').css("height", '80vh');
		$('#calendar').parent().css("width", '100%');
		$('#calendar').css("width", '100%');
		$('#calendar').css("transform", '0');
		$('#monthyearspan').css('margin-bottom', '1vh');
		$('#div_entity_tasks').css("height", '80vh');

		/*
		$('.task').click(function(){

			//alert('modal: ' + $(this).data('id'));
			var idseg = $(this).data('taskid');

			//get task entity
            var nome_entity;
            $.ajax({
				async: false,
				type: "POST",
				dataType:'json',
				url: "../entidade/_entidade_modal.php",
				data: {
					idseg: idseg,
					action: 55
				},
				cache: false,
				success: function (data) {
					nome_entity = data;
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage);
				}
			});
            $('#seguimento_modal .modal-title').text(nome_entity['fullname']);
			$('#entidade').text(nome_entity['id']);
			

			//get the task details
			var task_details = [];
			$.ajax({
				async: false,
				type: "POST",
				dataType: 'JSON',
				url: "../entidade/_entidade_modal.php",
				data: {
					idseg: idseg,
					action: 5
				},
				cache: false,
				success: function (data) {
					//alert(data.toSource());
					task_details = JSON.parse(JSON.stringify(data));
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage);
				}
			});


			//get the task notes
			var task_notes = [];
			$.ajax({
				async: false,
				dataType: "json",
				type: "POST",
				url: "../entidade/_entidade_modal.php",
				data: {
					idseg: idseg,
					action: 8
				},
				cache: false,
				success: function (data) {
					task_notes = JSON.parse(JSON.stringify(data));
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage);
				}
			});

			//instantiate the modal
			// segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
			$('#segid').val(idseg);
			$('#segtitle').val(task_details.titulo);
			$('#segdescricao').val(task_details.descricao);
			$("#tiposeg_select option[value=" + task_details.tipo + "]").attr('selected', 'selected');
			$("#segdata").val(moment(task_details.agendado).format("YYYY-MM-DD"));
			$("#seghour").val(moment(task_details.agendado).format("HH:mm"));
			if(task_details.tipohora==1) {
				$('#hourtype').val(1);
				$("#seghourform").hide();
			}
			else{
				$('#hourtype').val(2);
				$("#seghourform").show();
			}
			if(task_details.estado=='1') {
				$("#btn_segestado").removeClass('btn-nao-efetuado');
				$("#btn_segestado").addClass('btn-efetuado');
				$("#btn_segestado").text('EFETUADO');
				$("#segestado").prop('checked', true);
			}
			$('#seguimento_modal').modal({show: true});
			$('.seg_notes_li').remove();
			$.each(task_notes, function(key, value) {
				$('#seg_inputdiv_newnote').after('<li class="list-group-item seg_notes_li" data-noteid="' + value['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + value['criado'] + '</p>' + value['descricao'] + '</li>');
			});

		});
		*/

		$('#novoseg').click(function(){
			$('#seguimento_modal').modal({show: true});
			//alert("segid!: " + $('#segid').val());
			$('#segid').val(0);
			//alert("segid!: " + $('#segid').val());
			$('#segtitle').val('');
			$('#segdescricao').val('');
			$("#segtipo option[value='3']").attr('selected', 'selected');
			var date_seg = moment().format("YYYY-MM-DD");
			var time_seg = moment().format("HH:mm");
			$("#segdata").val(date_seg);
			$("#seghour").val(time_seg);
			$('#hourtype').val(1);
			$("#seghourform").hide();
		});

		$('#btn_segestado').click(function(){
			if($("#btn_segestado").hasClass('btn-nao-efetuado')) {
				$("#btn_segestado").removeClass('btn-nao-efetuado');
				$("#btn_segestado").addClass('btn-efetuado');
				$("#btn_segestado").text('EFETUADO');
				$("#segestado").prop('checked', true);
			}
			else {
				$("#btn_segestado").addClass('btn-nao-efetuado');
				$("#btn_segestado").removeClass('btn-efetuado');
				$("#btn_segestado").text('POR FAZER');
				$("#segestado").prop('checked', false);
			}
		});



		// segid ; segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
		$('#save_seg').click(function(){

			var segid =         $('#segid').val();
			var segtitle =      $('#segtitle').val();
			var segdescricao =  $('#segdescricao').val();
			var segtipo =       $('#segtipo option:selected').val();
			var segdata =       $('#segdata').val();
			var segtypehour =   $('#hourtype option:selected').val();
			var seghour =       $('#seghour').val();
			var segestado =     ($('#segestado').is(":checked") ? 1 : 0);
			var entidade =      $('#entidade').text();
			var user =      <?php echo $_SESSION['login']; ?>;
			
			$.ajax({
				async: false,
				type: "POST",
				url: "../entidade/seg_controller.php",
				data: {
					'segid' :        segid,
					'segtitle' :     segtitle,
					'segdescricao' : segdescricao,
					'segtipo' :      segtipo,
					'segdata' :      segdata,
					'segtypehour' :  segtypehour,
					'seghour' :      seghour,
					'segestado' :    segestado,
					'entidade' :     entidade,
					'user' :		 user
				},
				cache: false,
				success: function (data) {
					Swal.fire(
						'',
						'As modificações foram salvas! ',
						'success'
					).then(function(){
						$('#seguimento_modal').modal('toggle');
						var ident = data;
						var test1 = window.location.href;
						//alert(test1);
						test1 = test1.replace(/[&?]emodal=[0-9]*/, '');
						test1 = test1.replace('#', '');
						//alert(test1);
						var d = new Date(segdata);
						$('#myModal' + d.getDate()).modal('hide');
						if (test1.indexOf("?") >= 0) { 
							//alert(test1 + "&emodal=" + ident);
							window.location=test1 + "&emodal=" + segid;
						}
						else {
							//alert(test1 + "?emodal=" + ident);
							window.location=test1 + "?emodal=" + segid;
						}
					});
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage);
				}
			});

		});
		
		$('#seg_addnote').click(function(){
			var inputnote = $('#seg_newnote').val();
			var idseg = $('#segid').val();
			alert(inputnote);
			alert(idseg);

			$.ajax({
				async: false,
				dataType: "json",
				type: "POST",
				url: "../entidade/_entidade_modal.php",
				data: {
					idseg: idseg,
					descricao: inputnote,
					action: 6
				},
				cache: false,
				success: function(data) {
					note_info = JSON.parse(JSON.stringify(data));
					alert(data.toSource());
				},
				error: function(jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage);
				}
			});

			$('#seg_inputdiv_newnote').after('<li class="list-group-item seg_notes_li" data-noteid="' + note_info['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + note_info['criado'] + '</p>' + note_info['descricao'] + '</li>');
			$('#seg_newnote').val('');

		});



		$('#conttable tr[data-estado="1"]').css('background-color', '#e7f2e8');
		$('#conttable tr[data-estado="0"]').css('background-color', '#ffe3e3');


		$('#seguimento_modal').modal('hide');
				
		
		
		
		
		$('#segdata_form').datetimepicker({
			format: 'YYYY-MM-DD'
		  });

		$('#seghour_form').datetimepicker({
			format: 'HH:mm'
		});

		//---------------angariacao------------//
		$('.angariacao').click(function(){
			var imo_id = $(this).find('.p_ang').text().replace("KW", "");
			console.log(imo_id);
			var data_js;
			$('#ang_modal').modal('show');
			
			$.ajax({
				type: 'POST',
				url: '_controller.php',
				async: false,
				data: {id: imo_id},
				dataType: 'json',
				success: function (data) {
					data_js = data;
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
			
			$('#vista_completa2').attr("href", "/bootstrap4/php/imovel/imovel.php?id=" + imo_id);
			
			$('#ang_modal #domid').text('ID' + imo_id);
			$('#ang_modal #domtipocasa').text(data_js.imoveis[0].tipocasa);
			$('#ang_modal #domtipologia').text(data_js.imoveis[0].tipologia);
			$('#ang_modal #domnegocio').text(data_js.imoveis[0].negocio);
			$('#ang_modal #domtitle').text(data_js.imoveis[0].titulo);
			$('#ang_modal #dompreco').text(data_js.imoveis[0].val_neg);
			$('#ang_modal #domrua').text(data_js.imoveis[0].morada);
			$('#ang_modal #domdistcp').text(data_js.imoveis[0].zip1 + '-' + data_js.imoveis[0].zip2 + ' ' + data_js.imoveis[0].distrito);
			$('#ang_modal #domconcelho').text(data_js.imoveis[0].concelho);
			//$('#ang_modal #domuser').val('<?php echo $_SESSION['login'] ?>');
			//$('#ang_modal #domref').val(data_js[0].kwid);
			//$("#ang_modal select").val(data_js[0].estado);
		});
		
		

		var modal_action = <?php echo (isset($_GET['emodal']) && strlen($_GET['emodal'])>0) ? $_GET['emodal'] : 0; ?>;
		if(modal_action!=0) {
			//$('#conttable tr[data-theid="' + modal_action + '"]').click();
			//alert($('.task[data-taskid="' + modal_action + '"]'));
			$('.task[data-taskid="' + modal_action + '"]').trigger('click');
		}
		

		$('#hourtype').on('change', function(){
		  var tyyype = $("#hourtype option:selected").val();
		  if (tyyype == 2) {
			$('#seghourform').show();
		  } else {
			$('#seghourform').hide();
		  }
		});
		









		


		$('.task').click(function(){

			var idseg = $(this).data('taskid');
			var entity_id;

			$.ajax({
				async: false,
				dataType: "json",
				type: "POST",
				url: "_profile.php",
				data: {
					action: 1,
					idseg: idseg
				},
				cache: false,
				success: function (data) {
					entity_id = data;
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage + ' ' + textStatus);
				}
			});

			$('#entity').modal({show: true});
			
			var ident = entity_id;
			var entity_info = [];
			var entity_notes = [];
			var entity_tasks = [];
			var entity_users = [];

			$('#entidade').text(ident);

			$.ajax({
				async: false,
				dataType: "json",
				type: "POST",
				url: "../entidade/_entidade_modal.php",
				data: {
					entity: ident
				},
				cache: false,
				success: function (data) {
					entity_info = JSON.parse(JSON.stringify(data));
				},
				error: function (jqXhr, textStatus, errorMessage) {
					alert('Error: ' + errorMessage + ' ' + textStatus);
				}
			});

			$('#vista_completa').click(function() {
				window.location.href = "/bootstrap4/php/entidade/entidade.php?id=" + ident;
			});


					if(entity_info.nome.length>1 && entity_info.apelido.length>1)
						$('#nomeapelido').text(entity_info.nome + ' ' + entity_info.apelido);
					else
						$('#nomeapelido').text('');

					if(entity_info.telemovel!=0)
						$('#telemovel').text(entity_info.telemovel);
					else
						$('#telemovel').text('');


					if(entity_info.email.length>1)
						$('#email').text(entity_info.email);
					else
						$('#email').text('');


					if(entity_info.categoria.length>1)
						$('#categoria').text(entity_info.categoria);
					else
						$('#categoria').text('');


					if(entity_info.rating.length>1)
						$('#rating').text(entity_info.rating);
					else
						$('#rating').text('');


					if(entity_info.lead.length>1)
						$('#lead').text(entity_info.lead);
					else
						$('#lead').text(entity_info.lead);

					//---------------notes-------------//
					$.ajax({
						async: false,
						dataType: "json",
						type: "POST",
						url: "../entidade/_entidade_modal.php",
						data: {
							entity: ident,
							action: 2
						},
						cache: false,
						success: function (data) {
							entity_notes = JSON.parse(JSON.stringify(data));
						},
						error: function (jqXhr, textStatus, errorMessage) {
							alert('Error: ' + errorMessage);
						}
					});

					$('.entity_notes_li').remove();

					$.each(entity_notes, function(key, value){
						$('#input_div_entity_notes').append('<li class="list-group-item entity_notes_li" data-noteid="' + value['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + value['criado'] + '</p>' + value['descricao'] + '</li>');
					});
					//--------------------------------//


					//---------------tasks-------------//
					$.ajax({
						async: false,
						dataType: "json",
						type: "POST",
						url: "../entidade/_entidade_modal.php",
						data: {
							entity: ident,
							action: 3
						},
						cache: false,
						success: function (data) {
							entity_tasks = JSON.parse(JSON.stringify(data));
						},
						error: function (jqXhr, textStatus, errorMessage) {
							alert('Error: ' + errorMessage);
						}
					});

					$('#entity .task').remove();

					$.each(entity_tasks, function(key, value){
						
						
						var dtdb = new Date(value['agendado']);
						var dtnow = new Date();
						var labeltime = '';
						var labelefetuar = '';
						var labelcolor = '';
						var classtate = 'undone';


						labelefetuar = '<i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i>';



						if(dtdb.getFullYear() === dtnow.getFullYear() && dtdb.getMonth() === dtnow.getMonth() && dtdb.getDate() === dtnow.getDate()) {
						  labeltime = 'HOJE';
						}
						else if(dtdb > dtnow) {
						  labeltime = 'PRÓXIMO';

						}
						else if(dtdb < dtnow ) {
						  labeltime = 'POR FAZER';
						}
						else {
						  labeltime = 'DEFAULT';
						  //alert(dtdb.getDate() + ' - ' + dtnow.getDate())
						}



						if(value['estado']==1) {
						  labeltime = 'EFETUADO';
						  labelcolor = ' greentask';  //green
						  labelefetuar = '';
						  classtate = 'done';
						}
						else if(labeltime === 'POR FAZER') {
						  labelcolor = ' redtask'; //red
						}
						else if(labeltime === 'HOJE') {
						  labelcolor = ' yellowtask';    //yellow
						}

						var string = '<a class="list-group-item list-group-item-action task ' + labelcolor + ' ' + classtate + '" data-taskid="' + value['id'] + '"> ' + '<div class="row" style="font-size:1+5vh;">   <div class="col"><p style="float:left;  margin:0; ">' + value['agendado'] + '</p></div><div class="col"><p style="float:right; margin:0; font-weight:600;">' + labeltime + '</p></div></div>        <div class="row"><div class="col-10">' + value['titulo'] + '</div><div class="col">' + labelefetuar + '</div></div></a>';

						$('#entity #div_entity_tasks').append(string);
					});
					//--------------------------------//


					//---------------users-------------//
					 $.ajax({
						async: false,
						dataType: "json",
						type: "POST",
						url: "../entidade/_people.php",
						data: {
							entity: ident,
							action: 80
						},
						cache: false,
						success: function (data) {
							entity_users = JSON.parse(JSON.stringify(data));
						},
						error: function (jqXhr, textStatus, errorMessage) {
							alert('Error: ' + errorMessage);
						}
					});

					//--------------------------------//


					$('#entity .list-group-item-action').click(function(e){

					  var idseg = $(this).data('taskid');
					  $('#segid').text($(this).data('taskid'));

					  if(e.target.nodeName == 'I') {
						$.ajax({
							async: false,
							type: "POST",
							url: "../entidade/_entidade_modal.php",
							data: {
								idseg: idseg,
								action: 7
							},
							cache: false,
							success: function (data) {
							  Swal.fire(
								  'Seguimento',
								  'Terminado!',
								  'success'
							  ).then(function(){
									var ident = data;
									var test1 = window.location.href;
									//alert(test1);
									test1 = test1.replace(/[&?]emodal=[0-9]*/, '');
									//alert(test1);
									if (test1.indexOf("?") >= 0) { 
										//alert(test1 + "&emodal=" + ident);
										window.location.replace(test1 + "&emodal=" + ident);
									}
									else {
										//alert(test1 + "?emodal=" + ident);
										window.location.replace(test1 + "?emodal=" + ident);
									}
							  });
								//alert(task_info.toSource());
							},
							error: function (jqXhr, textStatus, errorMessage) {
								alert('Error: ' + errorMessage);
							}
						});
					  }
					  else {
						$('#segid').val($(this).data('taskid'));
						var task_info = [];
						$.ajax({
							async: false,
							dataType: "json",
							type: "POST",
							url: "../entidade/_entidade_modal.php",
							data: {
								idseg: idseg,
								action: 5
							},
							cache: false,
							success: function (data) {
								task_info = JSON.parse(JSON.stringify(data));
							},
							error: function (jqXhr, textStatus, errorMessage) {
								alert('Error: ' + errorMessage);
							}
						});

						// segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
						$('#segtitle').val(task_info.titulo);
						$('#segdescricao').val(task_info.descricao);
						$("#tiposeg_select option[value=" + task_info.tipo + "]").attr('selected', 'selected');
						var date_seg = moment(task_info.agendado).format("YYYY-MM-DD");
						var time_seg = moment(task_info.agendado).format("HH:mm");
						$("#segdata").val(date_seg);

						if(time_seg !== '00:00:00') {
							$("#seghour").val(time_seg);
							$('#hourtype option:eq(2)').attr('selected', 'selected');
							$("#seghourform").show();
						}
						else {
							$("#seghour").val('00:00:00');
							$('#hourtype option:eq(1)').attr('selected', 'selected');
							$("#seghourform").hide();
						}
						
						$("#seghour").val(time_seg);
						if(task_info.tipohora==1) {
						  $('#hourtype').val(1);
						  $("#seghourform").hide();
						}
						else{
						  $('#hourtype').val(2);
						  $("#seghourform").show();
						}


						if(task_info.estado=='1') {
							$("#btn_segestado").removeClass('btn-nao-efetuado');
							$("#btn_segestado").addClass('btn-efetuado');
							$("#btn_segestado").text('EFETUADO');
							$("#segestado").prop( "checked", true );
						}
						$('#seguimento_modal').modal({show: true});

					  }
					});

					//--------------filtering of tabs-----------------//
					$(".tab-filter-tasks").click(function() {
						var selected_tab = this.id;
						// todosseg-tab
						// porfazer-tab
						// historico-tab
						switch(selected_tab) {
							case 'todosseg-tab':$(".undone").show(); $(".done").show(); break;
							case 'porfazer-tab': $(".undone").show(); $(".done").hide(); break;
							case 'historico-tab': $(".undone").hide(); $(".done").show(); break;
						}

					});

					$('#porfazer-tab').click();
			});
		
		$('#domutilizador').change(function(){
			var selected_user = $('#domutilizador option:selected').val();
			if(selected_user != 0) {
				$('#div_entity_tasks .task').each(function(index){
					var numberPattern = /\d+/g;
					var hide=1;
					
					if($(this).data('users') == '' && selected_user==-1) {
						hide=0;
					}
					else {
						var obj_users = $(this).data('users').match( numberPattern );
						$.each(obj_users, function(index, element) {
							if(element === selected_user)
								hide=0;
						});
					}
					if(hide==1) {
						$(this).hide();
					}
					else {
						$(this).show();
					}
				});
			}
			else {
				$('#div_entity_tasks .task').show();
			}
		});
		
		$('#sidebarToggle').click(function(){
			$("body").toggleClass("sidebar-toggled");
			$(".sidebar").toggleClass("toggled");
			$(".sidebar").hasClass("toggled") && $(".sidebar .collapse").collapse("hide");
		});

	});
</script>

</body>

</html>
