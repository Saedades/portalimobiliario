<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';


//-----------------------INPUTS-------------------------//
//  $contato_id
//  $entidade_id
//  $imovel_id
//  $title
//  $date
//  $hour
//  $description
//  $title_top
//------------------------------------------------------//


if(isset($_GET['id'])) {

  //-----------------------------------------------------//
  //-----------------se for uma edição-------------------//
  //-----------------------------------------------------//

  if($avenda = $conn->query("SELECT * FROM vendas WHERE id =" . $_GET['id'] . " AND iduser=" . $_SESSION['login'])->fetch_assoc()) {

    //----------------------se ocontato tiver uma entidade associada, busca-la---------------------------//
    $id =           (isset($avenda['id'])) ?             $avenda['id']             : 0;
    $estado =       (isset($avenda['estado'])) ?         $avenda['estado']         : 0;
    $created_at =   (isset($avenda['created_at'])) ?     $avenda['created_at']    : " ";

    $query = 'SELECT * FROM vendas_entidades WHERE idven =' . $avenda['id'];
    $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $query = 'SELECT * FROM vendas_imoveis WHERE idven =' . $avenda['id'];
    $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    $query = '(SELECT idimo FROM vendas_imoveis WHERE idven =' . $avenda['id'] . ')';

    $title_top = "Editar Venda";


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
              (SELECT idimo FROM vendas_imoveis as ANGI WHERE idven =" . $id .")
          AND imovel<>0 AND id<>0
          )
          OR id IN
          (SELECT contato_fecho FROM vendas WHERE id = " .   $id . ")
      ")->fetch_all(MYSQLI_ASSOC);
      $file_query='SELECT * FROM vendas_ficheiros WHERE idvendas = ' . $id;
      $ven_files= $conn->query($file_query)->fetch_all(MYSQLI_ASSOC);
      $risco_query='SELECT * FROM vendas_risco ';
      $angrisco =  $conn->query($risco_query)->fetch_all(MYSQLI_ASSOC);
  }
  else {
    $history = [];
    $ven_files=[];
    echo "error";
  }


}
else {

  //-----------------------------------------------------//
  //---------------se for um novo contato----------------//
  //-----------------------------------------------------//

  $id =           (isset($avenda['id'])) ?             $avenda['id']             : 0;
  $estado =       (isset($avenda['estado'])) ?         $avenda['estado']         : 0;
  $created_at =   (isset($avenda['created_at'])) ?     $avenda['created_at']    : " ";

  $query = 'SELECT * FROM vendas_entidades WHERE idven =' . $id;
  $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

  $query = 'SELECT * FROM vendas_imoveis WHERE idven =' . $id;
  $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

  $title = " ";
  $entidade_id = 0;
  $imovel_id = 0;
  $entidade_nome = " ";
  $description = " ";
  $date = '"NULL"';
  $hour='00:00';
  $title_top = "Nova Venda";
  $history = [];
  $ven_files=[];
}


//----------------------      OUT SIDE IF     -------------------------//`
/*
  $date =         (isset($_GET['date']))  ?   $_GET['date']   :   $date;
  $hour =         (isset($_GET['hour']))  ?   $_GET['hour']   :   $hour;
  $date =         (isset($_GET['date']))  ?   $_GET['date']   :   $date;
  $with =         (isset($_GET['with']))  ?   $_GET['with']   :   $imovel_id;
  $contato_id =   (isset($_GET['id']))    ?   $_GET['id']   :   0;
*/

  // ----------------------   GET LISTS   --------------------------//

  $subquery ='(SELECT DISTINCT contato FROM contatos_imoveis as CTS WHERE imovel IN
  (SELECT idimo FROM vendas_imoveis as ANGI WHERE idven =' . $id . ')
