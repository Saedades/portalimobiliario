<?php
require_once 'php/connectDB.php';
include_once 'php/validate_session.php';

if($conn->query("SELECT admin FROM users WHERE idusers=" . $_SESSION['login'])->fetch_assoc()['admin']==1)
	$userlist = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
else
	header('Location: profile.php');
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
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Lista de Utilizadores</h1>
        </div>
        <!-- /.container-fluid -->
        <div class="card shadow m-2 col-md-9 addons">
          <div class="card-header py-1 row" style="width:auto; padding-right:0">
            <h6 style="" class="m-2 font-weight-bold text-primary col-3" hidden>Lista de Utilizadores</h6>
            <a href="contact.php" style="" class="btn btn-primary btn-icon-split col-3">
              <span class="icon text-white-50" style="position:absolute; left:0;">
                <i class="fas fa-plus"></i>
              </span>
              <span class="text" style="padding-left: 2vw;">Utilizador</span>
            </a>
          </div>
          <div class="form-group">
            <div class="card-body">
              <table id="contacts">
                <thead>
                  <th>Nome</th>
                  <th>Email</th>
				  <th></th>
                </thead>
                <tbody>
                  <?php
					foreach($userlist as $user) {
						echo '<tr class="a_contact" data-theid="' . $user['idusers'] . '">';
						echo '<td style="width:50%">' . utf8_decode(utf8_encode($user['name'])) . "</td>";
						echo '<td style="width:20%">' . $user['email'] . "</td>";
						echo "<td>" . '<i class="fas fa-times delete_contato"></i>' . "</td>";
						echo "</tr>";
					}
					?>
                </tbody>
              </table>
            </div>
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

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="css/pretty-checkbox.min" />

  <script>
    $(document).ready(function () {
      $('.a_contact').dblclick(function () {
        var the_id = $(this).data("theid");
        window.location.replace('contact.php?contactid=' + the_id);
      });

      $('#contacts').DataTable({
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false,
        "bInfo": false
      });

      $('.delete_contato').click(function () {
        swal({
          title: "Tem a certeza?",
          text: "Depois de apagado, não será possível recuperar este registo!",
          icon: "warning",
          buttons: ["Cancelar", "Apagar"],
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {

            var theid = $(this).parent().parent().data('theid');
            //var datats = thedad.data('fichodulos').toString();

            $.ajax({
              type: "POST",
              url: "php/_delete_user.php",
              data: {
                id_delete: theid,
                the_type: 2
              },
              cache: false,
              success: function (data) {
                swal({
                  text: "Registo apagado com sucesso! " + data,
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
    });

  </script>

</body>

</html>
