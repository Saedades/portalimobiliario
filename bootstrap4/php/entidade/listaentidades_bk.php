<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';



$active_TAB = (isset($_GET['mn'])) ? $_GET['mn'] : 1;
$active_secondary_TAB = (isset($_GET['tt'])) ? $_GET['tt'] : 1;

//echo "<p>active tab: " . $active_TAB . "</p>";
//echo "<p>active tab: " . $active_secondary_TAB . "</p>";

switch($active_TAB) {

  case 0:


  case 1:   $query = "SELECT * FROM entidades WHERE user=" . $_SESSION['login'] ;
            break;  //as minhas entidades

  case 2:   $query = "SELECT * FROM entidades WHERE tabela=1";
            break; //os imoveis da rede

  default:  $query = "SELECT * FROM entidades WHERE user=" . $_SESSION['login'] . " AND tabela=0";
            $active_TAB=1;
            break; //os imoveis da rede

}


if($active_secondary_TAB!='999' AND $active_secondary_TAB!='50' AND $active_secondary_TAB!='1') {

  $query .= " AND categoria =" . $active_secondary_TAB . ' AND lead=1';

}
elseif ($active_secondary_TAB=='50') {

  $query .= ' AND lead=1';

}

//echo $query . "<br>";
$listaentidades  = ($result = $conn->query($query)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
//var_dump($listaentidades);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Foco 20 - Entidades</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="sweetalert2.min.css">


  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

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

    .list-group-item-action:hover {
      filter: brightness(95%);
    }

    #addnote:hover {
      color:#4e73df;
      cursor:pointer
    }


    #efetuar:hover {
      color:#4e73df;
      cursor:pointer
    }

    .list-group-item-custom {
      list-style-type: none;
      padding-left:1vh;
    }

    .modal { overflow: auto !important; }

    .greentask {
      background-color:#e7f2e8;
    }

    .yellowtask {
      background-color:#fffeeb;
    }

    .redtask {
      background-color:#ffe3e3;
    }

    .seg_addnote {
      color:#4e73df;
      cursor:pointer
    }

    .btn-nao-efetuado {
      background-color:#ffe3e3;
    }

    .btn-efetuado {
      background-color:#e7f2e8;
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

          <h1 class="h3 mb-4 text-gray-800" style="margin-left:2vw;">Entidades</h1>



          <ul class="nav nav-pills" style="margin-left:3vh; margin-bottom:1vh;">
            <li class="nav-item">
              <a class="nav-link <?php echo ($active_TAB==1) ? 'active' : ''; ?>" href="listaentidades.php?mn=1">As Minhas Entidades</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($active_TAB==2) ? 'active' : ''; ?>" href="listaentidades.php?mn=2" >Entidades da Rede</a>
            </li>
            <li class="nav-item" >
              <a class="nav-link disabled" href="listaentidades.php?mn=4">Arquivo</a>
            </li>
          </ul>

          <a href="entidade.php" style="float:right; margin-right:2vw;" class="btn btn-primary btn-icon-split col-2">
            <span class="icon text-white-50" style="position:absolute; left:0;">
              <i class="fas fa-plus"></i>
            </span>
            <span class="text" style="padding-left: 2vw;">Nova Entidade</span>
          </a>


          <div class="container-fluid">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <?php
              $estados = ($result = $conn->query('SELECT * FROM categorias ORDER BY id')) ? $result->fetch_all(MYSQLI_ASSOC) : array();

                foreach($estados as $estado) {
                  echo '<li class="nav-item"><a class="nav-link';
                  echo ($active_secondary_TAB==$estado['id']) ? ' active' : '';
                  echo '"  href="listaentidades.php?mn=';
                  echo $active_TAB;
                  echo '&tt=' . $estado['id'];
                  echo '" >' . (($estado['nome']==='Todos') ? 'Contatos' : 'Lead ' . $estado['nome']) . '</a></li>';
                  if($estado['nome']==='Todos') {
                    echo '<li class="nav-item"><a class="nav-link';
                    echo ($active_secondary_TAB=='50') ? ' active' : '';
                    echo '"  href="listaentidades.php?mn=';
                    echo $active_TAB;
                    echo '&tt=' . '50';
                    echo '" >' . 'Leads' . '</a></li>';
                  }
                }

              ?>
            </ul>


            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow">
                  <div class="form-group">
                    <div class="card-body">
                        <table id="listaentidadestable">
                            <thead>
                                <th style="width:30%">Nome</th>
                                <th style="width:25%">Rating</th>
                                <th style="width:25%">Categoria</th>
                                <th style="width:15%">Telemóvel</th>
                                <th style="width:25%">Email</th>
                                <th style="width:5%" hidden></th>
                            </thead>
                            <tbody>
                                <?php
                                foreach($listaentidades as $ent) {

                                    echo '<tr class="a_contact" data-theid="' . $ent['id'] . '">';
                                        echo '<td style="width:30%">'. $ent['nome'] . "</td>";
                                        echo '<td style="width:15%">'.	$conn->query("SELECT nome FROM ratings WHERE id=" . ((isset($ent['rating'])) ? $ent['rating'] : 0))->fetch_assoc()['nome'] ."</td>";
                                        echo '<td style="width:25%">'.$conn->query("SELECT nome FROM categorias WHERE id=" . ((isset($ent['categoria'])) ? $ent['categoria'] : 0))->fetch_assoc()['nome']."</td>";
                                        echo '<td style="width:25%">'.$ent['telemovel']."</td>";
                                        echo '<td style="width:25%">'.$ent['email']."</td>";
                                        echo "<td hidden>" . '<i class="fas fa-times delete_contato"></i>' . "</td>";
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

<!---
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="../../css/pretty-checkbox.min" />
--->
  <?php include '_entidade_js.php'; ?>



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


                                          <a href="#" class="list-group-item list-group-item-action task" >
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0; ">2019-09-06 Todo o dia</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">PRÓXIMO</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-10">Telefonema: Ligar e agendar titulo</div>
                                                  <div class="col"><i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i></div>
                                              </div>
                                              <div class="row" style="padding-top: 1vh; padding-left:1vw;">
                                                  <ul class="list-group" style="font-size:1.5vh">
                                                      <li class="list-group-item-custom"><span style="width:10%;">2019-12-01 </span> - Cras justo odio</li>
                                                      <li class="list-group-item-custom"><span style="width:10%;">2019-12-02 </span> - Dapibus ac facilisis in</li>
                                                      <li class="list-group-item-custom"><span style="width:10%;">2019-07-03 </span> - Morbi leo risus</li>
                                                  </ul>
                                              </div>
                                          </a>

                                          <a href="#" class="list-group-item list-group-item-action task" style="background-color:#e7f2e8;">
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0; ">2019-09-06 14:00</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">EFETUADO</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col">Email: Enviar morada</div>
                                              </div>
                                              <div class="row" style="padding-top: 1vh; padding-left:1vw;">
                                                  <ul class="list-group" style="font-size:1.5vh">
                                                      <li class="list-group-item-custom"><span style="width:10%;">2019-12-01 </span> - Cras justo odio</li>
                                                  </ul>
                                              </div>
                                          </a>
                                          <a href="#" class="list-group-item list-group-item-action task" style="background-color:#ffe3e3;">
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0;">2019-09-30 19:00</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">EM ATRASO</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col">Visita</div>
                                                  <div class="col"><i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i></div>
                                              </div>
                                          </a>
                                          <a href="#" class="list-group-item list-group-item-action task" style="background-color:#fffeeb;">
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0;">2019-07-25 20:00</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">HOJE</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col">Telefonema</div>
                                                  <div class="col"><i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i></div>
                                              </div>
                                          </a>
                                          <a href="#" class="list-group-item list-group-item-action task" >
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0; ">2019-09-06 Todo o dia</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">PRÓXIMO</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-10">Telefonema: Ligar e agendar titulo</div>
                                                  <div class="col"><i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i></div>
                                              </div>
                                              <div class="row" style="padding-top: 1vh; padding-left:1vw;">
                                                  <ul class="list-group" style="font-size:1.5vh">

                                                  </ul>
                                              </div>
                                          </a>
                                          <a href="#" class="list-group-item list-group-item-action task" >
                                              <div class="row" style="font-size:1.5vh;">
                                                  <div class="col"><p style="float:left;  margin:0; ">2019-09-06 Todo o dia</p></div>
                                                  <div class="col"><p style="float:right; margin:0; font-weight:600;">PRÓXIMO</p></div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-10">Telefonema: Ligar e agendar titulo</div>
                                                  <div class="col"><i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i></div>
                                              </div>
                                              <div class="row" style="padding-top: 1vh; padding-left:1vw;">
                                                  <ul class="list-group" style="font-size:1.5vh">

                                                  </ul>
                                              </div>
                                          </a>

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

                            <input id="segid" name="segid" value="" hidden />

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



</body>

</html>
