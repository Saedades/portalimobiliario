<?php

if( strlen($contato['estado']) > 1) {
  $date_completed = $conn->query("SELECT completado FROM contatos WHERE id =" . $contato['id'])->fetch_assoc()['completado'];
  $date_scheduled = $conn->query("SELECT agendado FROM contatos WHERE id =" . $contato['id'] . " LIMIT 1")->fetch_assoc()['agendado'];
  if(strlen($date_scheduled)>1) {
    //echo "<h5>datcompleted  : " . $date_completed;
    //echo "<h5>datescadudle : " . $date_scheduled;
    $datedifference = strtotime($date_completed) - strtotime($date_scheduled);
    //echo "<h5>diff : " . $datedifference;
    $days_diff = floor($datedifference/(3600)/24);
    //echo "<h5>days : " . $days_diff;
    if($days_diff==1)
        $time_alert = "1 dia atrasado";
    elseif($days_diff==-1)
      $time_alert = "1 dia adiantado";
    elseif($days_diff>1 AND $days_diff<14)
      $time_alert = floor(abs($days_diff)) . " dias atrasado";
    elseif($days_diff>14 AND $days_diff<30)
      $time_alert = floor(abs($days_diff/7)) . " semanas atrasado";
    elseif($days_diff>=30 AND $days_diff<60)
      $time_alert = "1 mês atrasado";
    elseif($days_diff>=60 AND $days_diff<365)
      $time_alert = floor(abs($days_diff/30)) . " meses atrasado";
    elseif($days_diff>=365 AND $days_diff<730)
      $time_alert = "1 ano atrasado";
    elseif($days_diff>=730)
      $time_alert = floor(abs($days_diff/365)) . " anos atrasado";
    elseif($days_diff<-1 AND $days_diff>=-14)
      $time_alert = abs($days_diff) . " dias adiantado";
    elseif($days_diff<-14 AND $days_diff>-30)
      $time_alert = floor(abs($days_diff/7)) . " semanas adiantado";
    elseif($days_diff<=-30 AND $days_diff>-60)
      $time_alert = "1 mês adiantado";
    elseif($days_diff<=-60 AND $days_diff>-365)
      $time_alert = floor(abs($days_diff/30)) . " meses adiantado";
    elseif($days_diff<=-365 AND $days_diff>-730)
      $time_alert = "1 ano adiantado";
    elseif($days_diff<=-730)
      $time_alert = floor(abs($days_diff/365)) . " anos adiantado";
    elseif($days_diff==0)
      $time_alert = "É hoje!";
  }
  else {
    $time_alert = " ";
    $days_diff = "";
  }
}
else {
  $date_scheduled = $conn->query("SELECT agendado FROM contatos WHERE id =" . $contato['id'] . " LIMIT 1")->fetch_assoc()['agendado'];
  if(strlen($date_scheduled)>1) {
    $datedifference =  time() - strtotime($date_scheduled);
    $days_diff = floor($datedifference/(3600)/24);
    if($days_diff==1)
    $time_alert = "1 dia atrasado";
elseif($days_diff==-1)
  $time_alert = "1 dia adiantado";
elseif($days_diff>1 AND $days_diff<14)
  $time_alert = floor(abs($days_diff)) . " dias atrasado";
elseif($days_diff>14 AND $days_diff<30)
  $time_alert = floor(abs($days_diff/7)) . " semanas atrasado";
elseif($days_diff>=30 AND $days_diff<60)
  $time_alert = "1 mês atrasado";
elseif($days_diff>=60 AND $days_diff<365)
  $time_alert = floor(abs($days_diff/30)) . " meses atrasado";
elseif($days_diff>=365 AND $days_diff<730)
  $time_alert = "1 ano atrasado";
elseif($days_diff>=730)
  $time_alert = floor(abs($days_diff/365)) . " anos atrasado";
elseif($days_diff<-1 AND $days_diff>=-14)
  $time_alert = floor(abs($days_diff)) . " dias adiantado";
elseif($days_diff<-14 AND $days_diff>-30)
  $time_alert = floor(abs($days_diff/7)) . " semanas adiantado";
elseif($days_diff<=-30 AND $days_diff>-60)
  $time_alert = "1 mês adiantado";
elseif($days_diff<=-60 AND $days_diff>-365)
  $time_alert = floor(abs($days_diff/30)) . " meses adiantado";
elseif($days_diff<=-365 AND $days_diff>-730)
  $time_alert = "1 ano adiantado";
elseif($days_diff<=-730)
  $time_alert = floor(abs($days_diff/365)) . " anos adiantado";
elseif($days_diff==0)
  $time_alert = "É hoje!";
  }
  else {
    $time_alert = " ";
    $days_diff ="";
  }
}

echo "<tr ";
if($days_diff>0 AND $contato['estado'] == 0) {
    echo ' style="background-color: #fbdada" ';
}
elseif($days_diff == 0 AND $contato['estado'] == 0) {
    echo ' style="background-color:#fbffca" ';
}
elseif ($contato['estado'] == 1) {
    //echo ' style="background-color:#cae4ca" ';
}
echo " class='contato_click' data-contatoid='".$contato['id']."'>";
