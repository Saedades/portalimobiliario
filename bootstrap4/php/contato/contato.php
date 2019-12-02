<?php
include '_contato.php';
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
    <link href="../../css/subjects.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />


    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>

    <style>

		.report_click:hover {
			background-color: white !important;
			cursor: pointer;
		}

    .datetimebox {
        width:500px;
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

    .bootstrap-datetimepicker-widget {
      cursor:pointer;
      text-align:center;
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
                <div class="container-fluid row" >
                    <form class="col-5 border-left-primary m-lg-3" action="_controlador_contato.php" method="POST" style="min-width:220px; max-width: 30vw;">
                        <h1 class="h3 mb-4 text-gray-800"><?php echo $title_top; ?></h1>
                        <input type="text" id="domcontato" name="domcontato" value="<?php if(isset($contato_id)) { echo $contato_id; }?>" hidden>
                        <input type="text" id="domuserid" name="domuserid" value="<?php echo $_SESSION['login']; ?>" hidden>
                        <input type="text" id="domseguimento" name="domseguimento" value="<?php echo $seguimento; ?>" hidden>



                        <div class="form-group" required>
                            <label for="domtitle">Título</label>
                            <input type="text" class="form-control" id="domtitle" name="domtitle" value="<?php echo $title;?>" required>
                        </div>



                        <div style="position:relative;">
                            <div class="form-group">
                                <div class="input-group" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="domdate" />
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="domdescr">Descrição</label>
                            <textarea type="text" class="form-control" id="domdescr" name="domdescr"> <?php echo $descricao;?> </textarea>
                        </div>


                        <br>
                        <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd">Salvar</button>
                        <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer">Apagar</div>
                        <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer">Editar</div>


                    </form>

                    <!------------------------------------------------>
                    <div class="col-6 border-left-primary m-lg-3" style="min-width:220px; max-width: 50vw;">

                        <a href="../contato/contato.php<?php 
                         
                         /*if($contato_id!=0) 
                          { 
                            echo '?with=' . $contato_id; 
                          } 
                          elseif($contato_id!=0) 
                          { 
                            echo '?who=' . $contato_id; 
                          } */
                          if($contato_id!=0) {
                            echo '?seg=' . $seguimento;
                          }
                          ?>" style="" class="btn btn-primary btn-icon-split col-3">
                              <span class="icon text-white-50" style="position:absolute; left:0;"> <i class="fas fa-plus"></i> </span>
                              <span class="text" style="padding-left: 2vw;">Contato</span>
                        </a>


                        <table id="history_contact">
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
                                  //include '../partials/timewarning.php';
                                    echo '<tr>';
                                        echo "<td>" . $contato['agendado'] . "</td>";
                                        echo "<td>" . $contato['titulo'] . "</td>";
                                        echo "<td>";
                                            $entidades_assoc = ($result = $conn->query("SELECT CONCAT(nome, apelido) as nometotal FROM entidades WHERE id IN (SELECT entidade FROM contatos_entidades WHERE contato = " . $contato['id'] . " LIMIT 2)")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
                                            foreach($entidades_assoc as $item) {
                                              echo $item['nometotal'] . '; ';
                                            }
                                        echo "</td>";
                                        echo "<td>" . $contato['estado'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>

                        <br>



                        <div class="row" hidden>
                            <div class="col" style="overflow-y: scroll; min-height:52vh; max-height:80vh;">
                                <span style="font-size:3vh;">Imóveis associados <i class="fa fa-plus" id="add_imovel"></i></span>

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
                                    else {
                                    $src ="";
                                        echo '
                                        <div class="col-sm-10" style="margin-top:1vh;">
                                          <div class="card">
                                            <div class="card-body">
                                              <div style="width:100%; '.$src.'" background-size:cover;">
                                              </div>
                                              <h5 class="card-title">'.$imovel['title'].'</h5>
                                              <a href="../imovel/imovel.php?id='.$imovel['ID'].'" class="btn btn-primary">Ver detalhes</a>
                                              <a class="btn btn-danger deletenewimovelassoc" data-imo="'.$imovel['ID'].'"><i class="fas fa-trash" style="color:white"></i></a>
                                            </div>
                                          </div>
                                        </div>';
                                    }
                                  }
                                ?>
                            </div>
                        </div>



                          <!------ENTIDADES--------->
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
                                          <a href="../entidade/entidade?id='. $entidade['id'] .'">' . $entidade['nome'] . '</a>
                                          <i class="fas fa-times delentidade" data-ide="'. $entidade['id'].'" style="float:right"></i>
                                        </li>';
                                      }
                                      ?>
                                      <li class="list-group-item" id="add_li" style="display: none;">
                                          <div class="form-group row">
                                              <select class="browser-default custom-select col-10" onfocus='this.size=5;' onblur='this.size=1;'
                                                onchange='this.size=1; this.blur();' id="domentidadeid" name="domentidadeid">
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


                            <br><br>


                          </div>
                        
                    </div>
                
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include_once '../partials/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include '../partials/modalandscroll.php' ?>

    <?php include '_contato_js.php'; ?>

</body>
</html>
