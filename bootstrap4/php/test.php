
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
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">


  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../css/subjects.css" rel="stylesheet">
  <style>
    .deletefile:hover {
      color: darkred;
      cursor: pointer;
    }

    .deletenewfile:hover {
      color: darkred;
      cursor: pointer;
    }

    .deletenewphoto {
      color: gray;
    }

    .deletenewphoto:hover {
      color: darkred;
      cursor: pointer;
    }

    #addfilebutton:hover {
      color: blue;
      cursor: pointer;
    }

    .filetype_option:hover {
      cursor: pointer;
    }

  </style>

</head>

<body id="page-top">
  <!------------------------------	CONTRACTS 	 ------------------------------------->
  <br>
  <span style="font-size:3vh;margin:10vh;">Contratos </span>

  <div class="row" id="contratos" style="padding:10vh;width:60%;margin:auto;">



    <!--- ANGARIAÇÃO --->
    <div id="leftcard">
      <div id="ang" class="card border-left-info shadow contrato"
        style="background-color: #f8f9fc; width: 12vw; margin: 1vh;">
        <div id="angt" class="card-header text-center anghover"
          style="background-color: #f8f9fc; cursor: pointer; margin-top: 9vh; margin-bottom: 9vh; border: 0;">
          <a id="anghe">ANGARIAÇÃO </a><i id="min_ang" class="cont_fa fa fa-minus"
            style="float: right; display: none; cursor: pointer;"></i>
        </div>

        <div id="angbody" style="display: none;">
          <div id="display_card_group" class="card-group">
            <div class="card">
              <div class="card-header text-center">Categorias</div>
            </div>
            <div class="card">
              <div class="card-header text-center">Nome</div>
            </div>
            <div class="card">
              <div class="card-header text-center">Data</div>
            </div>
            <div class="" style="float:right; height:2vh; width:3vh; margin:auto;">
              <span style="margin:10px">
              </span>
            </div>
          </div>

          <form id="file_form" style="unset:all" method="POST" enctype="multipart/form-data"
            action="php/_upload_subject_file.php">
            <input type="text" value="<?php echo (isset($_GET['id'])) ? $_GET['id'] : 0; ?>"
              name="subject_id" hidden>
            <input type="text" value="1" name="type_of_contract" hidden>
            <div id="input_card_group" class="card-group">
              <div class="card">
                <select class="form-control" id="exampleFormControlSelect1" style="margin:auto"
                  name="ifiletype">
                  <option class="filetype_option">Escolha o tipo</option>
                  <?php
            foreach($filetypes as $type) {
              echo '<option class="filetype_option" value="' . $type['id'] . '">'. utf8_decode(utf8_encode($type['nome'])) . "</option>";
            }
            ?>
                </select>
              </div>
              <div class="card">
                <div class="custom-file" style="margin:auto;width:95%;">
                  <input type="file" class="custom-file-input" id="ifile" name="ifile">
                  <label id="filenamedisplay" class="custom-file-label" for="customFile">Ficheiro</label>
                </div>
              </div>
              <div class="card">
                <div class="card-body" style="margin:8px;width:95%;">
                  <?php echo date("d, M Y"); ?>
                  <div class="font-weight-bold text-uppercase mb-1"
                    style="color:gray; float:right; z-index:1000">
                    <i class="fas fa-check-circle" id="upload_file" style="height:1vh; font-size:3.2vh"></i>
                  </div>
                </div>
              </div>
              <div class="" style="float:right; height:2vh; width:3vh; margin:auto;">
                <span style="margin:10px">
                </span>
              </div>
            </div>
            <div class="" style="float:right; height:2vh; width:3vh; margin:auto;">
              <span style="margin:10px">

              </span>
            </div>
          </form>
          <div class="" style="height:4vh; width:95%; margin:auto; padding:10px; text-align:center;">
            <span id="addfilebutton">
              <i class="fas fa-plus" id="addfile" style="height:1vh; font-size:1.5vh;"></i> &nbsp;Adicionar
              ficheiro &nbsp;&nbsp;&nbsp;
            </span>
          </div>
          <br>
          <div class="form-group">
            <label for="domtitle">Nome</label>
            <input type="text" class="form-control" id="domtitle" name="domtitle"
              value="<?php echo $imovel_title;?>" required>
          </div>
        </div>
      </div>
    </div>

    <!------VENDA------->
    <div id="rightcard">
      <div id="ven" class="card border-left-info shadow contrato"
        style="background-color: #f8f9fc; width: 12vw; margin: 1vh;">
        <div id="vent" class="card-header text-center venhover"
          style="background-color: #f8f9fc; cursor: pointer; margin-top: 9vh; margin-bottom: 9vh; border: 0;">
          <a id="venhe">VENDA </a><i id="min_ven" class="cont_fa fa fa-minus"
            style="float: right; display: none; cursor: pointer;"></i></div>
        <div id="venbody" style="display: none;">
          <div class="card-group">
            <div class="card">
              <div class="card-header text-center">Categorias</div>
              <div class="card-body">
              </div>
            </div>
            <div class="card">
              <div class="card-header text-center">Upload</div>
              <div class="custom-file" style="margin:8px;width:95%;">
                <input type="file" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Selecione o ficheiro</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="domtitle">Nome</label>
            <input type="text" class="form-control" id="domtitle" name="domtitle"
              value="<?php echo $imovel_title;?>" required>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/sb-admin-2.min.js"></script>

  <script>

    $(document).ready(function(){
      $('.contrato').mouseover(function () {
        $(this).removeClass('shadow');
        $(this).addClass('shadow-lg');
      })
      .mouseout(function () {
        $(this).removeClass('shadow-lg');
        $(this).addClass('shadow');
      });

    var ang = 0;
    var ven = 0;
    var left = $('#leftcard');



    $('#anghe').click(function () {

      $('#ang').removeClass('animate_not_groww_ang');
      $('#ang').parent().removeClass('animate_not_groww_ang');
      $('#angt').removeClass('animate_not_headertop_ang');
      $('#angt').removeClass('anghover');

      $('#angt').css('cursor', 'default');

      $('#ang').addClass('animate_groww_ang');
      $('#ang').parent().addClass('animate_groww_ang');
      $('#angt').addClass('animate_headertop_ang');

      $('#angbody').show();
      $('#min_ang').show();
  });

  $('#min_ang').click(function () {

    $('#ang').removeClass('animate_groww_ang');
    $('#ang').parent().removeClass('animate_groww_ang');
    $('#angt').removeClass('animate_headertop_ang');

    $('#ang').addClass('animate_not_groww_ang');
    $('#ang').parent().addClass('animate_not_groww_ang');
    $('#angt').addClass('animate_not_headertop_ang');
    $('#angt').addClass('anghover');

    $('#angt').css('cursor', 'pointer');

    $('#angbody').hide();
    $('#min_ang').hide();
  });

    $('#venhe').click(function () {

      $('#leftcard').remove();

      $('#ven').removeClass('animate_not_groww_ven');
      $('#ven').parent().removeClass('animate_not_groww_ven');
      $('#vent').removeClass('animate_not_headertop_ven');
      $('#vent').removeClass('venhover');

      $('#vent').css('cursor', 'default');

      $('#ven').addClass('animate_groww_ven');
      $('#ven').parent().addClass('animate_groww_ven');
      $('#vent').addClass('animate_headertop_ven');

      $('#venbody').show();
      $('#min_ven').show();

      $('#rightcard').after(left);

    if (($('#ang').hasClass('animate_groww_ang') || $('#ang').parent().hasClass('animate_groww_ang'))) {
      $('#ang').removeClass('animate_groww_ang');
      $('#ang').parent().removeClass('animate_groww_ang');
      $('#angt').removeClass('animate_headertop_ang');

      $('#ang').addClass('animate_not_groww_ang');
      $('#ang').parent().addClass('animate_not_groww_ang');
      $('#angt').addClass('animate_not_headertop_ang');
      $('#angt').addClass('anghover');

      $('#angt').css('cursor', 'pointer');

      $('#angbody').hide();
      $('#min_ang').hide();
    }

    $('#anghe').click(function () {

      $('#ang').removeClass('animate_not_groww_ang');
      $('#ang').parent().removeClass('animate_not_groww_ang');
      $('#angt').removeClass('animate_not_headertop_ang');
      $('#angt').removeClass('anghover');

      $('#angt').css('cursor', 'default');

      $('#ang').addClass('animate_groww_ang');
      $('#ang').parent().addClass('animate_groww_ang');
      $('#angt').addClass('animate_headertop_ang');

      $('#angbody').show();
      $('#min_ang').show();

      if (($('#ven').hasClass('animate_groww_ven') || $('#ven').parent().hasClass('animate_groww_ven'))) {
        $('#leftcard').remove();

        $('#ven').removeClass('animate_groww_ven');
        $('#ven').parent().removeClass('animate_groww_ven');
        $('#vent').removeClass('animate_headertop_ven');

        $('#ven').addClass('animate_not_groww_ven');
        $('#ven').parent().addClass('animate_not_groww_ven');
        $('#vent').addClass('animate_not_headertop_ven');
        $('#vent').addClass('anghover');

        $('#vent').css('cursor', 'pointer');

        $('#venbody').hide();
        $('#min_ven').hide();

        $('#rightcard').before(left);
      }

      $('#min_ang').click(function () {
      $('#ang').removeClass('animate_groww_ang');
      $('#ang').parent().removeClass('animate_groww_ang');
      $('#angt').removeClass('animate_headertop_ang');

      $('#ang').addClass('animate_not_groww_ang');
      $('#ang').parent().addClass('animate_not_groww_ang');
      $('#angt').addClass('animate_not_headertop_ang');
      $('#angt').addClass('anghover');

      $('#angt').css('cursor', 'pointer');

      $('#angbody').hide();
      $('#min_ang').hide();
    });

    });



  });

  $('#min_ven').click(function () {

    $('#rightcard').before(left);

    $('#ven').removeClass('animate_groww_ven');
    $('#ven').parent().removeClass('animate_groww_ven');
    $('#vent').removeClass('animate_headertop_ven');

    $('#ven').addClass('animate_not_groww_ven');
    $('#ven').parent().addClass('animate_not_groww_ven');
    $('#vent').addClass('animate_not_headertop_ven');
    $('#vent').addClass('anghover');

    $('#vent').css('cursor', 'pointer');

    $('#venbody').hide();
    $('#min_ven').hide();
  });

    $('#min_ang').click(function () {
      $('#rightcard').before(left);

      $('#ang').removeClass('animate_groww_ang');
      $('#ang').parent().removeClass('animate_groww_ang');
      $('#angt').removeClass('animate_headertop_ang');

      $('#ang').addClass('animate_not_groww_ang');
      $('#ang').parent().addClass('animate_not_groww_ang');
      $('#angt').addClass('animate_not_headertop_ang');
      $('#angt').addClass('anghover');

      $('#angt').css('cursor', 'pointer');

      $('#angbody').hide();
      $('#min_ang').hide();
    });



    $('#contatos_table').DataTable({
      "scrollY": "700px",
      "scrollCollapse": true,
      "paging": false,
      "bInfo": false
    });
  });

  </script>
</body>
</html>