AND imovel<>0 AND id<>0
)';

  //$entidades = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $_SESSION['login'] . " AND id in (SELECT entidade FROM contatos_entidades WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
  $entidades = $conn->query("SELECT * FROM entidades WHERE id IN (SELECT ident FROM vendas_entidades WHERE idang=" . $id . ")");


//--------------------------------------------------------------  SELECCAO DOS IMOVEIS RELACIONADOS ---------------------------------------------------//

    //ESTA VERSÃO SELECIONA OS IMOVEIS RELACIONADOS COM ESTA ANGARIAÇÃO DIRECTAMENTE DA TABELA vendas_imoveis, MAS TAMBÉM DOS IMOVEIS PERTENCENTES ÀS ENTIDADES ASSOCIADAS
    //$imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND id in (SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL) OR id IN (SELECT idimo FROM vendas_imoveis WHERE idven = " . $id . ")")->fetch_all(MYSQLI_ASSOC);

  //ESTA VERSÃO SELECIONA OS IMOVEIS RELACIONADOS apenas DOS IMOVEIS PERTENCENTES ÀS ENTIDADES ASSOCIADAS
    //$imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND id in (SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);

  //ESTA VERSÃO SELECIONA OS IMOVEIS RELACIONADOS COM ESTA ANGARIAÇÃO DIRECTAMENTE DA TABELA vendas_imoveis
    $imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND id in (SELECT idimo FROM vendas_imoveis WHERE idven = " . $id . ")")->fetch_all(MYSQLI_ASSOC);

    //--------------------------------------------------------------  SELECCAO DOS IMOVEIS RELACIONADOS ---------------------------------------------------//
















  //echo "SELECT * FROM contatos WHERE ((idimovel=" . $with . " AND idimovel<>0) OR (identidade=" . $entidade_id . " AND idimovel<>0)) AND id<>". $contato_id;
  //echo "SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN(SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL)";
  $all_imoveis =  $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN(SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
  $all_entidades = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $_SESSION['login'] . " AND ID NOT IN(SELECT entidade FROM contatos_entidades WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
  $filetypes = $conn->query("SELECT * FROM tipoficheiro")->fetch_all(MYSQLI_ASSOC);
  $angstates = $conn->query("SELECT * FROM vendas_estados")->fetch_all(MYSQLI_ASSOC);
  $selected_filetype = $conn->query("SELECT estado FROM vendas")->fetch_assoc()['estado'];
  $selected_risco = $conn->query("SELECT risco FROM vendas")->fetch_assoc()['risco'];



  //IF GET['idimovel] THEN AUTOMATICALLY CREATE THE HOUSE RAISING
  // that is done in jquery
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
      <div id="content" style="height:100vh">

        <?php include '../partials/topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid row">

          <!-- Page Heading -->





          <form class="col-5 border-left-primary m-lg-3" action="_controlador_angariacao.php" method="POST"
            style="min-width:220px; max-width: 30vw;" enctype="multipart/form-data" id="file_form">
            <!----------------    action="php/_upload_subject_file.php"     ---------->
            <h1 class="h3 mb-4 text-gray-800"><?php echo $title_top; ?></h1>
            <input type="text" id="domuserid" name="domuserid" value="<?php echo $_SESSION['login']; ?>" hidden>
            <div class="form-group" required>
              <label for="domtitle" <?php  if(!isset($_GET['id'])) echo 'hidden'; ?>>Título</label>
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
                <label for="domdate">Estado</label>
                <select class="form-control" id="domestado" style="margin:auto" name="domestado">
                  <option value="0">Escolha o estado</option>
                  <?php
                    foreach($angstates as $state) {
                      echo '<option value="' . $state['id'] . '" ';
                      if($selected_filetype == $state['id'])
                        echo 'selected';
                      echo '>'. $state['nome'] . "</option>";
                    }
                  ?>
                </select>
            </div>
            <div class="form-group">
                <label for="domdate">Risco</label>
                <select class="form-control" id="domrisco" style="margin:auto" name="domrisco">
                  <option value="0">Escolha o nível de risco</option>
                  <?php
                    foreach($angrisco as $risco) {
                      echo '<option value="' . $risco['id'] . '" ';
                      if($selected_risco == $risco['id'])
                        echo 'selected';
                      echo '>'. $risco['nome'] . "</option>";
                    }
                  ?>
                </select>
            </div>
          <br>





            <div id="documents_div" class="col-12 m-1" style="max-height:100vh; overflow-y: scroll; overflow-x:none;">
                <span style="font-size:2vh;">Documentos</span>
                <!---&nbsp;&nbsp;&nbsp;<i id="upload_file" class="fas fa-check-circle" style="height:1vh; font-size:3.2vh;margin:0.8vh"></i><span style="font-size:X-small">CLIQUE NO VERDE PARA CARREGAR O NOVO FICHEIRO</span><br>--->
                  <div class="card" style="width: 100%; margin-top:1vh;">
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
                        foreach($ven_files as $ang_file) {
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
                              <i data-fileid="' . $ang_file['id'] . '" class="fas fa-trash deletefile" style="height:1vh; font-size:1.5vh; margin:0">
                              </i>
                            </span>
                            </div>
                          </div>
                        </div>';

                        }
                        ?>

                        <input type="text" value="1" name="type_of_contract" hidden>
                        <div id="input_card_group" class="card-group">
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
                        </div>
                      <div class="" style="height:4vh; width:95%; margin:auto; padding:10px; text-align:center;">
                        <button type="submit" class="circle" id="upload_file">OK</button>
                        <span style="font-size:X-small" hidden>CLIQUE OK PARA CARREGAR O NOVO FICHEIRO</span><br>
                        <span id="addfilebutton">
                          <i class="fas fa-plus" id="addfile" style="height:1vh; font-size:1.5vh;"></i> &nbsp;Adicionar
                          ficheiro &nbsp;&nbsp;&nbsp;
                        </span><br>
                      </div>
                      <br>

                    </div>
                  </div>
            </div>









            <br>
            <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd">Salvar</button>
            <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer">Apagar</div>
            <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer;">Editar</div>
          </form>




          <!------------------------------------------------>

          <div class="col-6 border-left-primary m-lg-3" style="min-width:220px; max-width: 50vw;" <?php  if(!isset($_GET['id'])) echo 'hidden'; ?> >

            <table id="history">
              <thead>
                <tr>
                  <td>
                    Data
                  </td>
                  <td>
                    Título
                  </td>
                  <td>
                    Contato
                  </td>
                  <td>
                    Estado
                  </td>
                </tr>
              </thead>
              <tbody>

                <?php
                      foreach($history as $contato) {

                        include '../partials/timewarning.php';
                          echo "<td>" . $contato['date'] . "</td>";
                          echo "<td>" . $contato['titulo'] . "</td>";
                          echo "<td>" . $conn->query("SELECT * FROM entidades WHERE id = " . $contato['identidade'])->fetch_assoc()['nome'] . "</td>";
                          echo "<td>";
                          echo '<div class="form-check form-check-inline">
                                  <div class="pretty p-default p-round">
                                  <input class="completed_check" type="checkbox" data-id="' . $contato['id'] . '"  ';
                                if($contato['completed']==1) {
                                    echo " checked";
                                }
                          echo '>';
                          echo '
                            <div class="state p-success-o">
                              <label>' . $time_alert . '</label>
                            </div>
                          </div>';
                          echo '</div>';
                          echo "</td>";
                          echo "</tr>";
                      }
                      ?>

              </tbody>
            </table>




              <div class="row" style="margin-top:2vh;">

                <div class="col" style="max-height:200vh; overflow-y: scroll">
                  <span style="font-size:3vh;">Imóveis associados <i class="fas fa-plus" id="add_imovel"></i></span>
                  <li class="list-group-item" id="add_imovel_card" style="width:36vh;margin-bottom:2vh;">
                    <div class="form-group row" style="margin:0">
                      <select class="browser-default custom-select col-10" onfocus="this.size=5;" onblur="this.size=1;"
                        onchange="this.size=1; this.blur();" id="imovelassoc" name="imovelassoc" size="1">
                        <option disabled="" selected="" value=""> </option>
                        <?php
                                foreach($all_imoveis as $umimovel) {
                                  echo '<option class="opt" value="' . $umimovel['ID'] . '">';
                                  echo $umimovel['title'];
                                  echo '</option>';
                                }
                                ?>
                      </select>
                      <div class="col-2" id="savecol">
                        <div class="font-weight-bold text-uppercase mb-1" style="color:gray;">
                          <i class="fas fa-check-circle" id="savenewimovelassoc" style="height:1vh; font-size:3.2vh"></i>
                        </div>
                      </div>
                    </div>
                  </li>
                  <?php
                              if(!$imoveis) {
                                echo '<p style="padding-left:1.5vh">Este cliente não tem nenhum imóvel associado.</p>';
                              }
                              foreach($imoveis as $imovel) {
                                $photos=$conn->query("SELECT * FROM `imoveis_fotos` WHERE idimovel=".$imovel['ID'])->fetch_assoc();
                                if($photos) {
                                  $src = " height:15vh; background-image:url('../../img/uploads/imoveis/" . $photos['urlphoto'] . "'); margin-bottom:1vh;";
                                }
                                else
                                  $src ="";
                                echo '
                                <div class="col-sm-10" style="margin-top:1vh;">
                                  <div class="card">
                                    <div class="card-body">
                                      <div style="width:100%; '.$src.' background-size:cover;">
                                      </div>
                                      <h5 class="card-title">'.$imovel['title'].'</h5>
                                      <a href="../imovel/imovel.php?id='.$imovel['ID'].'" class="btn btn-primary">Ver detalhes</a>
                                      <a class="btn btn-danger deletenewimovelassoc" data-imo="'.$imovel['ID'].'"><i class="fas fa-trash" style="color:white"></i></a>
                                    </div>
                                  </div>
                                </div>';
                              }
                  ?>
                </div>


                <div class="col">
                  <span style="font-size:3vh;">Entidades Associadas</span>
                  <i class="fa fa-plus" id="add_contact"></i>
                  <div class="card" style="width: 16vw; margin-top:1vh;">
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
                        <i class="fas fa-times delentidade" data-ide="'. $entidade['id'].'" style="float:right"></i>
                      </li>';
                    }
                    ?>
                      <li class="list-group-item" id="add_li" style="display: none;">
                        <div class="form-group row">
                          <select class="browser-default custom-select col-10" onfocus='this.size=5;'
                            onblur='this.size=1;' onchange='this.size=1; this.blur();' id="domentidadeid"
                            name="domentidadeid">
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
                              <i class="fas fa-check-circle" id="savenewentity" style="height:1vh; font-size:3.2vh"></i>
                            </div>
                          </div>

                        </div>
                      </li>
                    </ul>
                  </div>
                  <!--- // -------------------------------------------------------------------- // --->


                  <br><br>


                </div>
              </div>
              <!------------------------------------------------>

            </div>

        <!-- /.container-fluid -->
      </div>
      <!--//footer after container fluid closing--->

      <?php include '../partials/footer.php'; ?>

      <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
    <?php include '../partials/modalandscroll.php'; ?>


