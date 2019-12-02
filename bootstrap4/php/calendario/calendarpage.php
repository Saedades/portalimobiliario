<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';
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

    <style>
        .today {
            background-color: #b3d4fc;
        }

        .mc {
            cursor:pointer;
        }

        .mc:hover {
            background-color: lightgray;
        }

        #monthyearspan {
            float:left;
        }

        #yearspan {
            float:left;
            margin-left:4vw;
        }

        #scroller {
            overflow: auto;
            height: 100px;
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
                <a href="http://localhost/foco20/src/bootstrap4/calendarpage.php" class="btn btn-light btn-icon-split col-sm-1" hidden>
                    <span class="icon text-white-50" style="position:absolute; left:0;">
                        <i class="fas fa-backward"></i>
                    </span>
                </a>
                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Calendário</h1>
                <?php include 'calendar.php'; ?>

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

<!-- Bootstrap core JavaScript-->
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../js/sb-admin-2.min.js"></script>
<script>

    $(document).ready(function () {
        var month = <?php echo $month; ?>;
        var year = <?php echo $year; ?>;

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus');
        });

        $('.mc').click(function () {
            var new_month = $(this).html();
            var thebutton = $('.bmc');
            thebutton.html(new_month);
            
			var new_month_date = 0;
			if(new_month === "Janeiro") {
				new_month_date=1;
			}
			if(new_month === "Fevereiro") {
				new_month_date=2;
			}
			if(new_month === "Março") {
				new_month_date=3;
			}
			if(new_month ===  "Abril") {
				new_month_date=4;
			}
			if(new_month === "Maio") {
				new_month_date=5;
			}
			if(new_month ==="Junho") {
				new_month_date=6;
			}
			if(new_month === "Julho") {
				new_month_date=7;
			}
			if(new_month === "Agosto") {
				new_month_date=8;
			}
			if(new_month === "Setembro") {
				new_month_date=9;
			}
			if(new_month === "Outubro") {
				new_month_date=10;
			}
			if(new_month === "Novembro") {
				new_month_date=11;
			}
			if(new_month === "Dezembro") {
				new_month_date=12;
			}



            var year2 = $('.byc').html().trim();
            thebutton.data('iddate', new_month_date);
            //thebutton.data('iddate', new_month));
            //alert(thebutton.data('iddate'));
            window.location.replace("calendarpage.php?year=" + year2 + "&month=" + new_month_date);
        });

        $('.yc').click(function () {
            var new_year = $(this).html().trim();
            $('.byc').html(new_year);
            var month2 = $('.bmc').data('iddate');
            window.location.replace("calendarpage.php?year=" + new_year + "&month=" + month2);
        });


    });

</script>

</body>

</html>
