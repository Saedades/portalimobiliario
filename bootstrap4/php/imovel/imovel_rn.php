<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';


if(isset($_GET['id'])) {
  	//if is editing
	$title = "Editar Imóvel";
	$imovel_id = $_GET['id'];
	$user_id = $_SESSION['login'];

	//get more DB info
	$imovel_pre_info = $conn->query("SELECT * FROM imoveis WHERE iduser = " . $user_id . " AND ID = " . $imovel_id);

	//if the query was successful
	if($imovel_pre_info) {
		$imovel_info = $imovel_pre_info->fetch_assoc();
		$imovel_title = $imovel_info['title'];
		$imovel_kwid = $imovel_info['KWID'];
		$imovel_ref = $imovel_info['referencia'];
		$imovel_description = $imovel_info['descricao'];
		$imovel_status = $imovel_info['status'];
		$imovel_value = $imovel_info['val_neg'];
		$imovel_value2 = $imovel_info['val_co_contra'];
		$imovel_value3 = $imovel_info['val_co_cobra'];
		$imovel_domaddr = $imovel_info['morada'];
		$imovel_local = $imovel_info['local'];
		$imovel_zip1 = $imovel_info['zip1'];
		$imovel_zip2 = $imovel_info['zip2'];
	}
	else {
		header('Location: ../profile/profile.php');
	}
}
else {
	//if is creating
	$title = "Novo Imóvel";
	$imovel_id = 0;
	$user_id = $_SESSION['login'];
	$imovel_title = '';
	$imovel_kwid = '';
	$imovel_ref = '';
	$imovel_description = "";
	$imovel_status = "";
	$imovel_value = "";
	$imovel_value2 = "";
  $imovel_value3 = "";
  $imovel_local = "";
  $imovel_domaddr = "";
  $imovel_zip1 = '';
  $imovel_zip2 = '';
}