</body>

<script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  $(document).ready(function(){

    $('#history').DataTable({
        "scrollY": "700px",
        "scrollCollapse": true,
        "paging": false,
        "bInfo" : false
    });

    $('#delbtn').click(function(){
				swal({
				  title: "Tem a certeza?",
				  text: "Depois de apagado, não será possível recuperar este contato!",
				  icon: "warning",
				  buttons: ["Cancelar", "Apagar"],
				  dangerMode: true,
				}).then((willDelete) => {
				  if (willDelete) {

					var theid = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; }?>;

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
						  text: "Contato apagado com sucesso! " + data,
						  icon: "success",
						}).then(function () {
						  location.replace('reports&taskspage.php');
						});
					  },
					  error: function (jqXhr, textStatus, errorMessage) {
						alert('Error: ' + errorMessage);
					  }
					});
				  }
				});
			});

			$('#submitbtn').hide();
			$('#delbtn').hide();

			$('#editbtn').click(function(){
				if($('#submitbtn').is(":hidden")) {
					$('#submitbtn').show();
					$('#delbtn').show();
					$(this).text('Cancelar');
				}
				else{
					$('#submitbtn').hide();
					$('#delbtn').hide();
					$(this).text('Editar');
				}
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
                }).then(
                function() {
                  location.reload();
                })
            }
            else {
              swal({
                  text: "Terminado!",
                  icon: "success",
                }).then(
                function() {
                  location.reload();
                })
            }

          },
          error: function (jqXhr, textStatus, errorThrown) {
            alert(errorThrown);
          }
        });
        $(this).closest('tr').toggleClass('to_complete');
      });


			$('.contato_click').dblclick(function(){
				window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
			});

      $('#add_imovel_card').hide();
      $('#add_imovel').click(function() {
        if ($(this).hasClass('fa-plus')) {
            $('#add_imovel_card').show();
            $(this).removeClass('fa-plus');
            $(this).addClass('fa-minus');
        } else {
            $('#add_imovel_card').hide();
            $(this).addClass('fa-plus');
            $(this).removeClass('fa-minus');
        }
      });

      $('#imovelassoc').change(function() {
          var saveit = $('#savenewimovelassoc');
          saveit.css('color', 'green');
          saveit.css('cursor', 'pointer');
      });

      $('#savenewimovelassoc').click(function() {
        var sel = $("#imovelassoc option:selected").val();
        var ang = <?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>;
        if (sel != null) {
          if ($(this).css('color') === 'rgb(0, 128, 0)') {
            $.ajax({
                url: "_controlador_angariacao.php",
                method: 'POST',
                async: false,
                data: {
                    'action_type': 10,
                    'id_imovel': sel,
                    'id':ang
                },
                success: function(result) {
                  if(result==1) {
                    swal({
                      text: "Imóvel adicionado!",
                      icon: "success",
                    }).then( function() { location.reload(); });
                  }
                  else {
                    swal({
                      text: "Tem que descrever e salvar o contato antes de adicionar entidades ou imoveis.",
                      icon: "warning",
                    })
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
          }
        }
      });

    $('.deletenewimovelassoc').click(function() {
        var sel = $(this).data('imo');
        var ang = <?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>;
        if (sel != null) {
          $.ajax({
            url: "_controlador_angariacao.php",
            method: 'POST',
            data: {
              'action_type': 11,
              'id_imovel': sel,
              'id': ang
            },
            success: function(result) {
              if(result==1) {
                  swal({
                    text: "Imóvel removido!",
                    icon: "success",
                  }).then( function() { location.reload(); });
                }
                else {
                  swal({
                    text: "Ocorreu um erro.",
                    icon: "warning",
                  })
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);
            }
          });
        }
    });






    //-------------------js adicionar entidade-------------------//

    $('#add_contact').click(function () {
      if ($(this).hasClass('fa-plus')) {
        $('#add_li').show();
        $(this).removeClass('fa-plus');
        $(this).addClass('fa-minus');
      } else {
        $('#add_li').hide();
        $(this).addClass('fa-plus');
        $(this).removeClass('fa-minus');
      }
    });

    $('.delentidade').click(function () {
      var sel = $(this).data('ide');
      $.ajax({
        url: "_controlador_angariacao.php",
        method: 'POST',
        async: false,
        data: {
          'action_type' : 31,
          'id_entidade': sel,
          'id': <?php echo $id;?>
        },
        success: function (result) {
          swal({
              title: "Relação apagada.",
              icon: "warning"
            }).then(
            function() {
              location.reload();
            })
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
    });

    $('#add_li').hide();

    $('#domentidadeid').change(function () {
      var saveit = $('#savenewentity');
      saveit.css('color', 'green');
      saveit.css('cursor', 'pointer');
    });

    $('#savenewentity').click(function () {
      var sel = $("#domentidadeid option:selected").val();
      if ($(this).css('color') === 'rgb(0, 128, 0)') {
        $.ajax({
          url: "_controlador_angariacao.php",
          method: 'POST',
          async: false,
          data: {
            'action_type' : 30, //relate imovel and entity
            'id_entidade': sel,
            'id': <?php echo $id;?>
          },
          success: function (result) {
            swal({
              title: "Nova entidade associada!",
              icon: "success"
            }).then(
              function() {
                location.reload();
              })
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
        });
      }
    });



     //-------------------js adicionar ficheiro-------------------//

     $('#inputGroupFile02').on('change',function(){
    //get the file name
    var fileName = $(this).val();
    var cutoff;

    if(fileName.length<40) {
      cutoff = fileName.length;
    }
    else {
      cutoff = 40;
    }

    var fileNameOnly = fileName.substring(12, cutoff);
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileNameOnly);
  });

    $('.deletefile').click(function(){
    var thefileid = $(this).data('fileid');
    swal({
      title: "Tem a certeza?",
      text: "Depois de apagado, não será possível recuperar este ficheiro!",
      icon: "warning",
      buttons: ["Cancelar", "Apagar"],
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          type: "POST",
          url: "_delete_subject_file.php",
          data: {
            id_delete: thefileid
          },
          cache: false,
          success: function (data) {
            swal({
              text: "Registo apagado com sucesso! ",
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

      $('#input_card_group').hide();

      $('#addfilebutton').click(function(){
        if($('#input_card_group').is(":visible")) {
          $('#input_card_group').slideUp();
          $('#addfile').addClass('fa-plus');
          $('#addfile').removeClass('fa-minus');
        }
        else {
          $('#input_card_group').slideDown();
          $('#addfile').removeClass('fa-plus');
          $('#addfile').addClass('fa-minus');
        }
      });

      $('#upload_file').hide();

      function able_file(){
        //alert('comparing "' + $('#btn_tipo').text().trim() + '" and  "Tipo de ficheiro"');
        //alert('comparing "' + $('#filenamedisplay').text() + '" and  "Ficheiro"');
        if($('#filenamedisplay').text() != 'Ficheiro' && $('#btn_tipo').text().trim() != "Tipo de ficheiro") {
          $('#upload_file').show();
          $('#upload_file').css('background-color', 'green');
          $('#upload_file').css('cursor', 'pointer');
          $('#addfilebutton').hide();
        }
      }




      $( "#ifile" ).change(function() {
        var nome = $(this).val().split('\\').pop().substr(0,15);
        $('#filenamedisplay').text(nome);
        able_file();
      });

      $('#upload_file').click(function () {
        if($(this).css('color')==='rgb(0, 128, 0)') {
          $('#file_form').submit();
        }
      });


      //IF GET['idimovel] THEN AUTOMATICALLY CREATE THE HOUSE RAISING
      // that is done in jquery

      var automatic = <?php echo (isset($_GET['idimovel'])) ? $_GET['idimovel'] : 0; ?>;
      if(automatic!=0) {
        $.ajax({
          url: '_automatic.php',
          type: 'post',
          async: false,
          data: {
            'action_type' : 90,
            'idimovel': automatic
          },
          success: function (data, textStatus, jQxhr) {
            location.replace('angariacao.php?id=' + data);
          },
          error: function (jqXhr, textStatus, errorThrown) {
            alert(errorThrown);
          }
        });
      }
  });
</script>

</html>
