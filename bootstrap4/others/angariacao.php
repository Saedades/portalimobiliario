<?php
require_once 'php/connectDB.php';
include_once 'php/validate_session.php';

if(isset($_GET['id'])) {
   	if(!($angariacao = $conn->query("SELECT * FROM angariacoes WHERE id = " . $_GET['id'])->fetch_assoc())) {
		echo "redirect";
	}
	$title_top = "Editar Angariação";
	
	$entidades = $conn->query('SELECT * FROM contatos WHERE idcontatos IN (SELECT ident FROM angariacoes_entidades WHERE idang = ' . $_GET['id'] . ")")->fetch_all(MYSQLI_ASSOC);
	if(!$entidades) $entidades = "";
	
	$imoveis = $conn->query('SELECT * FROM imoveis WHERE ID IN (SELECT idimovel FROM angariacoes_imoveis WHERE idangariacao = ' . $_GET['id'] . ")")->fetch_all(MYSQLI_ASSOC);
	if(!$imoveis) $imoveis = "";
	
	$title = $angariacao['titulo'];
	$data = $angariacao['data'];
		
}
else {
	$title_top = "Nova Angariação";
}

$all_entidades = $conn->query('SELECT * FROM contatos WHERE pertence_a =' . $_SESSION['login'])->fetch_all(MYSQLI_ASSOC);
$all_imoveis = $conn->query('SELECT * FROM imoveis WHERE iduser =' . $_SESSION['login'])->fetch_all(MYSQLI_ASSOC);


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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
	<link href="css/subjects.css" rel="stylesheet">

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
		
		#btnassinar:hover {
			background-color:green;		
			border-color:green;
			cursor:pointer;
		}

    </style>

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <?php include 'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include 'topbar.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid row" style="height:100vh;">
                <!-- Page Heading -->
                <form class="col-5 border-left-primary m-lg-3" action="php/_reports.php" method="POST" style="min-width:220px; max-width: 30vw;">
				<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-light btn-icon-split col-sm-3" >
					<span class="icon text-white-50" style="position:absolute; left:0;">
						<i class="fas fa-backward"></i>
					</span>
                    <span class="text" style="padding-left: 1vw;">Voltar</span>
                </a>
                <br><br>
                <div id="alert" class="alert alert-success alert-dismissible"
                    <?php if(!(isset($_GET['code']))) {  echo " hidden"; } ?>>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span id="changes" <?php if(isset($_GET['code']) AND $_GET['code']!=1) { echo " hidden"; } ?>>
						<strong>Sucesso!</strong> Alterações foram guardadas!
					</span>
                    <span id="newcont"  <?php if(isset($_GET['code']) AND $_GET['code']!=2) { echo " hidden"; } ?>>
						<strong>Sucesso!</strong> Novo Relatório Adicionado!
					</span>
                </div>
					
					
					
					
					
					
					<h1 class="h3 mb-4 text-gray-800"><?php echo $title_top; ?></h1>
                    <input type="text" id="angid" name="angid" value="<?php if(isset($report_id)) { echo $report_id; }?>" hidden>
                    <input type="text" id="userid" name="userid" value="<?php echo $_SESSION['login']; ?>" hidden>

                    <div class="form-group" required>
                        <label for="domtitle">Título</label>
                        <input type="text" class="form-control" id="domtitle" name="domtitle" value="<?php echo $title;?>" required>
                    </div>
					
					<br>
					<div style="width:5vw;margin:auto;">
						<span id="btnassinar" class="btn-circle btn-sm btn-dark" style="width:auto;margin:auto;"><i class="fas fa-check"></i></span> &nbsp;Assinar
					</div>
					
                    
					<div class="form-group" id ="assinar_group">
						<div class="row">
							<div class="col">
								<label for="domdate">Data</label>
								<input type="date" class="form-control" id="domdate" name="domdate" value="<?php echo $date;?>">
							</div>
						</div><br>
						<div id="snaptarget" class="ui-widget-header" style="height:15vh; width:100%;  border: 2px dashed;">
						</div>
					</div>
                    <br>
                    <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd">Salvar</button>
					<div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer">Apagar</div>
					<div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer">Editar</div>
                </form>
				
				
				<!------------------------------------------------>
				<div class="col-6 border-left-primary m-lg-3" style="min-width:220px; max-width: 50vw; padding-top:6.5vh; ">

					<span style="font-size:3vh;">Entidades associadas</span>
					<i class="fa fa-plus" id="add_contact"></i>
					<div class="card" style="width: 16vw; margin-top:1vh;">
						<div class="card-header">
							Nomes
						</div>
						<ul class="list-group list-group-flush">
							<?php
							foreach($entidades as $entidade) {
								echo '
									<li class="list-group-item drag_entity">
										<a href="entidade?id='. $entidade['idcontatos'] .'">' .  $entidade['nome'] .  '</a> 
										<i class="fas fa-times delcontato" data-idc="'. $entidade['idcontatos'].'" style="float:right"></i>
									</li>';
							}
							?>
							<li class="list-group-item" id="add_li" style="display: none;">
								<div class="form-group row">
									<select class="browser-default custom-select col-10" onfocus='this.size=5;'
											onblur='this.size=1;' onchange='this.size=1; this.blur();' id="domcontatoid"
											name="domcontatoid">
										<option disabled selected value> </option>
										<?php
										foreach($all_entidades as $umaentidade) {
											echo '<option class="opt" value="' . $umaentidade['idcontatos'] . '" ';
											if($umaentidade['idcontatos'] == $contato_id OR (isset($_GET['who']) AND $umaentidade['idcontatos'] == $_GET['who'])) {
												echo 'selected';
											}
											echo '>'. utf8_decode(utf8_encode($umaentidade['nome'])) . "</option>";
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
					
					<div style="margin-top:3vh;"><br></div>
					
					
					<!---------------------------------------------------------------
					add_imovel	delimovel	domimovelid		savecol2 	savenewimovel
					---------------------------------------------------------------->
					<span style="font-size:3vh;">Imóveis associados</span>
					<i class="fa fa-plus" id="add_imovel"></i>
					<div class="card" style="width: 16vw; margin-top:1vh;">
						<div class="card-header">
							Nomes
						</div>
						<ul class="list-group list-group-flush">
							<?php
							foreach($imoveis as $imovel) {
								echo '
									<li class="list-group-item" >
										<a href="entidade?id='. $imovel['ID'] .'">' .  $imovel['title'] .  '</a> 
										<i class="fas fa-times delimovel" data-idc="'. $imovel['ID'].'" style="float:right"></i>
									</li>';
							}
							?>
							<li class="list-group-item" id="add_li2" style="display: none;">
								<div class="form-group row">
									<select class="browser-default custom-select col-10" onfocus='this.size=5;'
											onblur='this.size=1;' onchange='this.size=1; this.blur();' id="domimovelid"
											name="domimovelid">
										<option disabled selected value> </option>
										<?php
										foreach($all_imoveis as $umimovel) {
											echo '<option class="opt" value="' . $umimovel['ID'] . '" ';
											if($umimovel['ID'] == $contato_id OR (isset($_GET['who']) AND $umimovel['ID'] == $_GET['who'])) {
												echo 'selected';
											}
											echo '>'. utf8_decode(utf8_encode($umimovel['title'])). "</option>";
										}
										?>
									</select>
									<div class="col-2" id="savecol2">
										<div class="font-weight-bold text-uppercase mb-1" style="color:gray;">
											<i class="fas fa-check-circle" id="savenewimovel" style="height:1vh; font-size:3.2vh"></i>
										</div>
									</div>

								</div>
							</li>
						</ul>
					</div>

				</div>
				<!------------------------------------------------>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <?php include_once 'footer.php'; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pronto para sair?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Selecione "Sair" para terminar a sua sessão atual.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="php/logout.php">Sair</a>
        </div>
      </div>
    </div>
  </div>

	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/sb-admin-2.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
$(document).ready(function(){
	
	$('#history_contact').DataTable({
		"scrollY": "700px",
		"scrollCollapse": true,
		"paging": false,
		"bInfo" : false
	} );
	
	$('#delbtn').click(function(){
		swal({
		  title: "Tem a certeza?",
		  text: "Depois de apagado, não será possível recuperar este registo!",
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
				  text: "Registo apagado com sucesso! " + data,
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
	
	
	
	
	$('#add_li').hide();
	$('#add_li2').hide();

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

	$('#domcontatoid').change(function () {
		var saveit = $('#savenewrole');
		saveit.css('color', 'green');
		saveit.css('cursor', 'pointer');
	});

	$('#savenewrole').click(function () {
		var sel = $("#domcontatoid option:selected").val();
		var ang = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; } ?>;
		var page = 1;
		if(ang != 0) {
			if ($(this).css('color') === 'rgb(0, 128, 0)') {
				$.ajax({
					url: "php/_addcontact.php",
					method: 'POST',
					async: false,
					data: {
						'page' : page,
						'id_contact': sel,
						'id_ang': ang
					},
					success: function (result) {
						window.location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
				});
			}
		}
	});

	$('.delcontato').click(function () {
		
		var sel = $(this).data('idc');
		var ang = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; } ?>;
		var page = 1;
		$.ajax({
			url: "php/_delcontato.php",
			method: 'POST',
			async: false,
			data: {
				'page' : page,
				'id_contact': sel,
				'id_ang': ang
			},
			success: function (result) {
				window.location.reload();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});

	
	//--------------------------------//
	//<!---------------------------------------------------------------
	//add_imovel	delimovel	domimovelid		savenewimovel
	//---------------------------------------------------------------->
	
	$('#add_imovel').click(function () {
		if ($(this).hasClass('fa-plus')) {
			$('#add_li2').show();
			$(this).removeClass('fa-plus');
			$(this).addClass('fa-minus');
		} else {
			$('#add_li2').hide();
			$(this).addClass('fa-plus');
			$(this).removeClass('fa-minus');
		}
	});

	$('#domimovelid').change(function () {
		var saveit = $('#savenewimovel');
		saveit.css('color', 'green');
		saveit.css('cursor', 'pointer');
	});

	$('#savenewimovel').click(function () {
		var sel = $("#domimovelid option:selected").val();
		var ang = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; } ?>;
		var page = 2;
		if(ang != 0) {
			if ($(this).css('color') === 'rgb(0, 128, 0)') {
				$.ajax({
					url: "php/_addcontact.php",
					method: 'POST',
					async: false,
					data: {
						'page' : page,
						'id_imo': sel,
						'id_ang': ang
					},
					success: function (result) {
						window.location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
				});
			}
		}
	});

	$('.delimovel').click(function () {
		
		var sel = $(this).data('idc');
		var ang = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; } ?>;
		var page = 2;
		$.ajax({
			url: "php/_delcontato.php",
			method: 'POST',
			async: false,
			data: {
				'page' : page,
				'id_imo': sel,
				'id_ang': ang
			},
			success: function (result) {
				window.location.reload();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});
	
	
	//-------------------------------------------------------------------------//
	$('#assinar_group').hide();
	$('#btnassinar').click(function(){
		if($('#btnassinar').hasClass('btn-dark')) {
			$('#btnassinar').removeClass('btn-dark');
			$('#assinar_group').show();
		}
		else {
			$('#btnassinar').addClass('btn-dark');
			$('#assinar_group').hide();
		}

	});

	$( ".drag_entity" ).draggable({ 
		snap: ".ui-widget-header", 
		snapMode: "inner",
		start: function(event, ui) { $(this).css("color","green"); },
        stop: function(event, ui) { $(this).css("color","black"); }, 
		revert : function(event, ui) {
			// on older version of jQuery use "draggable"
			// $(this).data("draggable")
			// on 2.x versions of jQuery use "ui-draggable"
			// $(this).data("ui-draggable")
			$(this).data("uiDraggable").originalPosition = {
				top : 0,
				left : 0
			};
			// return boolean
			return !event;
			// that evaluate like this:
			// return event !== false ? false : true;
		}
	});
	
	
	$( ".ui-widget-header" ).droppable({
		drop: function(event, ui) {
			var $this = $(this);
			ui.draggable.position({
				my: "center",
				at: "center",
				of: $this,
				using: function(pos) {
					$(this).animate(pos, 200, "linear");
				}
			});
		}
	});

	//$( ".drag_entity" ).


});

</script>



</body>
</html>

