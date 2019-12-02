<?php
    require_once '../partials/connectDB.php';
    include_once '../partials/validate_session.php';

	$lista_classificadores = ($result = $conn->query('SELECT * FROM classificadores ORDER BY id ASC')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
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
        <link href="../../css/subjects.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/pretty-checkbox.min" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
        <!---<script src="../../vendor/jquery/jquery.js"></script>-->
        <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
        <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../../js/sb-admin-2.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="../../js/jquery.formatCurrency-1.4.0.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js" 
				integrity="sha256-apFUVcutYBHTJh5O835gpzGcVk3v6iUxg38lKBpQMDA=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css" 
			  integrity="sha256-JHRpjLIhLC03YGajXw6DoTtjpo64HQbY5Zu6+iiwRIc=" crossorigin="anonymous" />
		
		
		<script src="../../vendor/color-picker-drawr-palette/src/jquery.drawrpalette.js"></script>

		

		<style>
			i {
				cursor:pointer;
			}
			.fa-trash:hover {
				color:darkred;
			}
			#colorSelector2 {
				position: absolute;
				top: 0;
				left: 0;
				width: 36px;
				height: 36px;
				background-color:grey;
			}
			
			.span_classificador:hover {
				color:black;
			}
		</style>
		
		<script>
			$(document).ready(function(){
				$('.fa-trash').click(function(){
					var idcla = $(this).data('idcla');
					$.ajax({
						type: "POST",
						url: '_classificadores.php',
						data: {
							'action': 2,
							'id' : idcla
						},
						success: function(result){
							window.location.reload();
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							alert("some error " + errorThrown);
						}
					});
				});
				
				$('.newlistitem').hide()
				$('#new_classificador').click(function(){
					$('.newlistitem').fadeIn();
				}); 

				$('.newlistitem').bind("keypress", function(e) {
					if (e.keyCode == 13) {
						var texto = $('#newitemtext').val();
						$.ajax({
							type: "POST",
							url: '_classificadores.php',
							data: {
								'action': 1,
								'nome' : texto
							},
							success: function(result){
								window.location.reload();
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								alert("some error " + errorThrown);
							}
						});
					}
				});
				
				
				$(".picker").drawrpalette()
					.on("preview.drawrpalette",function(event,hexcolor){})
					.on("cancel.drawrpalette",function(event,hexcolor){})
					.on("choose.drawrpalette",function(event,hexcolor){
						var idcla = $(this).parent().parent().find('i').data('idcla');
						$.ajax({
							type: "POST",
							url: '_classificadores.php',
							data: {
								'action': 3,
								'id' : idcla,
								'hex' : hexcolor
							},
							success: function(result){
								Swal.fire(
								  'Sucesso!',
								  'A cor foi modificada.',
								  'success'
								)
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								alert("some error " + errorThrown);
							}
						});		
					})
					.on("open.drawrpalette",function(){})
					.on("close.drawrpalette",function(){});
				
				$('.span_classificador').click(function(){
					var content = $(this).text();
					var idcla = $(this).attr('id');
					$(this).parent().append('<input class="form-control input_classificador" value="'+content+'" />');
					$(this).hide();
					
					$('.input_classificador').bind("keypress", function(e) {
						if (e.keyCode == 13) {
							var texto = $(this).val();
							
							$.ajax({
								type: "POST",
								url: '_classificadores.php',
								data: {
									'action': 4,
									'nome' : texto,
									'id': idcla
								},
								success: function(result){
									Swal.fire(
									  	'Sucesso!',
									  	'O classificador foi modificado.',
									  	'success'
									);
									$('#'+idcla).text(texto);
									$('#'+idcla).show();
									$('#'+idcla).parent().find('input').hide();
								},
								error: function(XMLHttpRequest, textStatus, errorThrown) {
									alert("some error " + errorThrown);
								}
							});
						}
					});
					
				});
				
				
			});
		</script>
			
	</head>


    <body id="page-top">
        <div id="wrapper">
            <?php include '../partials/sidebar.php'; ?>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php include '../partials/topbar.php'; ?>
                    <div class="container-fluid" style="max-width:600px;margin:unset;">
						
						<h1 class="h3 mb-4 text-gray-800">Classificadores</h1>

						<div class="form-group" id="classificador_div">
							<label class="form-check-label" for="domclassificador">Modifique, apague ou&nbsp;</label><a href="#" id="new_classificador">crie um novo</a>
							<br>
							<ul class="list-group list-group-flush">
								<?php
								foreach($lista_classificadores as $classificador) {
									echo '<li class="list-group-item" style="border:1px solid #caccd9; border-radius:8px">';
									echo '<div class="row">';
									echo '<div class="col-10"><span class="span_classificador" id="'.$classificador['id'].'">'.$classificador['nome'].'<span></div>';
									echo '<div class="col-0"><input style="float:left; margin-right:15px;" type="text" class="picker" value="'.$classificador['cor'].'"/>';
									if($classificador['id']!=1)
										echo '<i data-idcla="'.$classificador['id']. '" class="fas fa-trash" style="position: relative;top: -14px;"></i>';
									echo '</div>';
									echo '</div>';
									echo '</li>';
								}
								?>
								<li class="list-group-item newlistitem" style="border:1px solid #caccd9; border-radius:8px">
									<div class="row">
										<input style="all:unset; padding-left:10px;" name="newitemtext" id="newitemtext" />
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>