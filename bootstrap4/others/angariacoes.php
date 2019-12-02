<?php
require_once 'php/connectDB.php';
include_once 'php/validate_session.php';

$angariacoes = $conn->query("SELECT * FROM angariacoes WHERE iduser = " . $_SESSION['login'])->fetch_all(MYSQLI_ASSOC);
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
	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
	

	<style>
		.ang_row:hover {
			color: white;
			background-color: #8397d1 !important;
			cursor: pointer;
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
			


			<div class="card-header py-1" style="width:auto; padding-right:0">
				<h1 class="h3 mb-4 text-gray-800">Lista de Angariações</h1>
				<br>
				<a href="reports.php" style="" class="btn btn-primary btn-icon-split col-3">
					<span class="icon text-white-50" style="position:absolute; left:0;">
						<i class="fas fa-plus"></i>
					</span>
					<span class="text" style="padding-left: 2vw;">Angariação</span>
				</a>
			</div>
			<div class="form-group">
				<input type="file" class="form-control-file" id="profilePicInput" name="profpic" hidden>
				<div class="card-body">
					<table id="table_angariacoes">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Objetos</th>
								<th>Estado</th>
								<th>Data</th>
								<th hidden></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($angariacoes as $angariacao) {
								$imovel_ids = $conn->query('SELECT idimovel FROM angariacoes_imoveis WHERE idangariacao = ' . $angariacao['id']);
								if($imovel_ids->num_rows==1) {
									$objecto = $conn->query('SELECT title FROM imoveis WHERE ID = ' . $imovel_ids->fetch_assoc()['idimovel'])->fetch_assoc()['title'];
								}
								elseif($imovel_ids->num_rows<4) {
									$objecto ="";
									$imovel_ids->fetch_all(MYSQLI_ASSOC);
									foreach($imovel_ids as $id) {
										$objecto .= $conn->query('SELECT title FROM imoveis WHERE ID = ' . $id['idimovel'])->fetch_assoc()['title'] . "; ";
									}
								}
								else {
									$objecto = "Grupo de imóveis";
								}
								
								if($angariacao['assinado_em'] == 0) {
									$data_assinado = "Não assinado";
								}
								else {
									$data_assinado = "2019";
										//date('Y-m-d', $angariacao['assinado_em']);
								}
								echo '<tr class="ang_row" data-ang="' . $angariacao['id'] . '">';
									echo '<td>' . $angariacao['titulo'] . 	'</td>';
									echo '<td>' . $objecto . 				'</td>';
									echo '<td>' . $angariacao['estado'] . 	'</td>';
									echo '<td>' . $data_assinado . 			'</td>';
									echo '<td hidden>' .   '</td>';
								echo '</a>';	
							}
							?>
						</tbody>
					</table>
				</div>
			</div>





		</div>
		<!-- End of Main Content -->


		<!-- Footer -->
		<footer class="sticky-footer bg-white">
			<div class="container my-auto">
				<div class="copyright text-center my-auto">
					<span>Copyright &copy; 2019 ALOHA - Bussiness & Software, Lda</span>
				</div>
			</div>
		</footer>
		<!-- End of Footer -->

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

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script>

    $(document).ready(function () {

		$('#table_angariacoes').DataTable({
			"order": [],
			"scrollY": "300px",
			"scrollCollapse": true,
			"paging": false,
			"bInfo": false
		});
		
		$('.ang_row').click(function(){
			var id = $(this).data('ang');
			window.location.replace('angariacao.php?id=' + id);
		});

		

    });

</script>

</body>

</html>
