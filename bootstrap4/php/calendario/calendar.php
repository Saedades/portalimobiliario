<?php

function build_calendar($month,$year, $conn) {

	$bottom='';


    $firstDayOfMonth = 1;
    $lastDayOfMonth = date("t", strtotime("'". $year . "'" . $month . "'" . '1' . "'"));
    $data_inicio = "'" . $year . "-" . $month . "-" . $firstDayOfMonth . "'";
    $data_fim = "'" . $year . "-" . $month . "-" . $lastDayOfMonth . "'";
    $query ="SELECT * FROM reports WHERE iduser=" . $_SESSION['login'] . " AND data BETWEEN " . $data_inicio . " AND " . $data_fim;
    $reports_on_this_month = $conn->query("SELECT * FROM contatos WHERE user=" . $_SESSION['login'] . " AND agendado BETWEEN " . $data_inicio . " AND " . $data_fim )->fetch_all(MYSQLI_ASSOC);
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
            <div class="container"  style="display: inline-block">
                <div id="monthyearspan" class="dropdown" >
                    <a href="calendarpage.php?year=' . $new_year . '&month=' . $left_month . '" class="btn btn-primary btn-sm" id="leftmonth">
                      &larr;
                    </a>
                    &nbsp;&nbsp;&nbsp;
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
                    &nbsp;&nbsp;&nbsp;
                    <a href="calendarpage.php?year=' . $new_year . '&month=' . $right_month . '" class="btn btn-primary btn-sm" id="rightmonth">
                        &rarr;
                    </a>
                </div>







                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;








                <div id="yearspan" class="dropdown" >
                    <a href="calendarpage.php?year=' . (intval($year)-1) . '&month=' . $month . '" class="btn btn-primary btn-sm" id="leftmonth">
                    &larr;
                    </a>
                    &nbsp;&nbsp;&nbsp;
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
                    &nbsp;&nbsp;&nbsp;
                    <a href="calendarpage.php?year=' . (intval($year)+1) . '&month=' . $month . '" class="btn btn-primary btn-sm" id="rightmonth">
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
                                    <table>';
                                    foreach($event_horizon as $event) {
                                        $max_event_counter++;
                                        $bottom .= "<tr style='padding:1vw; border-bottom:1pt solid black;'>";

                                        $bottom .="<td style='text-align:left'>" .
                                                    '<a href="../contato/contato.php?id=' . $event['id'] . '">' . $event['titulo'] . "</a>
                                                  </td>";

                                        $bottom .=  "<td style='padding-left:3vw;text-align:left'>" .
                                                      $conn->query("SELECT nome FROM entidades WHERE id=" . $event['id'])->fetch_assoc()['nome'] .
                                                    "</td>";

                                        $bottom .= "<td style='padding-left:3vw;text-align:left'>";
										if(isset($event['hour']) && strlen($event['hour'])>3) {
											$bottom .= substr($event['hour'],0,2) . ":" . substr($event['hour'],2,2);
										}
										else {
											$bottom .= " ";
										}
										$bottom .= "</td>";
                                        $bottom .= "</tr>";
                                    }
                                    $bottom .= '
                                    </table>
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
<!DOCTYPE html>
    <html>
        <style>

            table, td, th {
				text-align: center;
				//border: 1px solid #c6d8ff;
            }

            .monthyear {
              height:2vh;
              background-color: #6b86d3;
              color:white;
              font-weight: bold;
              padding: 30px;
            }

            #calendar {
             	width:75%;
              	height:75%;
              	border-collapse: collapse;
				transform: translateX(15%);
                background-color: #ffffff;
                border: 1px solid rgba(0, 0, 0, .125);
                border-radius: .50rem;
            }

            .eventspan {
                z-index: 1000;
                position: absolute;
                padding-bottom: 1vh;
                line-height: 1vh;
                width: 3vw;
                margin-left: -1.5vw;
            }

            .modal-content {
                color:black!important;
            }

            #monthyearspan {
                float: left;
                //background-color: red;
            }

            #yearspan{
                float: left;
                //background-color: red;
            }

            .above {
                background-color: #f8f9fc;
            }
/*
            td:nth-child(7n) {
                background-color: #f7f9ff;
            }

            td:nth-child(6n) {
                background-color: red;
            }*/

        </style>
		<link href="../../css/calendar.css" rel="stylesheet" />

        <div style="height: 60vh;width:80vw;margin:auto">
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

            echo build_calendar($month,$year, $conn);
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
