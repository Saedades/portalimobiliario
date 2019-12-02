<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';



$active_TAB = (isset($_GET['mn'])) ? $_GET['mn'] : 1;
$active_secondary_TAB = (isset($_GET['tt'])) ? $_GET['tt'] : 999;

//echo "<p>active tab: " . $active_TAB . "</p>";
//echo "<p>active tab: " . $active_secondary_TAB . "</p>";

switch($active_TAB) {

  case 0: 


  case 1:   $query = "SELECT * FROM contatos WHERE user=" . $_SESSION['login'];
            break;  //os meus imoveis

}


if($active_secondary_TAB!='999') {

  $query .= " AND estado =" . $active_secondary_TAB;

}

//echo $query . "<br>";
$imoveis  = ($result = $conn->query($query)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
//var_dump($imoveis);


?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Foco 20 - Seguimentos</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/pretty-checkbox.min" />

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>

  <style>
    .addons {
      transform: translateX(15%);
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

    .dot {
      height: 10px;
      width: 10px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
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
          <!-- Page Heading -->

          <h1 class="h3 mb-4 text-gray-800" style="margin-left:2vw;">Seguimentos</h1> 



          <ul class="nav nav-pills" style="margin-left:3vh; margin-bottom:1vh;">
            <li class="nav-item">
              <a class="nav-link <?php echo ($active_TAB==1) ? 'active' : ''; ?>" href="listacontatos.php?mn=1">Os Meus Seguimentos</a>
            </li>
            <li class="nav-item" >
              <a class="nav-link disabled" href="listacontatos.php?mn=4">Arquivo</a>
            </li>
          </ul> 

          <a href="imovel.php" style="float:right; margin-right:2vw;" class="btn btn-primary btn-icon-split col-2">
            <span class="icon text-white-50" style="position:absolute; left:0;">
              <i class="fas fa-plus"></i>
            </span>
            <span class="text" style="padding-left: 2vw;">Novo Seguimento</span>
          </a>


          <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <?php 
              $estados = ($result = $conn->query('SELECT * FROM estados')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
              
                foreach($estados as $estado) {
                  echo '<li class="nav-item"><a class="nav-link';
                  echo ($active_secondary_TAB==$estado['id']) ? ' active' : '';
                  echo '"  href="listacontatos.php?mn=';
                  echo $active_TAB; 
                  echo '&tt=' . $estado['id'];
                  echo '" >' . $estado['nome'] . '</a></li>';
                }
              
              ?>
              <li class="nav-item"><a class="nav-link
              <?php 
              if($active_secondary_TAB == 999) {
                echo ' active';
              }
              ?>" href="listacontatos.php?mn=<?php echo $active_TAB;?>&tt=999">Todos</a></li>
            </ul>


            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow">
                  <div class="form-group">
                    <div class="card-body">
                      <table class="t_imoveis" style="width:100%">
                        <thead>
                          <tr>
                            <th>Estado</th>
                            <th>Fotografia</th>
                            <th>KWID</th>
                            <th>Título do Seguimento</th>
                            <th>Tipologia</th>
                            <th>Local</th>
                            <th>Preço</th>
                            <th>Acções</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            setlocale(LC_MONETARY, 'pt_PT');
                            foreach($imoveis as $imovel) {
                                echo '<tr class="a_contact" data-theid="' . $imovel['id'] . '">';



                                    echo '<td>';
                                    echo '<span class="dot" style="background-color:';
                                    echo $conn->query('SELECT cor FROM estados WHERE id='.$imovel['estado'])->fetch_assoc()['cor'];
                                    echo '"></span> ' . $conn->query('SELECT nome FROM estados WHERE id='.$imovel['estado'])->fetch_assoc()['nome'];
                                    echo '</td>';


                                    echo '<td>';
                                    if($result = $conn->query('SELECT url FROM imoveis_fotos WHERE imovel='.$imovel['id']) AND $result->num_rows>0)
                                      $url = "../../img/uploads/imoveis/" . $result->fetch_assoc()['url'];
                                    else
                                      $url = "../../img/default.jpg";
                                    echo '<img  width="70" height="70" src="'. $url . '">';
                                    echo '</td>';



                                    echo '<td>'.$imovel['kwid'].'</td>';



                                    echo '<td>'. $imovel['titulo'] . "</td>";



                                    echo '<td>' . (($result=$conn->query('SELECT nome FROM tipologias WHERE id='.$imovel['tipologia'])) ? $result->fetch_assoc()['nome'] : '') . "</td>";



                                    echo '<td>' . $imovel['local'] . "</td>";



                                    echo '<td>';
                                    $val_neg = number_format($imovel['val_neg'], 2);
                                    if(strcmp($val_neg, '0.00')==0) { echo 'Sob consulta'; }
                                    else { echo $val_neg; } 
                                    echo "</td>";


                                    /*
                                    echo '<td >';
                                    echo $conn->query('SELECT nome FROM negocios WHERE id = ' . $imovel['negocio'])->fetch_assoc()['nome'];
                                    echo "</td>";
                                    */


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
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <?php include '../partials/modalandscroll.php' ?>


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
