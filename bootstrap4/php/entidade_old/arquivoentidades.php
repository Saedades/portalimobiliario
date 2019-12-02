<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
$contactlist = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $id . " AND estado=0")->fetch_all(MYSQLI_ASSOC);
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
<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../css/pretty-checkbox.min" />

<style>


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
        <h1 class="h3 mb-4 text-gray-800">Lista de Entidades Apagadas</h1>
      </div>
      <!-- /.container-fluid -->
      <div class="card shadow m-2 col-md-9 addons">

    <div class="form-group">
      <div class="card-body">
        <table id="contacts">
          <thead>
            <th style="width:30%">Nome</th>
            <th style="width:15%">Telemóvel</th>
            <th style="width:25%">Email</th>
            <th style="width:25%">Estado</th>
            <th style="width:5%" hidden></th>
          </thead>
          <tbody>
            <?php
                      foreach($contactlist as $mycontact) {
                          echo '<tr class="a_contact" data-theid="' . $mycontact['id'] . '">';
                              echo '<td style="width:30%">'. utf8_decode(utf8_encode($mycontact['nome']))."</td>";
                              echo '<td style="width:15%">'.$mycontact['telemovel']."</td>";
                              echo '<td style="width:25%">'.$mycontact['email']."</td>";
              echo '<td style="width:25%">'.$mycontact['estado']."</td>";
                              echo "<td hidden>" . '<i class="fas fa-times delete_contato"></i>' . "</td>";
                          echo "</tr>";
                      }
                      ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
        <!-- End of Main Content -->
  </div>
  <!-- End of Content Wrapper -->
  <?php include '../partials/footer.php'; ?>
</div>
<!-- End of Page Wrapper -->

<?php include '../partials/modalandscroll.php'; ?>

<!---//----------------ALL SCRIPTS ARE IN THIS FILE-------------------------//--->
<?php include '_entidade_js.php'; ?>

</body>

</html>
