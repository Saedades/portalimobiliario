<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
include '_entidade.php';


$tags = $conn->query('SELECT * FROM classificadores WHERE id IN (SELECT classificador FROM entidades_tags WHERE entidade = ' . (isset($_GET['id'])? $_GET['id'] : 0) . ')')->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>RG Consultores</title>

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="sweetalert2.min.css">

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

	<script>
		$(document).ready(function(){
			$('#addnote_vc').click(function(){
				var inputnote = $('#newnote_vc').val();
				var ident = <?php echo (isset($_GET['id']) ? $_GET['id'] : 0); ?>;

				$.ajax({
					async: false,
					dataType: "json",
					type: "POST",
					url: "_entidade_modal.php",
					data: {
						entity: ident,
						descricao: inputnote,
						action: 4
					},
					cache: false,
					success: function (data) {
						note_info = JSON.parse(JSON.stringify(data));
					},
					error: function (jqXhr, textStatus, errorMessage) {
						alert('Error: ' + errorMessage);
					}
				});
				$('#inputdiv_newnote_vc').after('<li class="list-group-item entity_notes_li" data-noteid="' + note_info['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + note_info['criado'] + '</p>' + note_info['descricao'] + '</li>');
				$('#newnote_vc').val('');

			});
		});
	</script>
	
  <style>

      .select2-selection__rendered {
          line-height: 38px !important;
          /*border: 1px solid #d1d3e2;*/
      }
      .select2-container--default .select2-selection--single {
          height: 38px !important;
          border: 1px solid #d1d3e2;
          border-radius: .35rem;
      }
      .select2-selection__arrow {
          height: 36px !important;
          border: 1px solid #d1d3e2!important;
          border-radius: .35rem;
      }

      .dataTables_filter input { width: 300px }
      .dataTables_filter label { font-size: small }

      #conttable tr:hover {
          background-color:#f4f4ff;
          cursor:pointer;
      }

      .greentask {
      background-color:#e7f2e8;
    }

    .yellowtask {
      background-color:#fffeeb;
    }

    .redtask {
      background-color:#ffe3e3;
    }

    .btn-nao-efetuado {
      background-color:#ffe3e3;
    }

    .btn-efetuado {
      background-color:#e7f2e8;
    }
	  
	  .row {
		  flex-wrap: nowrap;
	  }
	  
	  .del_people:hover {
		  cursor:pointer;
		  color:darkred;
	  }
	  
	  a {
	  	cursor:pointer!important;
	  }
  </style>
	
	<script>
		$(document).ready(function(){			
			
			//_______ GET ALL OPTIONS ______//
			var options;
			$.ajax({ 
				type: 'POST', 
				url: 'tags_functions.php', 
				data: { 
					action: 1
				}, 
				dataType: 'json',
				success: function (data) { 
					options = data;
				},
				error: function (request, status, error) {
					alert(status.toSource() + error);
				}
			});
			
			
			//_____ AJAX SAVE TAGS _____//
			function save_tag() {
				var tagarray = [];
				$('#tagspace .badge').each(function(i) {
					tagarray.push($(this).data('id'));
				});
				$.ajax({ 
					type: 'POST', 
					dataType: 'json',
					url: 'tags_functions.php', 
					data: { 
						action: 2,
						entidade: <?php echo (isset($_GET['id']) ? $_GET['id'] : 0); ?>,
						tags: tagarray
					}, 
					dataType: 'json',
					success: function (data) { 
						//alert(data);
					}
				});
			}
			


			//_______ CLOSER OF TAGS ______//
			$('.closetag').on("click", function(){
				$(this).parent().remove();
				save_tag();
			});



			//_______ UPON WRITING TAGS ______//
			
			
			$('#tag1').on('keyup', function(){
				var written = $('#tag1').val();
				var i;
				if(written.length>0) {
					$('#actual_list').empty();
					for (i = 0; i < options.length; ++i) {
							var oneoptionname = options[i]['nome'];
							var oneoptionid = options[i]['id'];
							var oneoptioncor = options[i]['cor'];
							if(oneoptionname.toLowerCase().indexOf(written.toLowerCase()) == 0) {
								$('#actual_list').append('<li data-id="'+oneoptionid+'" data-cor="'+oneoptioncor+'" class="list-group-item p-2">'+oneoptionname+'</li>');
							}
						}
						$('#actual_list li').click(function(e){
							var selected_option = e.target.getAttribute('data-id');
							var selected_option_name = e.target.textContent;
							var selected_option_cor = e.target.getAttribute('data-cor');

						$('#tagspace').append('<span class="badge" style="background-color:'+selected_option_cor+'" data-id="'+selected_option+'">'+selected_option_name+' <span class="closetag">x</span></span>');
						save_tag();
						$('#actual_list').empty();
						$('#tag1').val('');
						$('.closetag').on("click", function(){
							$(this).parent().remove();
							save_tag();
						});
					});
				}
				else {
					$('#actual_list').empty();
				}
			});
			
		});
	</script>

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

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?php echo $title?></h1>


          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="detalhes-tab" data-toggle="tab" href="#detalhes" role="tab" aria-controls="detalhes" aria-selected="true">Detalhes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="relacoes-tab" data-toggle="tab" href="#relacoes" role="tab" aria-controls="relacoes" aria-selected="false" <?php echo ($identidade==0) ? 'hidden' : ''?>>Relações</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="moradatab-tab" data-toggle="tab" href="#moradatab" role="tab" aria-controls="moradatab" aria-selected="false" <?php echo ($identidade==0) ? 'hidden' : ''?>>Morada</a>
            </li>
          </ul>


          <div class="tab-content" id="myTabContent">


            <!--------------------------------------TAB DETALHES--------------------------------------------->
            <div class="tab-pane fade show active" id="detalhes" role="tabpanel" aria-labelledby="detalhes-tab">

              <form class="border-left-primary" action="_controlador_entidade.php" method="POST" style="width:100%;padding-left:1vw">

                <div style="float:left; padding-top:20px; min-width:50%">

                  <input type="text" name="action_type" value="3" hidden>


                  <div class="form-group" hidden>
                      <label id="domid" for="domid">ID</label>
                      <input type="text" class="form-control" id="domid" name="domid" value="<?php echo $identidade ?>" style="width:5vw;" readonly>
                  </div>


					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-sm-12">
								<label id="domnomelabel" for="domname">Nome</label>
								<input type="text" class="form-control" id="domname" name="domname" value="<?php echo $domname; ?>" >
							</div>
							<div class="col-lg-4 col-sm-12">
								<label id="domapelabel" for="domape">Apelido</label>
								<input type="text" class="form-control" id="domape" name="domape" value="<?php echo $domape; ?>" >
							</div>
							<div class="col-lg-4 col-sm-12">
								<label id="domnickabel" for="domnick">Nickname</label>
								<input type="text" class="form-control" id="domnick" name="domnick" value="<?php echo $domnick ?>" />
							</div>
						</div>
					</div>


					<div class="form-group">
						<div class="row">
							
							<div class="col-lg-4 col-sm-12">
								<label id="domemaillabel" for="domemail">Email</label>
								<input type="text" class="form-control" id="domemail" name="domemail" value="<?php echo $domemail ?>" />
							</div>
							<div class="col-lg-4 col-sm-12">
								<label id="domtelelabel" for="domtele">Telemóvel</label>
								<input type="text" class="form-control" id="domtele" name="domtele" value="<?php echo $domtele ?>" />
							</div>
							
							<div class="col-lg-4 col-sm-12">
								<div class="form-group" style="margin-bottom:0;margin-left:12px;" <?php echo (isset($_GET['id']) ? '' : 'hidden'); ?>>
									<label>Classificadores</label>
									<div class="form-control row" style="display:flex;">
										<div id="writespace" class="col" style="max-width:80%;">
											<input type="text" id="tag1" style="border:none;width:100%;" />
										</div>
									</div>
									
								</div>
								<div class="tt-menu" style="position: relative; top: 0; left: 0; height:1px; z-index:100">
									<ul id="actual_list" class="list-group" style="display: block">
									</ul>
								</div>
								<div id="tagspace" style="position: absolute;z-index: 99;top: 8vh;font-size:120%!important">
									<?php 
										foreach($tags as $item) {
											echo '<span class="badge" style="background-color:'.$item['cor'].'" data-id="'.$item['id'].'">'.$item['nome'].' <span class="closetag">x</span></span>';
										}
									?>
								</div>
							</div>
							
						</div>
					</div>


                  <div class="form-group" >
                    <div class="row">
						<div class="col-lg-4 col-sm-12" hidden>
							<label id="domratinglabel" for="domrating">Rating</label>
							<select class="form-control" id="domrating" name="domrating">
								<option selected disabled>Escolha...</option>
								<?php
								foreach($listaratings as $rating) {
									echo '<option style="font-size:1.5vh" value="' . $rating['id'] . '" ';
									if($rating['id']==$rating_selected) {
										echo "selected";
									}
									echo '>' . $rating['nome'] . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-lg-4 col-sm-12" hidden>
							<label id="domcategorialabel" for="domcategoria">Categoria</label>
							<select class="form-control" id="domcategoria" name="domcategoria">
								<option selected disabled>Escolha...</option>
								<?php
								foreach($listacategoria as $categoria) {
									echo '<option style="font-size:1.5vh" value="' . $categoria['id'] . '" ';
									if($categoria['id']==$categoria_selected) {
										echo "selected";
									}
									echo '>' . $categoria['nome'] . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-lg-4 col-sm-12">
							<label id="domvalidlabel" for="domfilhos">Validade da identificação</label>
							<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
								<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" id="domvalid" name="domvalid" value="<?php echo $domvalid;?>"/>
								<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-12">
							<label id="domidentlabel" for="domident">Identificação</label>
							<input type="text" class="form-control" id="domident" name="domident" value="<?php echo $domident; ?>"/>
						</div>
					  </div>
                  </div>


					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-sm-12">
								<label id="domgenlabel" for="domgen">Género</label>
								<select class="form-control" id="domgen" name="domgen">
									<option selected disabled>Escolha...</option>
									<?php
									foreach($listageneros as $genero) {
										echo '<option style="font-size:1.5vh" value="' . $genero['id'] . '" ';
										if($genero['id']==$genero_selected) {
											echo "selected";
										}
										echo '>' . $genero['nome'] . '</option>';
									}
									?>
								</select>
							</div>
							<div class="col-lg-4 col-sm-12">
								<label id="domnasclabel" for="domnasc">Data de nascimento</label>
								<div class="input-group date" id="domnascpicker" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#domnascpicker" id="domnasc" name="domnasc" value="<?php echo $domnasc; ?>"/>
									<div class="input-group-append" data-target="#domnascpicker" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-sm-12" hidden>
								<label id="domlead" for="domlead">Estado de lead</label>
								<select class="form-control" id="domlead" name="domlead">
									<option selected disabled>Escolha...</option>
									<?php
										foreach($listaleads as $lead) {
											echo '<option style="font-size:1.5vh" value="' . $lead['id'] . '" ';
											if($lead['id']==$lead_selected) {
												echo "selected";
											}
											echo '>' . $lead['nome'] . '</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>


					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-sm-12">
								<label id="domfilhoslabel" for="domfilhos">Nº Filhos</label>
								<input type="number" class="form-control" id="domfilhos" name="domfilhos" value="<?php echo $domfilhos ?>" />
							</div>
							<div class="col-lg-4 col-sm-12">
								<label id="domniflabel" for="domape">NIF</label>
								<input type="text" class="form-control" id="domnif" name="domnif" value="<?php echo $domnif ?>" />
							</div>
						</div>
					</div>

                  <div>
                    <?php if($domapagado==1) echo "<p>Esta entidade requisitou o direito ao esquecimento e todas as tuas informações foram apagadas sem recuperação. O seu ID é mantido por questões de integridade mas não corrompe a anonimidade.</p>";?>
                    <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd" <?php if($domapagado==1) echo "hidden";?>>Salvar</button>
                    <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer;" <?php if($domapagado==1 || $isadmin!=1) echo "hidden";?>>Apagar</div>
                    <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer;" <?php if($domapagado==1) echo "hidden";?>>Editar</div>
                    <div id="forgetbtn" class="btn btn-danger" name="forgetbtn" style="cursor:pointer;" <?php if($domapagado==1) echo "hidden";?>>Esquecer Permanentemente</div>
                  </div>
                </div>
              </form>
				
				
				<div class="form-group" style="float:right; position:absolute; top:22vh; right:2vw; width: 28vw; height:22%;" <?php echo (isset($_GET['id']) ? '' : 'hidden'); ?>>
					<h5 style="margin:5px; float:left">Seguimentos</h5>
					<div style="line-height:2vh; font-size:small; float:right" class="btn btn-primary" id="novoseg"> + Novo seguimento</div>
					<br><br>

					<table id="conttable" style="font-size:small">
						<thead>
							<tr>
								<th>Título</th>
								<th>Data</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$result = $conn->query('SELECT * FROM contatos WHERE entidade = ' . (isset($_GET['id']) ? $_GET['id'] : 0) . ' ORDER BY estado, agendado ASC')->fetch_all(MYSQLI_ASSOC);
							//var_dump($result);
							$i=0;
							foreach($result as $item) {
								echo '<tr ';
								echo (($item['estado'] == 1) ? ' data-estado="1" ' : ' data-estado="0" ');
								echo ' data-order="' . $i . '"';
								$i++;
								echo 'data-id="' . $item['id'] . '">';
									echo '<td>';
									echo $item['titulo'];
									echo '</td>';
									echo '<td>';
									$date_temp = date_create($item['agendado']);
									echo date_format($date_temp,"Y-m-d") . ' ';
									echo (($item['tipohora']==1) ? ' ' : date_format($date_temp,"H:i"));
									echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
				
				<div class="form-group" style="position:relative; float:right; width: 28vw; height:24%; top:31vh;" <?php echo (isset($_GET['id']) ? '' : 'hidden'); ?>>
					<h5>Notas</h5>
					<ul class="list-group" style="font-size:1.5vh;max-height:181px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;" id="input_div_entity_notes_vc">
						<div class="list-group-item" id="inputdiv_newnote_vc">
							<input id="newnote_vc" type="text" class="" style="width:95%;float: left;border:none"><i class="fas fa-check" id="addnote_vc"></i>
						</div>
						<?php 
						$id = isset($_GET['id']) ? $_GET['id'] : 0;
						$list = $conn->query('SELECT * FROM entidades_notas WHERE entidade=' . $id);
						foreach($list as $item) {
							echo '<li class="list-group-item entity_notes_li" data-noteid="' . $item['id'] . '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' . $item['criado'] . '</p>' . $item['descricao'] . '</li>';
						}
						?>
					</ul>
				</div>

				<div class="form-group" style="float:right; position:absolute; top:76vh; right:2vw; width: 28vw; height:24%;" <?php if(isset($_GET['id']) && $isadmin>=1) { echo ''; } else { echo 'hidden'; } ?>>
					<h4>Utilizadores</h4>
					<div class="form-group" id="associar_utilizador">
						<select class=" form-control js-example-basic-single" id="domutilizador" name="domutilizador">
							<option selected disabled>Escolha...</option>
							<?php
							foreach($listautilizadores as $utilizador) {
								echo '<option style="font-size:1.5vh" value="' . $utilizador['id'] . '">' . $utilizador['name'] . '</option>';
							}
							?>
						</select>
						<br><br>
						<ul class="list-group list-group-flush">
							<?php
							foreach($utilizadores_selected as $item) {
								echo '<li class="list-group-item" style="border:1px solid #caccd9; border-radius:8px">';
								echo '<div class="row">';
								echo '<div class="col-11"><a  href="#'.$item['id'].'">'.$item['name'].'</a></div>';
								echo '<div class="col-1">'.'<i data-iduser="'.$item['id'].'" class="fas fa-trash del_people"></i>'.'</div>';
								echo '</div>';
								echo '</li>';
							}
							?>
						</ul>
					</div>
				</div>
				

				
            </div>



            <!--------------------------------------TAB RELAÇÕES--------------------------------------------->
            <div class="tab-pane fade show" id="relacoes" role="tabpanel" aria-labelledby="relacoes-tab">

              <div class="row">

                <form id="famiglia" class="col-lg-6 col-sm-6 border-left-primary" style="height:60vh; padding:10px;" method="POST" action="relacoes.php">
                  <input name="entidadeA" value="<?php echo $identidade; ?>" hidden>
                  <h4>Familiares</h4>
                  <div class="form-group" id="associar_familiar">
                    <label class="form-check-label" for="domfamiglia" hidden>Associe a entidade ou então&nbsp;</label><a href="#" hidden>crie uma nova</a>
                    <select class=" form-control js-example-basic-single" id="domfamiglia" name="domfamiglia">
                      <option selected disabled>Escolha...</option>
                      <?php
                      foreach($listafamiliares as $familiar) {
                        echo '<option style="font-size:1.5vh" value="' . $familiar['id'] . '">' . $familiar['nome'] . ' ' . $familiar['apelido'] . '</option>';
                      }
                      ?>
                    </select>
                    <br><br>
                    <ul class="list-group list-group-flush">
                    <?php
                    foreach($familiares_selected as $item) {
                      echo '<li class="list-group-item" style="border:1px solid #caccd9; border-radius:8px">';
                        echo '<div class="row">';
                          echo '<div class="col-5"><a  href="#'.$item['idB'].'">'.$item['nomeB'].'</a></div>';
                          echo '<div class="col-5">'.$item['relacao'].'</div>';
                          echo '<div class="col-2">'.'<i data-idrel="'.$item['id'].'" class="fas fa-trash delidrel"></i>'.'</div>';
                        echo '</div>';
                      echo '</li>';
                    }
                    ?>
                    </ul>
                  </div>

                  <div class="modal hide fade" id="famiglia_modal" tabindex="-1" role="dialog" aria-labelledby="famiglia_modal" aria-hidden="true" >
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Escolha a relação</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <select class="form-control" id="domrelfam" name="domrelfam">
                              <?php
                              foreach($listarelacoes_famiglia as $item) {
                                echo '<option class="gen' . $item['genero'] . '" value="' . $item['id'] . '">';
                                echo  $item['nome'];
                                echo '</option>';
                              }
                              ?>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel_famiglia">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>

                <form id="societa" class="col-lg-6 col-sm-6 border-left-primary" style="height:20vh;  padding:10px;" method="POST" action="relacoes.php">
                  <input name="entidadeA" value="<?php echo $identidade; ?>" hidden>
                  <h4>Empresariais</h4>
                  <div class="form-group" id="associar_empresarial">
                    <label class="form-check-label" for="domsocieta" hidden>Associe a empresa ou então&nbsp;</label><a href="#" hidden>crie uma nova</a>
                    <select class=" form-control js-example-basic-single" id="domsocieta" name="domsocieta">
                      <option selected disabled>Escolha...</option>
                      <?php
                      foreach($listaempresas as $empresa) {
                        echo '<option style="font-size:1.5vh" value="' . $empresa['id'] . '">' . $empresa['designacao'] . '</option>';
                      }
                      ?>
                    </select>
                    <br><br>
                    <ul class="list-group list-group-flush">
                    <?php
                    foreach($empresas_selected as $item) {
                      echo '<li class="list-group-item" style="border:1px solid #caccd9; border-radius:8px">';
                        echo '<div class="row">';
                          echo '<div class="col-5"><a  href="#'.$item['id'].'">'.$item['nomeB'].'</a></div>';
                          echo '<div class="col-5">'.$item['relacao'].'</div>';
                          echo '<div class="col-2">'.'<i data-idrel="'.$item['id'].'" class="fas fa-trash delidrel"></i>'.'</div>';
                        echo '</div>';
                      echo '</li>';
                    }
                    ?>
                    </ul>
                  </div>

                  <div class="modal hide fade" id="societa_modal" tabindex="-1" role="dialog" aria-labelledby="societa_modal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Escolha a relação</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <select class="form-control" id="domrelsoc" name="domrelsoc">
                              <?php
                              foreach($listarelacoes_societa as $item) {
                                echo '<option value="' . $item['id'] . '">';
                                echo  $item['nome'];
                                echo '</option>';
                              }
                              ?>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel_societa">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>

                </form>

              </div>

            </div>




            <!----------------------------------------TAB MORADA--------------------------------------------->
            <div class="tab-pane fade show" id="moradatab" role="tabpanel" aria-labelledby="moradatab-tab">

              <form id="moradatab" class="border-left-primary row" style="height:60vh; padding:20px;" method="POST" action="morada.php">

                <input name="entidade"  value="<?php echo $identidade; ?>" hidden>

                <div class="col-6">

                  <div class="form-group">
                    <label id="dompaislabel" for="dompais">País</label>
                    <select class="form-control" id="dompais" name="dompais">
                      <option selected disabled>Escolha...</option>
                      <?php
                      foreach($listapaises as $item) {
                        echo '<option style="font-size:1.5vh" value="' . $item['paisId'] . '" ';
                        if($item['paisId']==$pais_selected) {
                          echo "selected";
                        }
                        echo '>' . $item['paisNome'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <div style="margin-top:1vh; margin-left: 1px;">
                      <label for="domlocal">Código Postal</label>
                      <label for="domlocal" style="float:right">Local</label>
                    </div>
                    <div class="row" style="margin-top:1vh; margin-left: 1px;">
                      <input class="form-control col-3" style="text-align: right;" id="domzip1" name="domzip1" type="text" maxlength="4" value="<?php echo $domzip1;?>">
                      <span style="font-size:3vh; margin-left:0.2vw;margin-right:0.2vw;"> - </span>
                      <input class="form-control col-2" id="domzip2" name="domzip2" type="text" maxlength="3" value="<?php echo $domzip2;?>">
                      <input class="form-control col" id="domlocal" name="domlocal" type="text" value="<?php echo $domlocal;?>" style="margin-left:2vw; margin-right:1vh">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="domaddr">Morada</label><a id="mapslink" target="_blank" style="float:right">
                    <i class="fas fa-map"></i><span style="margin-left:10px"> Google Maps </span></a>
                    <textarea type="text" class="form-control" id="domaddr" name="domaddr" value=""><?php echo $domaddr;?></textarea>
                  </div>

                </div>

                <div class="col">
                  <div class="form-group">
                    <div class="mapouter" style="margin:14px">
                      <div class="gmap_canvas"><iframe width="468" height="270" id="gmap_canvas"
                          src="https://maps.google.com/maps?q=&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"
                          scrolling="no" marginheight="0" marginwidth="0"></iframe>Google Maps Generator by <a
                          href="https://www.embedgooglemap.net">embedgooglemap.net</a>
                      </div>
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
                </div>

                <div style="width:100%; padding:20px;">
                  <button id="submitbtn_morada" type="submit" class="btn btn-dark" name="sbmtd_morada" >Salvar morada</button>
                </div>

              </form>

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

</body>
<!-- End of Page Wrapper -->


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
							
							<label id="entidade" hidden></label>
							<input id="segid" name="segid" value="" hidden />
							<label id="seguser" hidden> </label>

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


                        <div class="col" id="colnotes">
                            <div class="list-group">
                                <h5>Notas</h5>
                                <ul class="list-group" style="font-size:1.5vh;max-height:800px;overflow:scroll; -webkit-overflow-scrolling: touch; overflow-x:hidden;"
                                id="input_div_seg_notes_vc">
                                    <div class="list-group-item" id="seg_inputdiv_newnote_vc">
                                        <input id="seg_newnote_vc" type="text" class="" style="width:95%;float: left;border:none"><i class="fas fa-check" id="seg_addnote_vc"></i>
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


<!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->
<?php include '_entidade_js.php'; ?>
	<script>
		$('#entidade').text(<?php echo (isset($_GET['id']) ? $_GET['id'] : 0)?>);
		
	</script>

</html>
