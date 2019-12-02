<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials//validate_session.php';
//$query = "SELECT * FROM (SELECT rt.* , rdt.completed as detail_completed, MAX(rdt.created_at) as created_at FROM foco20.contatos rt
                                //    LEFT JOIN contatos_details as rdt ON rdt.idcontato = rt.id WHERE rt.iduser=" . $_SESSION['login'] . " GROUP BY rt.id) AS new_table ORDER BY completed ASC, date";
$contatos = $conn->query('SELECT * FROM contatos')->fetch_all(MYSQLI_ASSOC);

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
          <h1 class="h3 mb-4 text-gray-800">Contatos</h1>
        </div>
        <!-- /.container-fluid -->
        <div class="card shadow m-2 col-md-9 addons">
          <div class="card-header py-1 row" style="width:auto; padding-right:0">
            <h6 style="" class="m-2 font-weight-bold text-primary col-3" hidden>Lista de Contatos</h6>
            <a href="contato.php" style="" class="btn btn-primary btn-icon-split col-3">
              <span class="icon text-white-50" style="position:absolute; left:0;">
                <i class="fas fa-plus"></i>
              </span>
              <span class="text" style="padding-left: 2vw;">Contato</span>
            </a>
          </div>
          <div class="form-group">
            <input type="file" class="form-control-file" id="profilePicInput" name="profpic" hidden>
            <div class="card-body">
              <table id="schedule_table">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Título</th>
                    <th>Fio</th>
                    <th>Entidades</th>
                    <th>Imóveis</th>
                    <th>Estado</th>
                    <th hidden></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                        foreach($contatos as $contato) {

                                //calling this function creates the <tr> tag, a time warning and styling of row
                                //include '../partials/timewarning.php';
                                echo '<tr>';

                                echo "<td>";
                                    echo $contato['agendado'];
                                echo "</td>";

                                echo "<td>";
                                    echo $contato['titulo'];
                                echo "</td>";

                                echo "<td>";
                                    echo $contato['seguimento'];
                                echo "</td>";

                                //----------------------CONTATOS--------------------//
                                echo "<td>";

                                  $max=1;
                                  $entidades_assoc = $conn->query("SELECT entidade FROM contatos_entidades WHERE contato=" . $contato['id']);

                                  foreach($entidades_assoc as $item) {
                                    if($max<3) {
                                      echo $conn->query('SELECT nome FROM entidades WHERE id=' . $item['entidade'])->fetch_assoc()['nome'] . "; ";
                                    }
                                    else {
                                      echo $conn->query('SELECT nome FROM entidades WHERE id=' . $item['entidade'])->fetch_assoc()['nome'] . "...";
                                      break;
                                    }
                                    $max++;
                                  }

                                echo "</td>";
                                //------------------------------------------------//



                                //----------------------IMOVEIS--------------------//
                                echo "<td>";
                                  $max=1;
                                  $imoveis_assoc = $conn->query("SELECT imovel FROM contatos_imoveis WHERE contato=" . $contato['id']);
                                  foreach($imoveis_assoc as $item) {
                                    if($max<3) {
                                      echo $conn->query('SELECT title FROM imoveis WHERE id=' . $item['imovel'])->fetch_assoc()['title'] . "; ";
                                    }
                                    else {
                                      echo $conn->query('SELECT title FROM imoveis WHERE id=' . $item['imovel'])->fetch_assoc()['title'] . "...";
                                      break;
                                    }
                                    $max++;
                                  }
                                echo "</td>";
                                //------------------------------------------------//


                                //----------------------IMOVEIS--------------------//
                                echo '<td>
                                  <div class="form-check form-check-inline">
                                    <div class="pretty p-default p-round">
                                      <input class="completed_check" type="checkbox" data-id="'.$contato['id'].'"';

                                if(isset($contato['completed']) AND strlen($contato['completed'])>1)
                                  echo 'checked';
                                echo
                                      '>
                                      <div class="state p-success-o">
                                        <label> </label>
                                      </div>
                                    </div>
                                  </div>';
                                echo "</td>";
                                //------------------------------------------------//

                                echo "<td hidden>" . '<i class="fas fa-times delete_evento"></i>' . "</td>";
                            echo "</tr>";
                            }
                            ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Main Content -->


      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; 2019 ALOHA - Bussiness & Software, Lda</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!---<link rel="stylesheet" href="css/pretty-checkbox.min" />-->

  <script>
    $(document).ready(function () {

      $('#schedule_table').DataTable({
        "order": [],
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false,
        "bInfo": false
      });

      $('.completed_check').click(function () {
        var contato = $(this).data('id');
        var state = 0;
        var resp = 0;
        if ($(this).prop("checked")) {
          state = 1;
        }
        $.ajax({
          url: 'contato_change_state.php',
          type: 'post',
          async: false,
          data: {
            'contato': contato,
            'state': state
          },
          success: function (data, textStatus, jQxhr) {
            if(data==0) {
              swal({
                  text: "Pendente!",
                  icon: "warning",
                })
            }
            else {
              swal({
                  text: "Terminado!",
                  icon: "success",
                })
            }

          },
          error: function (jqXhr, textStatus, errorThrown) {
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
    });

  </script>

</body>

</html>
