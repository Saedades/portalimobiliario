<?php
  include '_entidade.php';
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
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
  <link rel="stylesheet" href="../../css/entidade.css" />
  <link href="../../css/subjects.css" rel="stylesheet">
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php include '../partials/sidebar.php'; ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column" >
      <!-- Main Content -->
      <div id="content" style="margin-bottom:2vh;">
        <?php include '../partials/topbar.php'; ?>
        <!-- Begin Page Content -->
        <div class="container-fluid" >


          <a href="<?php echo (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '#'; ?>" class="btn btn-light btn-icon-split col-sm-1"
          <?php echo (isset($_SERVER['HTTP_REFERER'])) ? '' : 'hidden' ?>>
              <span class="icon text-white-50" style="position:absolute; left:0;">
                  <i class="fas fa-backward"></i>
              </span>
              <span class="text" style="padding-left: 1vw;">Voltar</span>
          </a><br><br>


          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?php echo $title?></h1>

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="detalhes-tab" data-toggle="tab" href="#detalhes" role="tab" aria-controls="detalhes" aria-selected="true">Detalhes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="relacoes-tab" data-toggle="tab" href="#relacoes" role="tab" aria-controls="relacoes" aria-selected="false">Relações</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="detalhes" role="tabpanel" aria-labelledby="detalhes-tab">
              <div class="row">
                  <form class="col-4 border-left-primary m-lg-3" action="_controlador_entidade.php" method="POST" style="min-width:220px; max-width: 30vw;">
                    <div class="form-group" <?php if($status>0) echo "hidden";?>>
                        <label id="domid" for="domid">ID</label>
                        <input type="text" class="form-control" id="domid" name="domid" value="<?php echo $identidade?>" style="width:5vw;" readonly>
                    </div>
                    <input type="text" name="action_type" value="3" hidden>


                    <div class="form-group" <?php if($status==-1) echo "hidden";?>>
                        <label id="domnamelabel" for="domname">Nome</label>
                        <input type="text" class="form-control" id="domname" name="domname" value="<?php echo $name ?>" />
                    </div>


                    <div class="form-group">
                      <label class="form-check-label" for="domtipo">Tipo de entidade</label>
                      <select class="form-control" id="domtipo" name="domtipo">
                        <option selected disabled>Escolha...</option>
                        <?php
                        foreach($listatipo as $tipo) {
                          echo '<option style="font-size:1.5vh" value="' . $tipo['id'] . '" ';
                          if($tipo['id']==$tipo_selected) {
                            echo "selected";
                          }
                          echo '>' . $tipo['nome'] . '</option>';
                        }
                        ?>
                      </select>
                    </div>


                    <div class="form-group" >
                      <label class="form-check-label" for="dominteresse">Interesse</label>
                      <select class="form-control" id="dominteresse" name="dominteresse">
                        <option selected disabled>Escolha...</option>
                        <?php
                        foreach($listainteresse as $interesse) {
                          echo '<option style="font-size:1.5vh" value="' . $interesse['id'] . '" ';
                          if($interesse['id']==$interesse_selected) {
                            echo "selected";
                          }
                          echo '>' . $interesse['nome'] . '</option>';
                        }
                        ?>
                      </select>
                    </div>



                    <div class="form-group row" id="addrform" <?php if($status==-1) echo "hidden";?>>
                      <div class="col-7">
                        <label for="domaddr">Morada</label>
                        <textarea type="text" class="form-control" id="domaddr" name="domaddr" style="height:9vh;"><?php echo $morada;?></textarea>
                      </div>

                      <div class="col-5">
                        <label for="domlocal">Código Postal</label>
                        <div class="row" style="width:105%">
                          <input class="form-control col-12" id="domlocal" name="domlocal" type="text" placeholder="Localidade" value="<?php echo $local;?>">
                        </div>
                        <div class="row" style="margin-top:1vh;">
                          <input class="form-control col-6" style="text-align: right;" id="zip1" name="domzip1" type="text" maxlength="4" value="<?php echo $zip1;?>">
                          <span style="font-size:3vh; margin-left:0.3vw;margin-right:0.3vw;"> - </span>
                          <input class="form-control col-4" id="zip2" name="domzip2" type="text" maxlength="3" value="<?php echo $zip2;?>">
                        </div>
                      </div>
                    </div>
                    <div class="form-group" id="nifform" <?php if($status==-1) echo "hidden";?>>
                        <label for="domnif">NIF / ID fiscal</label>
                        <input type="number" class="form-control" id="domnif" name="domnif" value="<?php echo $nif;?>">
                    </div>
                    <div class="form-group" <?php if($status==-1) echo "hidden";?>>
                        <label for="domemail">Endereço de E-mail</label>
                        <input type="email" class="form-control" id="domemail" name="domemail" value="<?php echo $email;?>" required >
                    </div>
                    <div class="row" <?php if($status==-1) echo "hidden";?>>
                        <div class="form-group col-6" id="phnrform">
                            <label for="domemail">Número de Telemóvel</label>
                            <input type="text" class="form-control" id="domphnr" name="domphnr" maxlength="9" value="<?php echo $phnr;?>">
                        </div>
                        <div class="form-group col-6">
                            <label for="dombirth">Data de Nascimento</label>
                            <input type="date" class="form-control" id="dombirth" name="dombirth" value="<?php echo $birth;?>">
                        </div>
                    </div>

                    <div class="form-group" id="formgroup_estado" hidden>
                      <label class="form-check-label" for="domactive">Estado</label>
                      <select class="form-control" id="domactive" name="estado">
                        <option selected disabled>Escolha...</option>
                        <?php
                        foreach($listaestados as $estado) {
                          echo '<option style="font-size:1.5vh" value="' . $estado['id'] . '" ';
                          if($estado['id']==$status) {
                            echo "selected";
                          }
                          echo '>' . $estado['nome'] . '</option>';
                        }
                        ?>
                      </select>
                    </div>


                    <br>


                    <?php if($status==-1) echo "<p>Esta entidade requisitou o direito ao esquecimento e todas as tuas informações foram apagadas sem recuperação. O seu ID é mantido por questões de integridade mas não corrompe a anonimidade.</p>";?>
                    <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd" <?php if($status==-1) echo "hidden";?>>Salvar</button>
                    <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer;" <?php if($status==-1) echo "hidden";?>>Apagar</div>
                    <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer;" <?php if($status==-1) echo "hidden";?>>Editar</div>
                    <div id="forgetbtn" class="btn btn-danger" name="forgetbtn" style="cursor:pointer;" <?php if($status==-1) echo "hidden";?>>Esquecer Permanentemente</div>
                  </form>


                </div>
              </div>

            <div class="tab-pane fade show active" id="relacoes" role="tabpanel" aria-labelledby="relacoes-tab">
              <!--- SECOND ROW --->
              <div class="row" <?php if(!isset($_GET['id'])) { echo 'hidden';} ?>>
                <form class="col-12 border-left-primary m-lg-3 row" method="POST">
                  <div class="col-sm-5">
                    <span style="font-size:3vh;" >Entidades associadas</span>
                    <i class="fa fa-plus" id="add_entidade"></i>
                    <div class="card" style="width: 100%; margin-top:1vh;">
                      <div class="card-header">
                        Nomes
                      </div>
                      <ul class="list-group list-group-flush">
                        <?php
                        function opposite_relation($Arel) {
                          switch($Arel) {
                            case 2: return 8; break;
                            case 8: return 2; break;
                            default: return $Arel;
                          }
                        }

                        foreach($entidades_assoc as $linha) {
                          if($linha['entidadeA'] == $_GET['id']) {
                            $target_id = $linha['entidadeB'];
                            $relation_id = opposite_relation($linha['relacao']);
                          }
                          else {
                            $target_id = $linha['entidadeA'];
                            $relation_id = $linha['relacao'];
                          }

                          $relation_name = $conn->query("SELECT name FROM relations WHERE idrelations =" . $relation_id)->fetch_assoc()['name'] ;
                          $entidade_B_name = $conn->query("SELECT nome FROM entidades WHERE id =" . $target_id)->fetch_assoc()['nome'] ;
                          $entidade_B_telemovel = $conn->query("SELECT telemovel FROM entidades WHERE id =" . $target_id)->fetch_assoc()['telemovel'] ;
                          echo '
                          <li class="list-group-item" >
                            <span class="col-5">' . $relation_name
                              .
                            '</span>
                            <a href="entidade?id='. $target_id .'" class="col-5">' .
                              $entidade_B_name .
                            '</a>
                            <span class="col-3">' .
                              $entidade_B_telemovel .
                            '</a>
                            <i class="fas fa-times delcontato" data-idc="'. $linha['id'] .'" style="float:right"></i>
                          </li>';
                        }
                        ?>

                          <li class="list-group-item" id="add_li" style="display: none;">
                            <div class="form-group row">


                              <!----------------------------seleccionar tipo de relação - familiar, profissional..---------------------->
                              <select class="browser-default custom-select col-5" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();' id="domrelationid" name="domrelationid">
                                <option disabled selected value> Relação </option>
                                <?php
                                foreach($relations as $relationship) {
                                  echo '<option class="opt" value="' . $relationship['idrelations'] . '" ';
                                  echo '>'. $relationship['name']. "</option>";
                                }
                                ?>
                              </select>
                              <!------------------------------------------------------------------------------------------------------->


                              <!-------------------------------------------seleccionar entidade --------------------------------------->
                              <select style="margin-left:15px;" class="browser-default custom-select col-5 " onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();' id="domentidadeid" name="domentidadeid">
                                <option disabled selected value> Entidade </option>
                                <?php
                                foreach($all_entidades as $umaentidade) {
                                  if($umaentidade['id'] != $_GET['id']) {
                                    echo '<option value="' . $umaentidade['id'] . '" ';
                                    echo '>'. $umaentidade['nome']. "</option>";
                                  }
                                }
                                ?>
                              </select>
                              <!------------------------------------------------------------------------------------------------------->

                              <div class="col-1" id="savecol">
                                <div class="font-weight-bold text-uppercase mb-1" style="color:gray;">
                                  <i class="fas fa-check-circle" id="savenewrole"  style="height:1vh; font-size:3.2vh"></i>
                                </div>
                              </div>

                            </div>
                          </li>
                        </ul>
                      </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
      </div>
      <!--//footer after container fluid closing--->

      <?php include '../partials/footer.php'; ?>

    <!-- End of Main Content -->
  </div>
  <!-- End of Content Wrapper -->
  <?php include '../partials/modalandscroll.php'; ?>


  <div id="modal_2" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Escolha o tipo de entidade</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" >
                  <label class="form-check-label" for="domtipomodal">Tipo de entidade</label>
                  <select class="form-control" id="domtipomodal" >
                    <option selected disabled>Escolha...</option>
                    <?php
                    foreach($listatipo as $tipo) {
                      echo '<option style="font-size:1.5vh" value="' . $tipo['id'] . '" ';
                      if($tipo['id']==$tipo_selected) {
                        echo "selected";
                      }
                      echo '>' . $tipo['nome'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <button id="tipo_seguinte" type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" style="float:right;" disabled>Seguinte</button>
            </div>
        </div>
    </div>
</div>

</body>
<!-- End of Page Wrapper -->


<!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->
<?php include '_entidade_js.php'; ?>

</html>