$all_entidades = $conn->query('SELECT * FROM entidades WHERE user =' . $user_id)->fetch_all(MYSQLI_ASSOC);
$entidades = ($result = $conn->query('SELECT * FROM entidades WHERE id IN (SELECT entidade FROM imoveis_entidades WHERE imovel = ' . $imovel_id . " AND date_deleted IS NULL)")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$photolist = ($result = $conn->query('SELECT * FROM imoveis_fotos WHERE idimovel = ' . $imovel_id )) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$history = ($result = $conn->query("SELECT * FROM contatos")) ? $result->fetch_all(MYSQLI_ASSOC): array();
$imo_files = ($result = $conn->query('SELECT * FROM imoveis_ficheiros WHERE idimovel = ' . $imovel_id)) ? $result->fetch_all(MYSQLI_ASSOC): array();
$ang_id = ($result = $conn->query('SELECT idang FROM angariacoes_imoveis WHERE idimo =' . $imovel_id)) ? $result->fetch_assoc()['idang'] : 0;
$ang_files = ($result = $conn->query('SELECT * FROM angariacoes_ficheiros WHERE idangariacoes = ' . $ang_id)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$filetypes = ($result = $conn->query("SELECT * FROM tipoficheiro")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$estado = ($result = $conn->query("SELECT status FROM imoveis WHERE iduser = " . $user_id . " AND ID = " . $imovel_id) ) ? $result->fetch_assoc()['status'] : 0;
$selected_risco_value = $conn->query("SELECT risco FROM foco20.angariacoes WHERE id IN (SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=" . $imovel_id . ")")->fetch_assoc()['risco'];
$selected_estado_value = $conn->query("SELECT estado FROM foco20.angariacoes WHERE id IN (SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=" . $imovel_id . ")")->fetch_assoc()['estado'];
$tipologias = ($result = $conn->query("SELECT * FROM tipologias")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$empreendimentos = ($result = $conn->query("SELECT * FROM tiposimo")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
$selected_tipologia_value = ($result =$conn->query("SELECT tipologia FROM foco20.imoveis WHERE id =" . $imovel_id))? $result->fetch_assoc()['tipologia'] : 0;
$selected_emp_value = ($result = $conn->query("SELECT tipoimo FROM foco20.imoveis WHERE id =" . $imovel_id)) ? $result->fetch_assoc()['tipoimo'] : 0;

$action = 1;
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


  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/subjects.css" rel="stylesheet">
  <style>
    .deletefile:hover {
      color: darkred;
      cursor: pointer;
    }

    .ang_deletefile:hover {
      color: darkred;
      cursor: pointer;
    }

    .deletenewfile:hover {
      color: darkred;
      cursor: pointer;
    }

    .deletenewphoto {
      color: gray;
    }

    .deletenewphoto:hover {
      color: darkred;
      cursor: pointer;
    }

    #addfile:hover {
      color: blue;
      cursor: pointer;
    }

    #addfilebutton_ang:hover {
      color: blue;
      cursor: pointer;
    }

    .filetype_option:hover {
      cursor: pointer;
    }

    .tab-pane{
      min-height:58vh;
    }

    .report_click:hover {
      background-color: white !important;
      cursor: pointer;
    }

    .datetimebox {
      width: 500px;
    }

    .datetime-input-box-wrapper {
      min-width: 500px;
    }

    .opt:hover {
      color: white;
      background-color: #8397d1 !important;
      cursor: pointer;
    }

    .opt:active {
      color: white;
      background-color: #6b86d3 !important;
    }

    .deletefile:hover {
      color:darkred;
      cursor:pointer
    }

    .circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      border-color: white;
      font-size: small;
      color: white;
      line-height: 35px;
      text-align: center;
      background-color: #c5c5c5;
      display: inline-block;
      margin-right:10px;
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

          <!---
          <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-light btn-icon-split col-sm-1">
            <span class="icon text-white-50" style="position:absolute; left:0;">
              <i class="fas fa-backward"></i>
            </span>
            <span class="text" style="padding-left: 1vw;">Voltar</span>
          </a>
          --->

          <br><br>

          <div id="alert" class="alert alert-success alert-dismissible" <?php
                if(!(isset($_GET['code']))) {
                    echo " hidden";
                }
            ?>>
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <span id="changes" <?php
                    if(isset($_GET['code']) AND $_GET['code']!=1) {
                        echo " hidden";
                    }
                ?>><strong>Sucesso!</strong> Alterações foram guardadas.</span>
            <span id="newcont" <?php
                if(isset($_GET['code']) AND $_GET['code']!=2) {
                    echo " hidden";
                }
                ?>><strong>Sucesso!</strong> Novo imóvel criado.</span>
          </div>



          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?php echo $title?></h1>


          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="detalhes-tab" data-toggle="tab" href="#detalhes" role="tab" aria-controls="detalhes" aria-selected="true">Detalhes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="ficheiros-tab" data-toggle="tab" href="#ficheiros" role="tab" aria-controls="ficheiros" aria-selected="false">Ficheiros</a>
            </li>
            <li class="nav-item" hidden>
              <a class="nav-link" id="entidades-tab" data-toggle="tab" href="#entidades" role="tab" aria-controls="entidades" aria-selected="false">Entidades</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="angariacao-tab" data-toggle="tab" href="#angariacao" role="tab" aria-controls="angariacao" aria-selected="false">Angariação</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContent">


            <div class="tab-pane fade show active" id="detalhes" role="tabpanel" aria-labelledby="detalhes-tab">

              <div class="row">

                <form id="subjectForm" action="_imovel.php" method="POST" style="" class="border-left-primary m-lg-2 col-8 row">

                  <div class="col" style="padding-top:4vh;">

                    <input type="text" id="domid" name="domid" value="<?php echo $imovel_id; ?>" hidden>
                    <input type="text" id="domuser" name="domuser" value="<?php echo $user_id; ?>" hidden>

                    <div class="form-group">
                      <label for="domtitle">Nome</label>
                      <input type="text" class="form-control" id="domtitle" name="domtitle"
                        value="<?php echo $imovel_title;?>" required>
                    </div>

                    <div class="row">

                      <div class="form-group col-6">
                        <label for="domkwid">KWID</label>
                        <input type="text" class="form-control" id="domkwid" name="domkwid"
                          value="<?php echo $imovel_kwid;?>" required>
                      </div>

                      <br>

                      <div class="form-group col-6">
                        <label for="domref">ID</label>
                        <input type="text" class="form-control" id="domref" name="domref" value="<?php echo $imovel_ref;?>"
                          required>
                      </div>

                    </div>

                    <div class="form-group row" id="addrform">

                      <div class="col-7">
                        <label for="domaddr">Morada</label><a id="mapslink" target="_blank" style="float:right"><i
                            class="fas fa-map"></i><span style="margin-left:10px"> Google Maps </span></a>
                        <textarea type="text" class="form-control" id="domaddr" name="domaddr"
                          value=""><?php echo $imovel_domaddr;?></textarea>
                      </div>

                      <div class="col-5">
                        <label for="domlocal">Código Postal</label>
                        <div class="row" style="width:105%">
                          <input class="form-control col-12" id="domlocal" name="domlocal" type="text"
                            placeholder="Localidade" value="<?php echo $imovel_local;?>">
                        </div>
                        <div class="row" style="margin-top:1vh;">
                          <input class="form-control col-6" style="text-align: right;" id="zip1" name="domzip1" type="text"
                            maxlength="4" value="<?php echo $imovel_zip1; ?>">
                          <span style="font-size:3vh; margin-left:0.3vw;margin-right:0.3vw;"> - </span>
                          <input class="form-control col-4" id="zip2" name="domzip2" type="text" maxlength="3"
                            value="<?php echo $imovel_zip2;?>">
                        </div>
                      </div>

                    </div>

                    <div class="form-group">
                      <div class="mapouter" style="margin:14px">
                        <div class="gmap_canvas"><iframe width="468" height="270" id="gmap_canvas"
                            src="https://maps.google.com/maps?q=&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"
                            scrolling="no" marginheight="0" marginwidth="0"></iframe>Google Maps Generator by <a
                            href="https://www.embedgooglemap.net">embedgooglemap.net</a></div>
                        <style>
                          .mapouter {
                            position: relative;
                            text-align: right;
                            height: 270px;
                            width: 466px;
                          }

                          .gmap_canvas {
                            overflow: hidden;
                            background: none !important;
                            height: 270px;
                            width: 466px;
                          }

                        </style>
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="domdescr">Descrição</label>
                      <textarea type="text" class="form-control" id="domdescr" name="domdescr"
                        value=""><?php echo $imovel_description;?></textarea>
                    </div>

                    <br>

                    <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd">Salvar</button>
                    <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer">Apagar</div>
                    <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer">Editar</div>
                  </div>

                  <div class="col" style="padding-top:4vh;">
                    <div class="row">
                      <div class="col-6">
                        <label for="domval_neg">Valor de Negócio</label><br><br>
                        <label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">€</span>
                          </div>
                          <input id="domvalor" name="domvalor" type="text" style="text-align: right;" class="form-control"
                            aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value; ?>">
                          <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                          </div>
                        </div>
                        <br>
                      </div>

                      <div class="col-6">
                        <label for="domval_co_contra">Valor de Comissão Contratado</label>
                        <label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">€</span>
                          </div>
                          <input id="domvalor2" name="domvalor2" type="text" style="text-align: right;" class="form-control"
                            aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value2; ?>">
                          <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                          </div>
                        </div>
                        <br>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <label for="domvalor">Valor de Comissão Cobrada</label>
                        <label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">€</span>
                          </div>
                          <input id="domvalor3" name="domvalor3" type="text" style="text-align: right;" class="form-control"
                            aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value3; ?>">
                          <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                          </div>
                        </div>
                        <br>
                      </div>

                    </div>

                    <div class="form-group">
                      <label for="checkstate">Estado</label>
                      <select class="form-control" id="exampleFormControlSelect1" name="domestado">
                        <option value="0" <?php if($estado=='0') { echo 'selected'; } ?>>Inativo</option>
                        <option value="1" <?php if($estado=='1') { echo 'selected'; } ?>>Ativo</option>
                        <option value="2" <?php if($estado=='2') { echo 'selected'; } ?>>Reservado</option>
                        <option value="3" <?php if($estado=='3') { echo 'selected'; } ?>>Agendado</option>
                        <option value="4" <?php if($estado=='4') { echo 'selected'; } ?>>Pré-vendido</option>
                        <option value="5" <?php if($estado=='5') { echo 'selected'; } ?>>Vendido</option>
                      </select>
                    </div>

                  </div>

                </form>


                <div class="m-lg-2 col " style="padding-top:4vh;">
                  <span style="font-size:3vh;">Fotografias</span>
                    <i class="fa fa-plus" id="add_photo"></i>
                    <div class="card" style="margin-top:1vh;">
                      <div class="card-header">
                        Nomes e miniaturas
                      </div>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item" id="add_li_ph" style="display: none;">
                          <form name="imgform" enctype="multipart/form-data" id="imgform" method="POST"
                            action="_upload_subject_image.php">
                            <input name="subjectid" type="text" value="<?php echo $imovel_id;?>" hidden>
                            <div class="row">
                              <div class="input-group">
                                <div class="custom-file">
                                  <input name="subject_file" type="file" class="custom-file-input" id="image_file_group" />
                                  <label class="custom-file-label" for="image_file_group">Selecione</label>
                                </div>
                              </div>
                            </div>
                            <div class="row" style="margin-top:5px">
                              <div class="input-group col card" style="padding:0;">
                                <div id="image_preview"
                                  style="height:12vh; width:94%; background-size:cover; background-position:center; background-image:url('img/default.jpg'); margin:auto; margin-top:5px; margin-bottom:5px">
                                </div>
                              </div>
                              <div class="input-group col">
                                <div class="font-weight-bold text-uppercase"
                                  style="color:gray; width:4vw; margin:auto; text-align:center;">
                                  <i class="fas fa-check-circle" id="savenewphoto" style="height:1vh; font-size:3.2vh;"></i>
                                </div>
                              </div>
                            </div>
                          </form>
                        </li>
                        <?php
                          $counter_photos=0;
                            foreach($photolist as $photo) {
                            $counter_photos++;
                            echo '
                            <li class="list-group-item" id="add_li_ph">
                              <div class="row" style="margin-top:5px">
                                <div class="input-group col card" style="padding:0;">
                                  <div id="image_preview" style="height:12vh; width:94%; background-size:cover; background-position:center; ';
                            echo 			"background-image:url('../../img/uploads/imoveis/" . $photo['urlphoto'] . "');";
                            echo 			' margin:auto; margin-top:5px; margin-bottom:5px">';
                            echo '
                                  </div>
                                </div>
                                <div class="input-group col">
                                  <span class="badge badge-pill badge-light" style="padding-top:45%">'.$counter_photos.'</span>
                                  <div class="font-weight-bold text-uppercase" style="color:red; width:4vw; margin:auto; text-align:center;">
                                    <i data-photoid="' . $photo['idip'] . '" class="fas fa-trash deletenewphoto" style="height:1vh; font-size:2vh;">
                                    </i>
                                  </div>
                                </div>
                              </div>
                            </li>';
                          }
                        ?>
                    </ul>
                  </div>
                </div>


              </div>

            </div>



            <div class="tab-pane fade" id="ficheiros" role="tabpanel" aria-labelledby="ficheiros-tab">
              <!----------------------------------------------------ADD A FILE------------------------------------------------>
              <form id="documents_div" class="col-12 m-1" action="_controlador_ficheiros.php" method="POST" enctype="multipart/form-data" style="padding-top:4vh;">
              <span style="font-size:2.5vh; padding-left:2vh;">Documentos do Imóvel</span>&nbsp;&nbsp;&nbsp;<i id="upload_file" class="fas fa-check-circle" style="height:1vh; font-size:3.2vh;margin:0.8vh"></i><span style="font-size:X-small">CLIQUE NO VERDE PARA CARREGAR O NOVO FICHEIRO</span><br>
                  <div class="card" style="width: 100%; margin-top:1vh;">
                    <div id="ficheirosbody" >
                        <div id="display_card_group" class="card-group">
                          <div class="card">
                            <div class="card-header text-center">Categorias</div>
                          </div>
                          <div class="card">
                            <div class="card-header text-center">Nome</div>
                          </div>
                        </div>




                        <!----//LISTA DE FICHEIROS DO IMOVEL----->
                        <?php
                        foreach($imo_files as $imo_file) {
                          echo '
                          <div id="display_card_group" class="card-group">
                            <div class="card">
                              <div class="card-body" style="padding:5px; ">';
                                echo $conn->query("SELECT nome FROM tipoficheiro WHERE id = " . $imo_file['idtipoficheiro'])->fetch_assoc()['nome']; echo '
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-body" style="padding:5px;">';
                                echo substr($imo_file['url'], strrpos($imo_file['url'], '/')+1, strlen($imo_file['url']) - strrpos($imo_file['url'], '/')-1);
                                echo '
                                <span style="margin-right:10px; float:right;">
                                <i data-fileid="' . $imo_file['id'] . '" class="fas fa-trash deletefile" style="height:1vh; font-size:1.5vh; margin:0">
                                </i>
                              </span>
                              </div>
                            </div>
                          </div>';

                        }
                        ?>
                        <!----//FIM LISTA DE FICHEIROS DO IMOVEL----->



                        <input type="text" value="1" name="type_of_contract" hidden>
                        <input type="text" value="<?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>" name="idimo" hidden>
                        <div id="input_card_group" class="card-group">
                          <div class="card">
                            <select class="form-control" id="btn_tipo" style="margin:auto"
                              name="ifiletype">
                              <option class="filetype_option">Escolha o tipo</option>
                              <?php
                              foreach($filetypes as $type) {
                                echo '<option class="filetype_option" value="' . $type['id'] . '">'. utf8_decode(utf8_encode($type['nome'])) . "</option>";
                              }
                              ?>
                            </select>
                          </div>
                          <div class="card">
                            <div class="custom-file col">
                              <input type="file" class="custom-file-input" id="ifile" name="ifile">
                              <label id="filenamedisplay" class="custom-file-label" for="customFile">Ficheiro</label>
                            </div>
                          </div>
                    </div><div class="" style="height:4vh; width:95%; margin:auto; padding:10px; text-align:center;">
                        <span id="addfile">
                          <i class="fas fa-plus" id="addfile" style="height:1vh; font-size:1.5vh;"></i> &nbsp;Adicionar
                          ficheiro &nbsp;&nbsp;&nbsp;
                        </span>
                      </div>
                      <br>

                    </div>
                  </div>
                </form>
            </div>



            <div class="tab-pane fade" id="entidades" role="tabpanel" aria-labelledby="entidades-tab">

              <div class="row" style="padding-top:4vh;">

                <div class="border-left-primary col" style="">
                    <a href="../contato/contato.php?with=<?php echo $imovel_id; ?>" style="" class="btn btn-primary btn-icon-split col-3">
                        <span class="icon text-white-50" style="position:absolute; left:0;">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text" style="padding-left: 2vw;">Contato</span>
                    </a>
                    <table id="contatos_table">
                      <thead>
                        <tr>
                          <th>
                            Data
                          </th>
                          <th>
                            Título
                          </th>
                          <th>
                            Estado
                          </th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        if($history) {
                          foreach($history as $contato) {
                            echo "<tr class='contato_click' data-contatoid='".$contato['id']."'>";
                            echo "<td>" . $contato['date'] . "</td>";
                            echo "<td>" . $contato['titulo'] . "</td>";
                            //echo "<td>" . $contato['description'] . "</td>";
                            echo "<td>";
                            echo '
                                          <div class="pretty p-default p-round" style="top:-2.5vh">
                                          <input class="completed_check" type="checkbox" data-id="' . $contato['id'] . '"  ';
                            if(strlen($contato['completed'])>1) {
                              echo " checked";
                            }
                            echo '>';
                            echo '
                                      <div class="state p-success-o">
                                        <label>' . '</label>
                                      </div>
                                    ';
                            echo '</div>';
                            echo "</td>";
                            echo "</tr>";
                          }
                        }
                        ?>


                      </tbody>
                    </table>

                </div>
              </div>

            </div>



            <div class="tab-pane fade" id="angariacao" role="tabpanel" aria-labelledby="angariacao-tab">
              <div class="row" style="padding-top:4vh; ">
                <div id="angcard" class="card" style="padding:2vh;width:100%; margin-bottom:2vh;">

                  <div class="row">
                  <?php
                  $entidades_prop = $entidades;
                  $all_entidades_prop = $all_entidades;
                  ?>
                    <form action="_controlador_angariacao.php" method="POST" enctype="multipart/form-data" id="file_form" class="col-9">

                      <div class="row">
                        <div class="col" >
                          <span style="font-size:3vh">Detalhes da Angariação</span><br><br>
                          <?php include 'anghelp.php'; ?>

                          <input type="text" id="domuserid" name="domuserid" value="<?php echo $_SESSION['login']; ?>" hidden>

                          <div class="form-group" required>
                            <input type="text" class="form-control" id="domangid" name="domangid" value="<?php echo $id;?>" style="width:15%" readonly <?php  if(!isset($_GET['id'])) echo 'hidden'; ?>>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col">
                                <label for="domdate">Data</label>
                                <input type="date" class="form-control" id="domdate" name="domdate" value="<?php echo substr($created_at, 0, 10); ?>">
                              </div>
                              <div class="col">
                                <label for="domtime">Hora</label>
                                <input type="time" class="form-control" id="domtime" name="domtime" value="<?php echo substr($created_at, 11, 5); ?>">
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">

                              <div class="col">
                                <label for="domdate">Estado</label>
                                <select class="form-control" id="domestado" style="margin:auto" name="domestado">
                                  <option value="0">Escolha o estado</option>
                                  <?php
                                    foreach($angstates as $state) {
                                      echo '<option value="' . $state['id'] . '" ';
                                      if($selected_estado_value == $state['id'])
                                        echo 'selected';
                                      echo '>'. $state['nome'] . "</option>";
                                    }
                                  ?>
                                </select>
                              </div>

                              <div class="col">
                                <label for="domdate">Risco</label>
                                <select class="form-control" id="domrisco" style="margin:auto" name="domrisco">
                                  <option value="0">Escolha o nível de risco</option>
                                  <?php
                                    foreach($angrisco as $risco) {
                                      echo '<option value="' . $risco['id'] . '" ';
                                      if($selected_risco_value == $risco['id'])
                                        echo ' selected ';
                                      echo '>' . $risco['nome'] . "</option>";
                                    }
                                  ?>
                                </select>
                              </div>

                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">

                              <div class="col">
                                <label for="domtipologia">Tipologia</label>
                                <select class="form-control" id="domtipologia" style="margin:auto" name="domtipologia">
                                  <option value="0">Escolha a tipologia</option>
                                  <?php
                                    foreach($tipologias as $tipologia) {
                                      echo '<option value="' . $tipologia['id'] . '" ';
                                      if($selected_tipologia_value == $tipologia['id'])
                                        echo 'selected';
                                      echo '>'. $tipologia['nome'] . "</option>";
                                    }
                                  ?>
                                </select>
                              </div>

                              <div class="col">
                                <label for="domemp">Tipo de Empreendimento</label>
                                <select class="form-control" id="domemp" style="margin:auto" name="domemp">
                                  <option value="0">Escolha o tipo de empreendimento</option>
                                  <?php
                                    foreach($empreendimentos as $emp) {
                                      echo '<option value="' . $emp['id'] . '" ';
                                      if($selected_emp_value == $emp['id'])
                                        echo ' selected ';
                                      echo '>' . $emp['nome'] . "</option>";
                                    }
                                  ?>
                                </select>
                              </div>

                            </div>
                          </div>

                        </div>



                        <div class="col">
                          <div id="documents_div" class="col-12 m-1" style="width:100%; min-height:50vh">
                            <span style="font-size:3vh; ">Documentos</span>
                              <div class="card border-left-primary " style="width: 100%; margin-top:0.5vh">
                                <div id="angbody" style="padding-bottom:2vh;">

                                  <div id="display_card_group" class="card-group">
                                    <div class="card">
                                      <div class="card-header text-center">Categorias</div>
                                    </div>
                                    <div class="card">
                                      <div class="card-header text-center">Nome</div>
                                    </div>
                                  </div>

                                  <?php
                                  foreach($ang_files as $ang_file) {
                                    echo '
                                  <div id="display_card_group" class="card-group">
                                    <div class="card">
                                      <div class="card-body" style="padding:5px; height:3.5vh;">';
                                        echo $conn->query("SELECT nome FROM tipoficheiro WHERE id = " . $ang_file['idtipoficheiro'])->fetch_assoc()['nome']; echo '
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-body" style="padding:5px;">';
                                        echo substr($ang_file['url'], strrpos($ang_file['url'], '/')+1, strlen($ang_file['url']) - strrpos($ang_file['url'], '/')-1);
                                        echo '
                                        <span style="margin-right:10px; float:right;">
                                        <i data-fileid="' . $ang_file['id'] . '" class="fas fa-trash ang_deletefile" style="height:1vh; font-size:1.5vh; margin:0">
                                        </i>
                                      </span>
                                      </div>
                                    </div>
                                  </div>';
                                  }
                                  ?>

                                  <input type="text" value="1" name="type_of_contract" hidden>

                                  <div id="input_card_group_ang" class="card-group">
                                    <div class="card">
                                      <select class="form-control" id="btn_tipo_ang"  style="margin:auto"
                                        name="ifiletype">
                                        <option class="filetype_option">Escolha o tipo</option>
                                        <?php
                                          foreach($filetypes as $type) {
                                            echo '<option class="filetype_option" value="' . $type['id'] . '">'. utf8_decode(utf8_encode($type['nome'])) . "</option>";
                                          }
                                        ?>
                                      </select>
                                    </div>
                                    <div class="card">
                                      <div class="custom-file col">
                                        <input type="file" class="custom-file-input" id="ifile_ang" name="ifile_ang">
                                        <label id="filenamedisplay_ang" class="custom-file-label" for="customFile">Ficheiro</label>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="" style="height:8vh; width:95%; margin:auto; padding:10px; text-align:center;">
                                    <button type="submit" class="circle" id="upload_file_ang">OK</button>
                                    <span style="font-size:X-small" hidden>CLIQUE OK PARA CARREGAR O NOVO FICHEIRO</span><br>
                                    <span id="addfilebutton_ang">
                                      <i class="fas fa-plus" id="addfile_ang" style="height:1vh; font-size:1.5vh;"></i> &nbsp;Adicionar
                                      ficheiro &nbsp;&nbsp;&nbsp;
                                    </span><br>
                                  </div>

                                </div>
                              </div>
                          </div>
                        </div>


                      </div>
                    </form>

                    <div class="col">
                      <span style="font-size:3vh;">Proprietários</span>
                      <i class="fa fa-plus" id="add_owner"></i>
                      <div class="card" style="margin-top:1vh;">
                        <div class="card-header">
                          Nomes
                        </div>
                        <ul class="list-group list-group-flush">
                          <?php
                          foreach($entidades_prop as $entidade) {
                            echo '
                            <li class="list-group-item" >
                              <a href="../entidade/entidade?id='. $entidade['id'] .'">' .
                            $entidade['nome'] .
                            '</a>
                              <i class="fas fa-times delcontato" data-idc="'. $entidade['id'].'" style="float:right"></i>
                            </li>';
                          }
                          ?>
                          <li class="list-group-item" id="add_li" style="display: none;">
                            <div class="form-group row">
                              <select class="browser-default custom-select col-10" onfocus='this.size=5;' onblur='this.size=1;'
                                onchange='this.size=1; this.blur();' id="domcontatoid" name="domcontatoid">
                                <option disabled selected value> </option>
                                <?php
                            foreach($all_entidades_prop as $umaentidade) {
                              if(strlen($umaentidade['nome'])>1)
                              echo '<option class="opt" value="' . $umaentidade['id'] . '" ';
                              if(/*$umaentidade['id'] == $contato_id OR*/ (isset($_GET['who']) AND $umaentidade['id'] == $_GET['who'])) {
                                echo 'selected';
                              }
                              echo '>'. $umaentidade['nome'] . "</option>";
                            }
                            ?>
                              </select>
                              <div class="col-2" id="savecol">
                                <div class="font-weight-bold text-uppercase mb-1" style="color:gray;">
                                  <i class="fas fa-check-circle" id="savenewrole" style="height:1vh; font-size:3.2vh"></i>
                                </div>
                              </div>

                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>


                  <div class="row" style="padding:2vh;">
                    <button class="btn btn-primary" id="angedit" style="margin-right:1vh;">
                      Editar
                    </button>
                    <button type="submit" class="btn btn-success" id="angsave" style="margin-right:1vh;">
                      Salvar
                    </button>
                    <button class="btn btn-danger" id="angdel" style="margin-right:1vh;">
                      Apagar
                    </button>
                  </div>

                  <script src="../../vendor/jquery/jquery.min.js"></script>
                  <script>
                  $(document).ready(function(){
                    alert("okkkk");
                  });
                  </script>


                </div>
              </div>
            </div>

          <!-- /.container-fluid -->
          </div>
        <!--//footer after container fluid closing--->



      <!-- End of Main Content -->
      </div>
    <!-- End of Content Wrapper -->
    <!---<?php //include '../partials/modalandscroll.php'; ?>---->

<?php include '../partials/footer.php'; ?>





</body>
<!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->



</html>
