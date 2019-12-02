<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';


$isadmin = $conn->query('SELECT admin FROM users WHERE id =' . $_SESSION['login'])->fetch_assoc()['admin'];
if($isadmin==1)
	$query = 'SELECT * FROM entidades';
elseif($isadmin==2)
	$query = 'SELECT * FROM entidades WHERE ( user = '. $_SESSION['login'] .' AND id NOT IN (SELECT entidade FROM entidades_users WHERE user <> '. $_SESSION['login'] .')) OR id IN (SELECT entidade FROM entidades_users WHERE user = '. $_SESSION['login'] .')';
else 
	$query = 'SELECT * FROM entidades WHERE id IN (SELECT entidade FROM entidades_users WHERE user='. $_SESSION['login'] .')';

$listaentidades  = ($result = $conn->query($query)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
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
		<link rel="stylesheet" href="../../vendor/tagify-master/src/tagify.css">

		<script src="../../vendor/jquery/jquery.min.js"></script>
		<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="../../js/sb-admin-2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
		<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
		<script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
		
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

			#ativo_tab {
				border-top: 1px solid #e1e1e1;
				border-radius: 10px;
				padding-top: 11px;
				text-align: center;
				background-color: #fff;
			}

			#contato_tab {

			}


			/* autocomplete tagsinput*/
			.label-info {
				background-color: #5bc0de;
				display: inline-block;
				padding: 0.2em 0.6em 0.3em;
				font-size: 75%;
				font-weight: 700;
				line-height: 1;
				color: #fff;
				text-align: center;
				white-space: nowrap;
				vertical-align: baseline;
				border-radius: 0.25em;
			}
			
			#actual_list li:hover {
				background-color: #F5F5F5;
				cursor:pointer;
			}
			
			#actual_list {
				position: absolute; top:3px; left:0; z-index:100;"
			}
			
			.closetag:hover {
				cursor:pointer;
				color:#f3f3f3;
			}
			.badge {
				font-size:88%
			}
			
			#listaentidadestable td, th {
				text-align:left;
			}
		</style>
		
		<script>
			$(document).ready(function(){
				

				
				
				//_______ DECLARE DATATABLE AND FILTERING FUNCTION______//
				var listaentidades_dt = $('#listaentidadestable').DataTable({
					"scrollY": "700px",
					"scrollCollapse": true,
					"recordsDisplay" : true,
					"paging": false,
					"bInfo": false
				});
				var column_to_search = listaentidades_dt.columns(1);
				column_to_search.search('').draw();
				
				$('#span_registos').text(listaentidades_dt.page.info().recordsTotal + ' registos.');
				
				function filtering_tags() {
					if ($('#tagspace').is(':empty')) {
						column_to_search.search("").draw();
					}
					else {
						var valuesss = [];
						$('#tagspace .badge').each(function(i){
							valuesss.push(this.innerHTML.replace('<span class="closetag">x</span>', ''));
						});
						array = (valuesss + "").replace(/,/g, '');
						if(array.length>1) {
							if ( column_to_search.search() !== array ) {
								column_to_search.search( array ).draw();
							}
						}
						else {
							column_to_search.search("").draw();
						}
					}
						
					$('#span_registos').text('A mostrar ' + listaentidades_dt.page.info().recordsDisplay + ' de ' + listaentidades_dt.page.info().recordsTotal + ' registos.');
					
				}
				
				
				
				
				
				
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
					}
				});
				
				
				
				//_______ CLOSER OF TAGS ______//
				$('.closetag').on("click", function(){
					$(this).parent().remove();
					filtering_tags();
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
							$('#actual_list').empty();
							$('#tag1').val('');
							$('.closetag').on("click", function(){
								$(this).parent().remove();
								filtering_tags();
							});
							filtering_tags();
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
			<div id="content-wrapper" class="d-flex flex-column">
				<!-- Main Content -->
				<div id="content">
					<?php include '../partials/topbar.php'; ?>
					<!-- Begin Page Content -->
					<!-- Page Heading -->

					<h1 class="h3 mb-4 text-gray-800" style="margin-left:2vw;">Entidades</h1>


					<div class="row" style="margin-left:50px">
						<div class="col-9">
							<div class="form-group" style="margin-bottom:0;">
								<label>Pesquisa por Tags</label>
								<div class="form-control row" style="display:flex;">
									<div id="tagspace" style="float:left;">
									</div>
									<div id="writespace" class="col" style="max-width:80%;">
										<input type="text" id="tag1" style="border:none;width:100%;" />
									</div>
								</div>
							</div>
							<div class="tt-menu" style="position: relative; top: 0; left: 0; height:1px;">
								<ul id="actual_list" class="list-group">

								</ul>
							</div>
						</div>
						<div class="col-3" style="padding-top:20px">
							Registos:<br>
							<span id="span_registos"></span>
						</div>
					</div>
					<br>

					<div class="tab-content" id="myTabContent" style="margin-left:50px;margin-right:50px;">
						<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
							<div class="card shadow">
								<div class="form-group">
									<div class="card-body">
										<table id="listaentidadestable">
											<thead>
												<th style="width:30%">Nome</th>
												<th style="width:30%">Classificadores</th>
												<th style="width:10%">Telemóvel</th>
												<th style="width:25%">Email</th>
												<th style="width:5%" hidden></th>
											</thead>
											<tbody>
												<?php
												foreach($listaentidades as $ent) {

													echo '<tr class="a_contact" data-theid="' . $ent['id'] . '">';
													echo '<td style="width:30%">'. $ent['nome'] . " " . $ent['apelido'] . "</td>";
													
													echo '<td style="width:30%">';
													
														$result = $conn->query('SELECT * FROM classificadores WHERE id IN (SELECT classificador FROM entidades_tags WHERE entidade =' .
																			  $ent['id'] . ')');
														$tags_list = $result->fetch_all(MYSQLI_ASSOC);
														$iter = 0;
														$max_iter = $result->num_rows;
														foreach($tags_list as $item) {
															echo '<span class="badge" style="background-color:'.$item['cor'].';margin-right:5px" data-id="'.$item['id'].'">'.$item['nome'].'</span>';
														}
															
													echo "</td>";
													echo '<td style="width:10%">'.$ent['telemovel']."</td>";
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
