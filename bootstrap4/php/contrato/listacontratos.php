<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once '../partials/connectDB.php';
  include_once '../partials/validate_session.php';




  $query = 'SELECT * FROM angariacoes WHERE iduser=' . $_SESSION['login'] . ' AND deleted_at IS NULL';
  $ang = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
 foreach($ang as $unit) {
    $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $ang[0]['id'];
 }

  $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
  $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $ang[0]['id'];
  $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
  $final_query = 'SELECT a1.*, a2.*, a3.*
                  FROM angariacoes AS a1
                  INNER JOIN    angariacoes_entidades AS a2 ON a1.id = a2.idang
                  INNER JOIN    angariacoes_imoveis AS a3 ON a1.id = a3.idang
                  WHERE a1.iduser=' . $_SESSION['login'] . ' AND a1.deleted_at IS NULL';
  $angariacoes = $conn->query($final_query)->fetch_all(MYSQLI_ASSOC);





  $realang = array();
  foreach($ang AS $item) {
    $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $item['id'];
    $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $item['id'];
    $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $realang[] = ['id' => $item['id'], 'estado'=> $item['estado'], 'imoveis' => $ang_imoveis, 'entidades' => $ang_entidades];
  }




  //------------------------------------------------------------------------------------------//





  $query = 'SELECT * FROM vendas WHERE iduser=' . $_SESSION['login'] . ' AND deleted_at IS NULL';
  $ven = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
  if($ven) {
    $query = 'SELECT * FROM vendas_entidades WHERE idven =' . $ven[0]['id'];
    $ven_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    $query = 'SELECT * FROM vendas_imoveis WHERE idven =' . $ven[0]['id'];
    $ven_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    $final_query = 'SELECT a1.*, a2.*, a3.*
                    FROM vendas AS a1
                    INNER JOIN    vendas_entidades AS a2 ON a1.id = a2.idven
                    INNER JOIN    vendas_imoveis AS a3 ON a1.id = a3.idven
                    WHERE a1.iduser=' . $_SESSION['login'] . ' AND a1.deleted_at IS NULL';
    $vendas = $conn->query($final_query)->fetch_all(MYSQLI_ASSOC);
  }
  else {
    $ven=[];
  }


  $realven = array();
  foreach($ven AS $item) {
    $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $item['id'];
    $ven_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $item['id'];
    $ven_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $realven[] = ['id' => $item['id'], 'estado'=> $item['estado'], 'imoveis' => $ven_imoveis, 'entidades' => $ven_entidades];
  }

  //print("<pre>".print_r($realang,true)."</pre>");

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Foco 20</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .addons {
      transform: translateX(15%);
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

    .to_complete{
        background-color: #ffffff!important;
    }


    .ang_click:hover {
      color: white;
      background-color: #8397d1;
      cursor: pointer;
    }

    .ang_click:active {
      color: white;
      background-color: #6b86d3;
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
          <!-- Page Heading -->
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Angariações</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vendas</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">

            <!--------------------------------------------TAB ANGARIACOES------------------------------------------->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="card shadow m-2 col-md-9 addons">

                <div class="card-header py-1 row" style="width:auto; padding-right:0">
                  <h6 style="" class="m-2 font-weight-bold text-primary col-3" hidden>Lista de Angariações</h6>
                  <a href="angariacao.php" style="" class="btn btn-primary btn-icon-split col-3">
                    <span class="icon text-white-50" style="position:absolute; left:0;">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text" style="padding-left: 2vw;">Angariação</span>
                  </a>
                </div>


                <div class="form-group">
                  <div class="card-body">
                    <table id="ang_table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Estado</th>
                          <th>Imoveis</th>
                          <th>Entidades</th>
                          <th hidden></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                          foreach($realang as $ang) {
                            echo '<tr class="ang_click" data-angid="' . $ang['id'] . '">';
                              //calling this function creates the <tr> tag, a time warning and styling of row
                              echo "<td>";
                                echo $ang['id'];
                              echo "</td>";
                              echo "<td>";
                                switch($ang['estado']) {
                                  case 1: echo 'Iniciado'; break;
                                  case 2: echo 'CPCV'; break;
                                  case 3: echo 'Cancelado'; break;
                                  case 4: echo 'Fechado';
                                }
                              echo "</td>";
                              echo "<td>";
                                $max=1;
                                foreach($ang['imoveis'] as $imo) {
                                  if($max<3) {
                                    echo $conn->query('SELECT title FROM imoveis WHERE id=' . $imo['idimo'])->fetch_assoc()['title'] . "; ";
                                  }
                                  else {
                                    echo $imo['idimo'] . "...";
                                    break;
                                  }

                                }
                              echo "</td>";
                              echo "<td>";
                                $max=1;
                                foreach($ang['entidades'] as $ent) {
                                  if($max<3) {
                                    echo $conn->query('SELECT nome FROM entidades WHERE id=' . $ent['ident'])->fetch_assoc()['nome'] . "; ";
                                  }
                                  else {
                                    echo $ent['ident'] . "...";
                                    break;
                                  }

                                }
                              echo "</td>";

                              echo "<td hidden>" .
                                '<i class="fas fa-times delete_evento"></i>';
                              echo "</td>";
                            echo "</tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>


              </div>
            </div>





            <!------------------------------------------------------------------------------------------------
            //    IDS:      profile, ang_table
            //    CLASSES:  ang_click
            //    OTHERS:   data-angid
            ----------------------------------------------TAB VENDA------------------------------------------->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="card shadow m-2 col-md-9 addons">


                <div class="card-header py-1 row" style="width:auto; padding-right:0">
                  <h6 style="" class="m-2 font-weight-bold text-primary col-3" hidden>Lista de Vendas</h6>
                  <a href="venda.php" style="" class="btn btn-primary btn-icon-split col-3">
                    <span class="icon text-white-50" style="position:absolute; left:0;">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text" style="padding-left: 2vw;">Venda</span>
                  </a>
                </div>


                <div class="form-group">
                  <div class="card-body">
                    <table id="ven_table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Estado</th>
                          <th>Imoveis</th>
                          <th>Entidades</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                          foreach($realven as $ven) {
                            echo '<tr class="ven_click" data-venid="' . $ven['id'] . '">';

                              echo "<td>";
                                echo $ven['id'];
                              echo "</td>";

                              echo "<td>";
                                switch($ven['estado']) {
                                  case 1: echo 'Iniciado'; break;
                                  case 2: echo 'CPCV'; break;
                                  case 3: echo 'Cancelado'; break;
                                  case 4: echo 'Fechado';
                                }
                              echo "</td>";


                              echo "<td>";
                                $max=1;
                                foreach($ven['imoveis'] as $imo) {
                                  if($max<3) {
                                    echo $conn->query('SELECT title FROM imoveis WHERE id=' . $imo['idimo'])->fetch_assoc()['title'] . "; ";
                                  }
                                  else {
                                    echo $imo['idimo'] . "...";
                                    break;
                                  }

                                }
                              echo "</td>";


                              echo "<td>";
                                $max=1;
                                foreach($ven['entidades'] as $ent) {
                                  if($max<3) {
                                    echo $conn->query('SELECT nome FROM entidades WHERE id=' . $ent['ident'])->fetch_assoc()['nome'] . "; ";
                                  }
                                  else {
                                    echo $ent['ident'] . "...";
                                    break;
                                  }

                                }
                              echo "</td>";


                            echo "</tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>


            </div>
          </div>







        </div>
      </div>
    </div>
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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!---<link rel="stylesheet" href="css/pretty-checkbox.min" />-->

  <script>
    $(document).ready(function () {

      $('#ang_table').DataTable({
        "order": [],
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false,
        "bInfo": false
      });

      $('#ven_table').DataTable({
        "order": [],
        "paging": false,
        "bInfo": false
      });



      $('.ang_click').dblclick(function () {
        var theid = $(this).data('angid');
        window.location.replace('angariacao.php?id=' + theid);
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

      $('.delete_contato').click(function () {
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



    //----------------------------------------------------------------//
    //----------------------------------------------------------------//
    //----------------------------------------------------------------//






      $('.ven_click').dblclick(function () {
        var theid = $(this).data('venid');
        window.location.replace('venda.php?id=' + theid);
      });

    });
  </script>

</body>

</html>
