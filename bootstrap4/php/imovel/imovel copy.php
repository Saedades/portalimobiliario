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

$all_entidades = $conn->query('SELECT * FROM entidades WHERE pertence_a =' . $user_id)->fetch_all(MYSQLI_ASSOC);
$entidades = $conn->query('SELECT * FROM entidades WHERE id IN (SELECT entidade FROM imoveis_entidades WHERE imovel = ' . $imovel_id . " AND date_deleted IS NULL)")->fetch_all(MYSQLI_ASSOC);
$photolist = $conn->query('SELECT * FROM imoveis_fotos WHERE idimovel = ' . $imovel_id )->fetch_all(MYSQLI_ASSOC);
$history = $conn->query("SELECT * FROM contatos WHERE idimovel=" . $imovel_id . " AND idimovel<>0")->fetch_all(MYSQLI_ASSOC);
$file_query='SELECT * FROM imoveis_ficheiros WHERE idimovel = ' . $imovel_id;
$ang_files= $conn->query($file_query)->fetch_all(MYSQLI_ASSOC);
$filetypes = $conn->query("SELECT * FROM tipoficheiro")->fetch_all(MYSQLI_ASSOC);
$estado = $conn->query("SELECT status FROM imoveis WHERE iduser = " . $user_id . " AND ID = " . $imovel_id)->fetch_assoc()['status'];
echo "<p> estado: " . $estado ."</p>";
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

    #addfilebutton:hover {
      color: blue;
      cursor: pointer;
    }

    .filetype_option:hover {
      cursor: pointer;
    }

    .tab-pane{
      min-height:58vh;
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
            <li class="nav-item">
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
                                  <input name="subject_file" type="file" class="custom-file-input" id="inputGroupFile02" />
                                  <label class="custom-file-label" for="inputGroupFile02">Selecione</label>
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
                <span style="font-size:2vh;">Documentos</span>&nbsp;&nbsp;&nbsp;<i id="upload_file" class="fas fa-check-circle" style="height:1vh; font-size:3.2vh;margin:0.8vh"></i><span style="font-size:X-small">CLIQUE NO VERDE PARA CARREGAR O NOVO FICHEIRO</span><br>
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
                        foreach($ang_files as $ang_file) {
                          echo '
                        <div id="display_card_group" class="card-group">
                          <div class="card">
                            <div class="card-body" style="padding:5px; ">';
                              echo $conn->query("SELECT nome FROM tipoficheiro WHERE id = " . $ang_file['idtipoficheiro'])->fetch_assoc()['nome']; echo '
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-body" style="padding:5px;">';
                              echo substr($ang_file['url'], strrpos($ang_file['url'], '/')+1, strlen($ang_file['url']) - strrpos($ang_file['url'], '/')-1);
                              echo '
                              <span style="margin-right:10px; float:right;">
                              <i data-fileid="' . $ang_file['id'] . '" class="fas fa-trash deletefile" style="height:1vh; font-size:1.5vh; margin:0">
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
                        <div id="input_card_group2" class="card-group">
                          <div class="card">
                            <select class="form-control" id="exampleFormControlSelect1" style="margin:auto"
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
                        <span id="addfilebutton2">
                          <i class="fas fa-plus" id="addfile2" style="height:1vh; font-size:1.5vh;"></i> &nbsp;Adicionar
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
                <div class="col">
                  <span style="font-size:3vh;">Proprietários</span>
                    <i class="fa fa-plus" id="add_contact"></i>
                    <div class="card" style="margin-top:1vh;">
                      <div class="card-header">
                        Nomes
                      </div>
                      <ul class="list-group list-group-flush">
                        <?php
                        foreach($entidades as $entidade) {
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
                          foreach($all_entidades as $umaentidade) {
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
                <div class="border-left-primary col" style="">
                    <a href="../contato/contato.php?with=<?php echo $imovel_id; ?>" style="" class="btn btn-primary btn-icon-split col-3">
                        <span class="icon text-white-50" style="position:absolute; left:0;">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text" style="padding-left: 2vw;">Contato</span>
                    </a>
                    <?php include '../partials/tabela_contatos.php'; ?>
                </div>
              </div>

            </div>



            <div class="tab-pane fade" id="angariacao" role="tabpanel" aria-labelledby="angariacao-tab">

              <div class="row" style="padding-top:4vh;">

                    <!--- ANGARIAÇÃO --->
                    <div id="angcard" class="card col-4" style="padding:2vh;">
                      <div style="text-align:center">
                            <?php
                              if($result=$conn->query('SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=' . $imovel_id) AND $result->num_rows==0) {
                                echo '<button class="btn btn-primary" onclick="window.location.href=';
                                  echo "'../contrato/angariacao.php?idimovel=" . $imovel_id . "'";
                                  echo '">Criar Angariação</button>';
                              }
                              else {

                                if($aangariacao = $conn->query("SELECT * FROM angariacoes WHERE id =" . $result->fetch_assoc()['idang'] . " AND iduser=" . $_SESSION['login'])->fetch_assoc()) {

                                  //----------------------se ocontato tiver uma entidade associada, busca-la---------------------------//
                                  $id =           (isset($aangariacao['id'])) ?             $aangariacao['id']             : 0;
                                  $estado =       (isset($aangariacao['estado'])) ?         $aangariacao['estado']         : 0;
                                  $created_at =   (isset($aangariacao['created_at'])) ?     $aangariacao['created_at']    : " ";
                              
                                  $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $aangariacao['id'];
                                  $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
                              
                                  $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $aangariacao['id'];
                                  $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
                              
                                  $query = '(SELECT idimo FROM angariacoes_imoveis WHERE idang =' . $aangariacao['id'] . ')';
                              
                                  $title_top = "Editar Angariação";
                              
                              
                                  //--------------------SELECCIONAR CONTATOS RELACIONADOS COM O ATO DE ANGARIAÇAO---------------------//
                                  //                                                                                                  //
                                  //  Selecciona os contatos, que estejam relacionados com os imóveis, que estão relacionados com     //
                                  //  a angariação. Mas também o contato de fecho relacionado com o termino de uma angariação         //
                                  //  Ou seja, por exemplo:                                                                           //
                                  //  ANGARIAÇÃO Nº1  ---> IMOVEL Nº16 ---> CONTATOS Nº2, Nº6, Nº7                                    //
                                  //  OU  ANGARIAÇÃO Nº1 ---> CONTATO Nº 20                                                           //
                                  //--------------------------------------------------------------------------------------------------//
                                  $history = $conn->query(
                                    "SELECT * FROM contatos WHERE id IN
                                        (SELECT DISTINCT contato FROM contatos_imoveis as CTS WHERE imovel IN
                                            (SELECT idimo FROM angariacoes_imoveis as ANGI WHERE idang =" . $id .")
                                        AND imovel<>0 AND id<>0
                                        )
                                        OR id IN
                                        (SELECT contato_fecho FROM angariacoes WHERE id = " .   $id . ")
                                    ")->fetch_all(MYSQLI_ASSOC);
                              
                              
                                    $file_query='SELECT * FROM angariacoes_ficheiros WHERE idangariacoes = ' . $id;
                                    $ang_files= $conn->query($file_query)->fetch_all(MYSQLI_ASSOC);
                                    $risco_query='SELECT * FROM angariacoes_risco ';
                                    $angrisco =  $conn->query($risco_query)->fetch_all(MYSQLI_ASSOC);
                                }
                                else {
                                  $history = [];
                                  $ang_files=[];
                                  $title_top = "Nova Angariação";
                                  echo "error";
                                }

                                $subquery ='(SELECT DISTINCT contato FROM contatos_imoveis as CTS WHERE imovel IN
                                              (SELECT idimo FROM angariacoes_imoveis as ANGI WHERE idang =' . $id . ')
                                            AND imovel<>0 AND id<>0
                                            )';
                                $entidades = $conn->query("SELECT * FROM entidades WHERE id IN (SELECT ident FROM angariacoes_entidades WHERE idang=" . $id . ")");
                                $imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND id in (SELECT idimo FROM angariacoes_imoveis WHERE idang = " . $id . ")")->fetch_all(MYSQLI_ASSOC);
                                $all_imoveis =  $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN(SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
                                $all_entidades = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $_SESSION['login'] . " AND ID NOT IN(SELECT entidade FROM contatos_entidades WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
                                $filetypes = $conn->query("SELECT * FROM tipoficheiro")->fetch_all(MYSQLI_ASSOC);
                                $angstates = $conn->query("SELECT * FROM angariacoes_estados")->fetch_all(MYSQLI_ASSOC);
                                $selected_filetype = $conn->query("SELECT estado FROM angariacoes")->fetch_assoc()['estado'];
                                $selected_risco = $conn->query("SELECT risco FROM angariacoes")->fetch_assoc()['risco'];

                                echo
                                '<div class="form-group" required>
                                  <label for="domtitle"';
                                if(!isset($_GET['id'])) echo 'hidden';
                                  echo '>Título</label><input type="text" class="form-control" id="domangid" name="domangid" value="';
                                echo $id;
                                echo '" style="width:15%" readonly ';
                                if(!isset($_GET['id'])) 
                                  echo 'hidden';
                                echo '>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col">
                                      <label for="domdate">Data</label>
                                      <input type="date" class="form-control" id="domdate" name="domdate" value="';
                                echo substr($created_at, 0, 10); 
                                echo '">
                                    </div>
                                    <div class="col">
                                      <label for="domtime">Hora</label>
                                      <input type="time" class="form-control" id="domtime" name="domtime" value="';
                                echo substr($created_at, 11, 5);
                                echo '">
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="domdate">Estado</label>
                                    <select class="form-control" id="domestado" style="margin:auto" name="domestado">
                                      <option value="0">Escolha o estado</option>';
                                foreach($angstates as $state) {
                                  echo '<option value="' . $state['id'] . '" ';
                                  if($selected_filetype == $state['id'])
                                    echo 'selected';
                                  echo '>'. $state['nome'] . "</option>";
                                }
                                echo '</select>
                                </div>
                                <div class="form-group">
                                    <label for="domdate">Risco</label>
                                    <select class="form-control" id="domrisco" style="margin:auto" name="domrisco">
                                      <option value="0">Escolha o nível de risco</option>';
                                foreach($angrisco as $risco) {
                                  echo '<option value="' . $risco['id'] . '" ';
                                  if($selected_risco == $risco['id'])
                                    echo 'selected';
                                  echo '>'. $risco['nome'] . "</option>";
                                }
                                echo '
                                  </select>
                                </div>';

                                echo '<button class="btn btn-primary" onclick="window.location.href=';
                                echo "'../contrato/angariacao.php?id=" . $result->fetch_assoc()['idang'] . "'";
                                echo '">Editar Angariação</button>';
                              }
                            ?>
                      </div>
                    </div>
                  



                    <div id="rightcard"
                    <?php
                      if(!$conn->query('SELECT idang FROM foco20.vendas_imoveis WHERE idimo=' . $imovel_id)) {
                        echo 'hidden';
                      }
                    ?>
                    >
                      <div id="ven" class="card border-left-info shadow contrato"
                        style="background-color: #f8f9fc; width: 12vw; margin: 1vh;">
                        <div id="vent" class="card-header text-center venhover"
                          style="background-color: #f8f9fc; cursor: pointer; margin-top: 9vh; margin-bottom: 9vh; border: 0;">
                          <a id="venhe">VENDA </a><i id="min_ven" class="cont_fa fa fa-minus"
                            style="float: right; display: none; cursor: pointer;"></i>
                        </div>
                      </div>
                </div>
              </div>
            </div>

          </div>
        <!-- /.container-fluid -->
        </div>
      <!--//footer after container fluid closing--->

      

      <!-- End of Main Content -->
      </div>
    <!-- End of Content Wrapper -->
    <?php include '../partials/modalandscroll.php'; ?>

<?php include '../partials/footer.php'; ?>

</body>
<!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->
<?php include '_imovel_js.php'; ?>


</html>
