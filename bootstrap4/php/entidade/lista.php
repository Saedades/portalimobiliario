<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';

$listaentidades = ($result = $conn->query("SELECT * FROM entidades")) ? $result->fetch_all(MYSQLI_ASSOC) : array();

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
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/pretty-checkbox.min" />

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

    <?php include '../partials/sidebar.php'; ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <?php include '../partials/topbar.php'; ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Lista de Entidades</h1>
          <!-- /.container-fluid -->
          <div class="card shadow col-11">
            <div class="card-header py-1 row" style="width:auto; padding-right:0">
              <h6 style="" class="m-2 font-weight-bold text-primary col-3" hidden>Lista de Entidades</h6>
              <a href="entidade.php" style="" class="btn btn-primary btn-icon-split col-3">
                <span class="icon text-white-50" style="position:absolute; left:0;">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text" style="padding-left: 2vw;">Entidade</span>
              </a>
              <div style="position:absolute;right:10px; padding:5px;"><a href="arquivoentidades.php">Arquivo</a></div>
            </div>
        <div class="form-group">
          <div class="card-body">
            <table id="contacts">
              <thead>
                <th style="width:30%">Nome</th>
                <th style="width:25%">Rating</th>
                <th style="width:25%">Categoria</th>
                <th style="width:15%">Telemóvel</th>
                <th style="width:25%">Email</th>
                <th style="width:5%" hidden></th>
              </thead>
              <tbody>
                <?php
                  foreach($listaentidades as $ent) {
                      echo '<tr class="a_contact" data-theid="' . $ent['id'] . '">';
                          echo '<td style="width:30%">'. $ent['nome'] . "</td>";
                          echo '<td style="width:15%">'.$conn->query("SELECT nome FROM ratings WHERE id=" . ((isset($ent['rating'])) ? $ent['rating'] : 0))->fetch_assoc()['nome'] ."</td>";
                          echo '<td style="width:25%">'.$conn->query("SELECT nome FROM categorias WHERE id=" . ((isset($ent['categoria'])) ? $ent['categoria'] : 0))->fetch_assoc()['nome']."</td>";
                          echo '<td style="width:25%">'.$ent['telemovel']."</td>";
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

  <?php include '../partials/modalandscroll.php' ?>

  <!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->
<?php include '_entidade_js.php'; ?>

</body>

</html>
