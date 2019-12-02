<?php
    require_once '../partials/connectDB.php';
    include_once '../partials/validate_session.php';

    if (isset($_GET['id'])) {
        //if is editing
        $title = "Editar Imóvel";
        $imovel_id = $_GET['id'];
        $user_id = $_SESSION['login'];
        //get more DB info
        $imovel_pre_info = $conn->query("SELECT * FROM imoveis WHERE user = " . $user_id . " AND id = " . $imovel_id);
        //if the query was successful
        if ($imovel_pre_info) {
            $imovel_info = $imovel_pre_info->fetch_assoc();
            $imovel_title = $imovel_info['titulo'];
            $imovel_kwid = $imovel_info['kwid'];
            $imovel_ref = $imovel_info['id'];
            $imovel_description = $imovel_info['descricao'];
            $imovel_status = $imovel_info['estado'];
            $imovel_value = $imovel_info['val_neg'];
            $imovel_value2 = $imovel_info['val_co_contra'];
            $imovel_value3 = $imovel_info['val_co_cobra'];
            $imovel_domaddr = $imovel_info['morada'];
            $imovel_local = $imovel_info['local'];
            $imovel_zip1 = $imovel_info['zip1'];
            $imovel_zip2 = $imovel_info['zip2'];
            $imovel_dist = $imovel_info['dist'];
        } else {
            header('Location: ../profile/profile.php');
        }
    } else {
        //if is creating
        $title = "Novo Imóvel";
        $imovel_id = 0;
        $user_id = $_SESSION['login'];
        $imovel_title = '';
        $imovel_kwid = '';
        $imovel_ref = '';
        $imovel_description = "";
        $imovel_status = "";
        $imovel_value = "";
        $imovel_value2 = "";
        $imovel_value3 = "";
        $imovel_local = "";
        $imovel_domaddr = "";
        $imovel_zip1 = '';
        $imovel_zip2 = '';
        $imovel_dist = 0;
    }



    $all_entidades = $conn->query('SELECT * FROM entidades WHERE user =' . $user_id)->fetch_all(MYSQLI_ASSOC);
    $entidades = ($result = $conn->query('SELECT * FROM entidades WHERE id IN (SELECT entidade FROM imoveis_entidades WHERE imovel = ' . $imovel_id . " AND deleted IS NULL)")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $photolist = ($result = $conn->query('SELECT * FROM imoveis_fotos WHERE imovel = ' . $imovel_id)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $history = ($result = $conn->query("SELECT * FROM contatos WHERE entidade IN (SELECT entidade FROM foco200.imoveis_entidades WHERE imovel = " . $imovel_id . ")")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $imo_files = ($result = $conn->query('SELECT * FROM imoveis_ficheiros WHERE imovel = ' . $imovel_id)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    //$ang_id = ($result = $conn->query('SELECT idang FROM angariacoes_imoveis WHERE idimo =' . $imovel_id)) ? $result->fetch_assoc() ['idang'] : 0;
    //$ang_files = ($result = $conn->query('SELECT * FROM angariacoes_ficheiros WHERE idangariacoes = ' . $ang_id)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $filetypes = ($result = $conn->query("SELECT * FROM tipo_ficheiro")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $estado_selected = ($result = $conn->query("SELECT estado FROM imoveis WHERE user = " . $user_id . " AND id = " . $imovel_id)) ? $result->fetch_assoc() ['estado'] : 0;
    //$selected_risco_value = $conn->query("SELECT risco FROM foco20.angariacoes WHERE id IN (SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=" . $imovel_id . ")")->fetch_assoc() ['risco'];
    //$selected_estado_value = $conn->query("SELECT estado FROM foco20.angariacoes WHERE id IN (SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=" . $imovel_id . ")")->fetch_assoc() ['estado'];
    $tipologias = ($result = $conn->query("SELECT * FROM tipologias")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    //$empreendimentos = ($result = $conn->query("SELECT * FROM tiposimo")) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $selected_tipologia_value = ($result = $conn->query("SELECT tipologia FROM foco20.imoveis WHERE id =" . $imovel_id)) ? $result->fetch_assoc() ['tipologia'] : 0;
    //$selected_emp_value = ($result = $conn->query("SELECT tipoimo FROM foco20.imoveis WHERE id =" . $imovel_id)) ? $result->fetch_assoc() ['tipoimo'] : 0;
    $negocio_selected = ($result = $conn->query("SELECT negocio FROM imoveis WHERE id = " . $imovel_id)) ? $result->fetch_assoc()['negocio'] : 0;
    $tipocasa_selected = ($result = $conn->query("SELECT tipocasa FROM imoveis WHERE id =" . $imovel_id)) ? $result->fetch_assoc()['tipocasa'] : 0;
    $tipologia_selected = ($result = $conn->query("SELECT tipologia FROM imoveis WHERE id = " . $imovel_id)) ? $result->fetch_assoc()['tipologia'] : 0;
    $listaimoveis = ($result = $conn->query('SELECT * FROM imoveis WHERE user = ' . $user_id)) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $imoveis_selected = ($result = $conn->query('SELECT * FROM imoveis WHERE angariacao IN (SELECT angariacao FROM imoveis WHERE id=' . $imovel_id . ') AND angariacao <> 0')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
    $angariacao = $conn->query('SELECT angariacao FROM imoveis WHERE id=' . $imovel_id)->fetch_assoc()['angariacao'];
    $max_angariacao = $conn->query('SELECT MAX(angariacao) as themax FROM imoveis')->fetch_assoc()['themax'];
    $action = 1;

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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css" 
			  integrity="sha256-JHRpjLIhLC03YGajXw6DoTtjpo64HQbY5Zu6+iiwRIc=" crossorigin="anonymous" />
        
        <script>

            function readURL(input) {
                if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#image_file_group").val();
                filename = filename.substring(filename.lastIndexOf('\\')+1);
                reader.onload = function(e) {
                    $('#image_preview').css('background-image', "url(" + e.target.result + ")");
                }
                reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).ready(function () {
                
                var mainid = <?php echo (isset($_GET['id'])) ? $_GET['id'] : 0; ?>;

                //------------------------------------------------------ TAB CONTROLLER -----------------------------------------------------------//
                //---------------------------------------------------------------------------------------------------------------------------------//
                var selected_tab = <?php echo (isset($_GET['tab'])) ? $_GET['tab'] : 0; ?>;
                switch(selected_tab) {

                case 0: break;
                case 1: $('#detalhes-tab').click(); break;
                case 2: $('#ficheiros-tab').click(); break;
                case 3: $('#entidades-tab').click(); break;
                case 4: $('#angariacao-tab').click(); break;
                }




                //-------------------------------------------------------- TAB DETALHES -----------------------------------------------------------//
                //---------------------------------------------------------------------------------------------------------------------------------//
                function new_number_func(num){
                var str = num.toString().replace("$", "");
                parts = false;
                output = [];
                i = 1;
                formatted = null;
                if (str.indexOf(".") > 0) {
                    parts = str.split(".");
                    str = parts[0];
                }
                str = str.split("").reverse();
                for (var j = 0, len = str.length; j < len; j++) {
                    if (str[j] != ",") {
                    output.push(str[j]);
                    if (i % 3 == 0 && j < (len - 1)) {
                        output.push(",");
                    }
                    i++;
                    }
                }
                formatted = output.reverse().join("");
                return (formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
                }


                $("#image_file_group").change(function(event) {
                    readURL(this);
                });
                
                $('#domvalor2').val( new_number_func($('#domvalor2').val()));
                $('#domvalor3').val( new_number_func($('#domvalor3').val()));
                $('#domvalor').keyup(function () {
                    var txt1 = this.value;
                    var new_number = new_number_func(txt1);
                    this.value = new_number;
                });
                $('#domvalor2').keyup(function () {
                    var txt1 = this.value;
                    var new_number = new_number_func(txt1);
                    this.value = new_number;
                });
                $('#domvalor3').keyup(function () {
                    var txt1 = this.value;
                    var new_number = new_number_func(txt1);
                    this.value = new_number;
                });
                $('#domvalor').trigger("keyup");




                //-------------------------------------------------------- TAB ENTIDADES -----------------------------------------------------------//
                //---------------------------------------------------------------------------------------------------------------------------------//
                $('#contatos_table').DataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "paging": true,
                    "bInfo": false

                });

                /*$('.contato_click').dblclick(function(){
                    window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
                });*/




                //------------------------------------------------------- TAB ANGARIACAO ----------------------------------------------------------//
                //---------------------------------------------------------------------------------------------------------------------------------//
                $('#add_li').hide();

                $('#add_owner').click(function () {
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

                $('#add_photo').click(function () {
                    if ($(this).hasClass('fa-plus')) {
                    $('#add_li_ph').show();
                    $(this).removeClass('fa-plus');
                    $(this).addClass('fa-minus');
                    } else {
                    $('#add_li_ph').hide();
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
                    if ($(this).css('color') === 'rgb(0, 128, 0)') {
                    $.ajax({
                        url: "_controlador_imovel.php",
                        method: 'POST',
                        async: false,
                        data: {
                        'action_type' : 1, //relate imovel and entity
                        'entidade': sel,
                        'imovel': <?php echo $imovel_id;?>
                        },
                        success: function (result) {
                        swal({
                            title: "Nova entidade associada!",
                            icon: "success"
                        }).then(
                            function() {
                                var origin = window.location.href.split('?')[0] + '?id=' + mainid + '&tab=3'; 
                                window.location = origin;
                            })
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                        }
                    });
                    }
                });



                $('.ang_deletefile').click(function(){
                    var thefileid = $(this).data('fileid');
                    swal({
                    title: "Tem a certeza?",
                    text: "Depois de apagado, não será possível recuperar este ficheiro!",
                    icon: "warning",
                    buttons: ["Cancelar", "Apagar"],
                    dangerMode: true,
                    }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                        type: "POST",
                        url: "_delete_ang_file.php",
                        data: {
                            id_delete: thefileid
                        },
                        cache: false,
                        success: function (data) {
                            swal({
                            text: "Registo apagado com sucesso! ",
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



















                //---photo---//
                $('#image_file_group').change(function () {
                    var saveit = $('#savenewphoto');
                    saveit.css('color', 'green');
                    saveit.css('cursor', 'pointer');
                    $('#image_preview').css('background-image', "url('img/janey-e.jpg')");
                });

                $('#savenewphoto').click(function () {
                    swal({
						title: "Adicionar esta imagem ao imóvel?",
						icon: "warning"
                    }).then((willÂdd) => {
                    	$( "#imgform" ).submit();
                    });
                });
                //---photo---//



                $('.delcontato').click(function () {
                    var sel = $(this).data('idc');
                    $.ajax({
                    url: "_controlador_imovel.php",
                    method: 'POST',
                    async: false,
                    data: {
                        'action_type' : 2,
                        'entidade': sel,
                        'imovel': <?php echo $imovel_id;?>
                    },
                    success: function (result) {
                        swal({
                            title: "Relação apagada.",
                            icon: "warning"
                        }).then(
                            function() {
                            location.reload();
                            })
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                    });
                });



                $('#delbtn').click(function(){
                    swal({
                    title: "Tem a certeza?",
                    text: "Depois de apagado, não será possível recuperar este registo!",
                    icon: "warning",
                    buttons: ["Cancelar", "Apagar"],
                    dangerMode: true,
                    }).then((willDelete) => {
                    if (willDelete) {

                        var theid = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; } ?>;

                        $.ajax({
                        type: "POST",
                        url: "_delete.php",
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
                            location.replace('listaimoveis.php');
                            });
                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                            alert('Error: ' + errorMessage);
                        }
                        });
                    }
                    });
                });

                var newitem = <?php echo (!isset($_GET['id'])) ? 1 : 0; ?>;
                

                if(!newitem) {
                    $('#submitbtn').hide();
                    $('#delbtn').hide();
                }
                else {
                    $('#delbtn').hide();
                    $('#editbtn').hide();
                    $('#ficheiros-tab').hide(); 
                    $('#entidades-tab').hide(); 
                    $('#angariacao-tab').hide(); 
                    $('#fotografias').hide();
                }


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

                $('#submitbtn').click(function(){
                    $("#subjectForm").trigger('submit');
                });


                $('#domaddr').keyup(function(){
                    var que = $(this).val();
                    que.replace(" ", "+");
                    $('#mapslink').attr("href", "https://maps.google.com/maps?q=" + que);
                    $('#gmap_canvas').attr("src", "https://maps.google.com/maps?q=" + que + "&t=&z=15&ie=UTF8&iwloc=&output=embed");
                });

                $('#domaddr').trigger("keyup");

                $('#image_file_group').on('change',function(){
                    //get the file name
                    var fileName = $(this).val();
                    var cutoff;

                    if(fileName.length<40) {
                    cutoff = fileName.length;
                    }
                    else {
                    cutoff = 40;
                    }

                    var fileNameOnly = fileName.substring(12, cutoff);
                    //replace the "Choose a file" label
                    $(this).next('.custom-file-label').html(fileNameOnly);
                });

                $('.deletenewphoto').click(function(){
                    var thephotoid = $(this).data('photoid');
                    swal({
                    title: "Tem a certeza?",
                    text: "Depois de apagado, não será possível recuperar esta foto!",
                    icon: "warning",
                    buttons: ["Cancelar", "Apagar"],
                    dangerMode: true,
                    }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                        type: "POST",
                        url: "_delete_subject_image.php",
                        data: {
                            'id_delete': thephotoid,
                            'id_subject': <?php if(isset($_GET['id'])) { echo '"' . $_GET['id'] . '"'; } else { echo '"' . 0 . '"'; } ?>
                        },
                        cache: false,
                        success: function (data) {
                            swal({
                            text: "Registo apagado com sucesso! ",
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




                //-------------------------------------------------------------------------------------------------------------------------------------//
                //-----------------------------------------------------FICHEIROS---IMÓVEL--------------------------------------------------------------//

                    $('#input_card_group').hide();

                    $('#addfile').click(function(){
                    if($('#input_card_group').is(":visible")) {
                        $('#input_card_group').slideUp();
                        $('#addfile').addClass('fa-plus');
                        $('#addfile').removeClass('fa-minus');
                    }
                    else {
                        $('#input_card_group').slideDown();
                        $('#addfile').removeClass('fa-plus');
                        $('#addfile').addClass('fa-minus');
                    }
                    });

                    function able_file(){
                    //alert('comparing "' + $('#btn_tipo').text().trim() + '" and  "Tipo de ficheiro"');
                    //alert('comparing "' + $('#filenamedisplay').text() + '" and  "Ficheiro"');
                    if($('#filenamedisplay').text() != 'Ficheiro' && $('#ifiletype').text().trim() != "Tipo de ficheiro") {
                        $('#upload_file').css('color', 'green');
                        $('#upload_file').css('cursor', 'pointer');
                    }
                    }

                    $( "#ifile" ).change(function() {
                    var nome = $(this).val().split('\\').pop().substr(0,15);
                    $('#filenamedisplay').text(nome);
                    able_file();
                    });

                    $('#upload_file').click(function () {
                    if($(this).css('color')==='rgb(0, 128, 0)') {
                        $('#documents_div').submit();
                    }
                    });

                    $('.deletefile').click(function(){
                        var thefileid = $(this).data('fileid');
                        swal({
                            title: "Tem a certeza?",
                            text: "Depois de apagado, não será possível recuperar este ficheiro!",
                            icon: "warning",
                            buttons: ["Cancelar", "Apagar"],
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                            $.ajax({
                                type: "POST",
                                url: "_delete_subject_file.php",
                                data: {
                                id_delete: thefileid
                                },
                                cache: false,
                                success: function (data) {
                                swal({
                                    text: "Registo apagado com sucesso! ",
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


            

                //-------------------------------------------------------------------------------------------------------------------------------------//
                //-------------------------------------------------------------------------------------------------------------------------------------//








                    $('.completed_check').click(function () {
                        var contato = $(this).data('id');
                        var state = 0;
                        var resp = 0;
                        if ($(this).prop("checked")) {
                        state = 1;
                        }
                        $.ajax({
                        url: '../contato/contato_change_state.php',
                        type: 'post',
                        async: false,
                        data: {
                            'contato': contato,
                            'state': state
                        },
                        success: function (data, textStatus, jQxhr) {
                            if(data==0) {
                            swal({
                                text: "Pendente!",
                                icon: "warning",
                                }).then(
                                function() {
                                location.reload();
                                })
                            }
                            else {
                            swal({
                                text: "Terminado!",
                                icon: "success",
                                }).then(
                                function() {
                                location.reload();
                                })
                            }

                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                        });
                        $(this).closest('tr').toggleClass('to_complete');
                    });










                    //-------------------js adicionar ficheiro-------------------//

                    $('#image_file_group').on('change',function(){
                    //get the file name
                    var fileName = $(this).val();
                    var cutoff;

                    if(fileName.length<40) {
                        cutoff = fileName.length;
                    }
                    else {
                        cutoff = 40;
                    }

                    var fileNameOnly = fileName.substring(12, cutoff);
                    //replace the "Choose a file" label
                    $(this).next('.custom-file-label').html(fileNameOnly);
                    });



                $('#input_card_group2').hide();

                $('#addfilebutton2').click(function(){
                    if($('#input_card_group2').is(":visible")) {
                    $('#input_card_group2').slideUp();
                    $('#addfile2').addClass('fa-plus');
                    $('#addfile2').removeClass('fa-minus');
                    }
                    else {
                    $('#input_card_group2').slideDown();
                    $('#addfile2').removeClass('fa-plus');
                    $('#addfile2').addClass('fa-minus');
                    }
                });






                //----------------------------------------------------------------------------------------------------------------------------------------------//
                //----------------------------------------------------------------------------------------------------------------------------------------------//

                //                                                            ANG HELP

                $('#history').DataTable({
                    "scrollY": "700px",
                    "scrollCollapse": true,
                    "paging": false,
                    "bInfo" : false
                });

                $('#delbtn').click(function(){
                    swal({
                        title: "Tem a certeza?",
                        text: "Depois de apagado, não será possível recuperar este contato!",
                        icon: "warning",
                        buttons: ["Cancelar", "Apagar"],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {

                        var theid = <?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; }?>;

                        $.ajax({
                        type: "POST",
                        url: "_delete.php",
                        data: {
                        id_delete: theid,
                        the_type: 1
                        },
                        cache: false,
                        success: function (data) {
                        swal({
                            text: "Contato apagado com sucesso! " + data,
                            icon: "success",
                        }).then(function () {
                            location.replace('listaimoveis.php');
                        });
                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                        alert('Error: ' + errorMessage);
                        }
                        });
                        }
                    });
                    });


                    $('.completed_check').click(function () {
                    var contato = $(this).data('id');
                    var state = 0;
                    var resp = 0;
                    if ($(this).prop("checked")) {
                        state = 1;
                    }
                    $.ajax({
                        url: 'contato_change_state.php',
                        type: 'post',
                        async: false,
                        data: {
                        'contato': contato,
                        'state': state
                        },
                        success: function (data, textStatus, jQxhr) {
                        if(data==0) {
                            swal({
                                text: "Pendente!",
                                icon: "warning",
                            }).then(
                            function() {
                                location.reload();
                            })
                        }
                        else {
                            swal({
                                text: "Terminado!",
                                icon: "success",
                            }).then(
                            function() {
                                location.reload();
                            })
                        }

                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                        alert(errorThrown);
                        }
                    });
                    $(this).closest('tr').toggleClass('to_complete');
                    });


                    $('.contato_click').click(function(){
                        //window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
                        //alert($(this).data('contatoid'));
                        var idcontato = $(this).data('contatoid');
                        var detailscontato;


                        $.ajax({
                            url: "getcontato_modal.php",
                            method: 'POST',
                            async: false,
                            dataType: "json",
                            data: {
                                   'id':idcontato
                            },
                            success: function(result) {
                                   detailscontato = result;
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                    alert(xhr.status);
                                    alert(thrownError);
                            }
                        });

                        $('#modal_contato').modal('toggle');

                        $('#modal_title').text($(this).children('td').eq(1).text());
                        $('#agendado_d').val(detailscontato.d_agendado);
                        $('#agendado_t').val(detailscontato.t_agendado);
                        $('#modal_descricao').text(detailscontato.descricao);
                        $('#modal_estado_input').prop('checked', (detailscontato.estado==1 ? true: false));
                        $('#estado_label').text((detailscontato.estado==1 ? 'Completo': 'Por completar'));
                        $('#timestamp_completed').val(detailscontato.completado);
                        $('#modal_tipos_contato option').prop('selected', false);
                        $('#modal_tipos_contato option[value="' + detailscontato.tipo + '"]').prop('selected', true);
                    });

                    $('#add_imovel_card').hide();
                    $('#add_imovel').click(function() {
                    if ($(this).hasClass('fa-plus')) {
                        $('#add_imovel_card').show();
                        $(this).removeClass('fa-plus');
                        $(this).addClass('fa-minus');
                    } else {
                        $('#add_imovel_card').hide();
                        $(this).addClass('fa-plus');
                        $(this).removeClass('fa-minus');
                    }
                    });

                    $('#imovelassoc').change(function() {
                        var saveit = $('#savenewimovelassoc');
                        saveit.css('color', 'green');
                        saveit.css('cursor', 'pointer');
                    });

                    $('#savenewimovelassoc').click(function() {
                        var sel = $("#imovelassoc option:selected").val();
                        var ang = <?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>;
                        if (sel != null) {
                        if ($(this).css('color') === 'rgb(0, 128, 0)') {
                            $.ajax({
                                url: "_controlador_angariacao.php",
                                method: 'POST',
                                async: false,
                                data: {
                                    'action_type': 10,
                                    'id_imovel': sel,
                                    'id':ang
                                },
                                success: function(result) {
                                if(result==1) {
                                    swal({
                                    text: "Imóvel adicionado!",
                                    icon: "success",
                                    }).then( function() { location.reload(); });
                                }
                                else {
                                    swal({
                                    text: "Tem que descrever e salvar o contato antes de adicionar entidades ou imoveis.",
                                    icon: "warning",
                                    })
                                }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(xhr.status);
                                    alert(thrownError);
                                }
                            });
                        }
                        }
                    });

                    $('.deletenewimovelassoc').click(function() {
                        var sel = $(this).data('imo');
                        var ang = <?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>;
                        if (sel != null) {
                        $.ajax({
                            url: "_controlador_angariacao.php",
                            method: 'POST',
                            data: {
                            'action_type': 11,
                            'id_imovel': sel,
                            'id': ang
                            },
                            success: function(result) {
                            if(result==1) {
                                swal({
                                    text: "Imóvel removido!",
                                    icon: "success",
                                }).then( function() { location.reload(); });
                                }
                                else {
                                swal({
                                    text: "Ocorreu um erro.",
                                    icon: "warning",
                                })
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                            }
                        });
                        }
                    });






                    //-------------------js adicionar entidade-------------------//

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

                    $('.delentidade').click(function () {
                    var sel = $(this).data('ide');
                    $.ajax({
                        url: "_controlador_angariacao.php",
                        method: 'POST',
                        async: false,
                        data: {
                        'action_type' : 31,
                        'id_entidade': sel,
                        'id': <?php echo $id;?>
                        },
                        success: function (result) {
                        swal({
                            title: "Relação apagada.",
                            icon: "warning"
                            }).then(
                            function() {
                            location.reload();
                            })
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                        }
                    });
                    });

                    $('#add_li').hide();

                    $('#domentidadeid').change(function () {
                    var saveit = $('#savenewentity');
                    saveit.css('color', 'green');
                    saveit.css('cursor', 'pointer');
                    });

                    $('#savenewentity').click(function () {
                    var sel = $("#domentidadeid option:selected").val();
                    if ($(this).css('color') === 'rgb(0, 128, 0)') {
                        $.ajax({
                        url: "_controlador_angariacao.php",
                        method: 'POST',
                        async: false,
                        data: {
                            'action_type' : 30, //relate imovel and entity
                            'id_entidade': sel,
                            'id': <?php echo $id;?>
                        },
                        success: function (result) {
                            swal({
                            title: "Nova entidade associada!",
                            icon: "success"
                            }).then(
                            function() {
                                location.reload();
                            })
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                        });
                    }
                    });



                    //-------------------js adicionar ficheiro-------------------//

                    $('#image_file_group').on('change',function(){
                        //get the file name
                        var fileName = $(this).val();
                        var cutoff;

                        if(fileName.length<40) {
                        cutoff = fileName.length;
                        }
                        else {
                        cutoff = 40;
                        }

                        var fileNameOnly = fileName.substring(12, cutoff);
                        //replace the "Choose a file" label
                        $(this).next('.custom-file-label').html(fileNameOnly);
                    });


                    //IF GET['idimovel] THEN AUTOMATICALLY CREATE THE HOUSE RAISING
                    // that is done in jquery

                    var automatic = <?php echo (isset($_GET['idimovel'])) ? $_GET['idimovel'] : 0; ?>;
                    if(automatic!=0) {
                        $.ajax({
                        url: '_automatic.php',
                        type: 'post',
                        async: false,
                        data: {
                            'action_type' : 90,
                            'idimovel': automatic
                        },
                        success: function (data, textStatus, jQxhr) {
                            location.replace('angariacao.php?id=' + data);
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                        });
                    }














                //----------------------------------------------------------------------------------------------------------------------------------------------//
                //----------------------------------------------------------------------------------------------------------------------------------------------//

                //1 - create      2 - edit
                var newitem2 = <?php echo $action; ?>;

                if(newitem2==2) {
                    $('#angsave').hide();
                    $('#angdel').hide();
                }
                else {
                    $('#angdel').hide();
                    $('#angedit').hide();
                }


                $('#angedit').click(function(){
                if($('#angsave').is(":hidden")) {
                    $('#angsave').show();
                    $('#angdel').show();
                    $(this).text('Cancelar');
                }
                else{
                    $('#angsave').hide();
                    $('#angdel').hide();
                    $(this).text('Editar');
                }
                });

                $('#angsave').click(function(){
                    $('#file_form').submit();
                });

                $('#input_card_group_ang').hide();

                $('#addfilebutton_ang').click(function(){
                if($('#input_card_group_ang').is(":visible")) {
                    $('#input_card_group_ang').slideUp();
                    $('#addfile_ang').addClass('fa-plus');
                    $('#addfile_ang').removeClass('fa-minus');
                }
                else {
                    $('#input_card_group_ang').slideDown();
                    $('#addfile_ang').removeClass('fa-plus');
                    $('#addfile_ang').addClass('fa-minus');
                }
                });

                $('#upload_file_ang').hide();

                function able_file_ang(){
                    //alert('comparing "' + $('#btn_tipo').text().trim() + '" and  "Tipo de ficheiro"');
                    //alert('comparing "' + $('#filenamedisplay').text() + '" and  "Ficheiro"');
                    if($('#filenamedisplay_ang').text() != 'Ficheiro' && $('#btn_tipo_ang').text().trim() != "Tipo de ficheiro") {
                        $('#upload_file_ang').show();
                        $('#upload_file_ang').css('background-color', 'green');
                        $('#upload_file_ang').css('cursor', 'pointer');
                        $('#addfilebutton_ang').hide();
                    }
                }




                $( "#ifile_ang" ).change(function() {
                    var nome = $(this).val().split('\\').pop().substr(0,15);
                    $('#filenamedisplay_ang').text(nome);
                    able_file_ang();
                });

                $('#upload_file_ang').click(function () {
                    if($(this).css('color')==='rgb(0, 128, 0)') {
                        $('#file_form').submit();
                    }
                });



                $('#publish').click(function(){
                    if($(this).hasClass('btn-dark')) {
                        $.ajax({
                            url: "publish.php",
                            method: 'POST',
                            async: false,
                            data: {
                            'imovel': <?php echo $imovel_id;?>
                            },
                            success: function (result) {
                            swal({
                                title: "Publicado na rede KW!",
                                icon: "success"
                            }).then(
                                function() {
                                location.reload();
                                })
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                            }
                        });
                    }
                });



                //-----------------------------------------------//
                $('#domabitazione').select2({
                    width: '100%'
                });


                $('#domabitazione').on('select2:select', function (e) {
                    var ang = <?php echo (isset($angariacao)) ? $angariacao : 0; ?>;
                    var max_ang = <?php echo (isset($max_angariacao)) ? $max_angariacao : 0; ?>;
                    swal({
                        title: "Juntar um imovel a esta angariacao?",
                        icon: "warning"
                    }).then( function() {
                        var selected_ang=0;
                        var newer = 0;
                        if(ang==0) {
                            selected_ang = max_ang + 1;
                            newer = 1;
                        }
                        else {
                            selected_ang = ang;
                        }
                        
                        var sel = $("#domabitazione option:selected").val();

                        $.ajax({
                            url: "_angariacao.php",
                            method: 'POST',
                            async: false,
                            data: {
                                'action_type' : 1, //relate imovel and entity
                                'angariacao' : selected_ang,
                                'imovel2': sel,
                                'imovel1': <?php echo $imovel_id;?>
                            },
                            success: function (result) {
                                if(result==='-1') {
                                    swal({
                                        title: "Esse imóvel já se encontra num contrato de angariacao.",
                                        icon: "error"
                                    })
                                }
                                else {
                                    var origin = window.location.href.split('?')[0] + '?id=' + mainid + '&tab=4'; 
                                    window.location = origin;
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                            }
                        });
                    });
                });

                $('.delimo').click(function(){
                    var idimovel = $(this).data('idimo');
                    var angariacao = $(this).data('idang');
                    
                    swal({
                        title: "Remover este imóvel desta angariação?",
                        icon: "warning"
                    }).then( function() {
                        $.ajax({
                            url: "_angariacao.php",
                            method: 'POST',
                            async: false,
                            data: {
                                'action_type' : 2,
                                'angariacao' : angariacao,
                                'imovel': idimovel
                            },
                            success: function (result) {
                                if(result==='-1') {
                                    swal({
                                        title: "Impossível apagar.",
                                        icon: "error"
                                    })
                                }
                                else {
                                    location.reload();
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                            }
                        });
                    });
                });

                function copyToYou() {
                    var copyText = document.getElementById("angariacao");
                    copyText.select();
                    document.execCommand("copy");
                    alert("Copied the text: " + copyText.value);
                } 

                $('[data-toggle="tooltip"]').tooltip();   

                $('#criar_ang').click(function(){
                    var ang = <?php echo (isset($angariacao)) ? $angariacao : 0; ?>;
                    var max_ang = <?php echo $max_angariacao + 1; ?>;
                    var sel = $("#domabitazione option:selected").val();
                    

                    if(!$(this).hasClass('btn-primary') && $('#angariacao').val()!=='0') {
                        $.ajax({
                            url: "_angariacao.php",
                            method: 'POST',
                            async: false,
                            data: {
                                'action_type' : 4, //relate imovel and entity
                                'imovel1': <?php echo $imovel_id;?>
                            },
                            success: function (result) {
                                swal({
                                    title: "Nova angariação criada!",
                                    icon: "success"
                                }).then( function() {
                                    var origin = window.location.href.split('?')[0] + '?id=' + mainid + '&tab=4'; 
                                    window.location = origin;
                                });
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status);
                                alert(thrownError);
                            }
                        });
                    }
                    else {
                        var value = $('#angariacao_input').val();
                        var $temp = $("<input>");
                        $("body").append($temp);
                        $temp.val(value).select();
                        document.execCommand("copy");
                        $temp.remove();
                        $(this).text("Copiado");

                    }                    
                });

                $('#destruir_ang').click(function(){ 
                    $.ajax({
                        url: "_angariacao.php",
                        method: 'POST',
                        async: false,
                        data: {
                            'action_type' : 5, //relate imovel and entity
                            'imovel1': <?php echo $imovel_id;?>
                        },
                        success: function (result) {
                            swal({
                                title: "Angariação apagada!",
                                icon: "success"
                            }).then( function() {
                                var origin = window.location.href.split('?')[0] + '?id=' + mainid + '&tab=4'; 
                                window.location = origin;
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                    });
                });

                $('#agendado_h_picker').datetimepicker({
                    format: 'YYYY-MM-DD'
                });

                $('#agendado_t_picker').datetimepicker({
                    format: 'HH:mm'
                });


                if($('#estado_input').is(":checked")) {
                    $('#estado_label').html('Completo');
                }
                else {
                    $('#estado_label').html('Por completar');
                }

                $('#estado_input').click(function(){
                    if($(this).is(":checked")) {
                        var dt = new Date();
                        $('#estado_label').html('Completo');
                        $('#timestamp_completed').val(dt.toJSON().slice(0,10).replace(/-/g,'/') + ' ' + dt.toJSON().slice(11,19));
                    }
                    else {
                        $('#estado_label').html('Por completar');
                        $('#timestamp_completed').val('');
                    }
                });
                

                $('.sorting_asc').first().dblclick();
					
					
				function zipping() {
					var dist = '';
					var conc = '';
					var fregs = [];
					if($('#zip1').val().length ==4) {
						var zipinput1 = $('#zip1').val();
						var zipinput2 = $('#zip2').val();
						
						$.ajax({
							type: 'post',
							dataType: 'json',
							url: "moradas.php",
							async: false,
							data: {
								zip1: zipinput1,
								zip2: zipinput2
							},
							success: function(data, status){
								//alert("Data: " + data.toSource() + "\nStatus: " + status);
								dist = data[0].nome;
								conc = data[1].Designacao;
								//fregs = data[2];
								//alert(data.toSource());
							},
							 error: function (xhr, ajaxOptions, thrownError) {
								 alert(xhr.status);
								 alert(thrownError);
							 }
						});
						
						/*
						var string_options = '';
						var paar = 0;
						var string_aux = '';
						$.each( fregs, function( index, value ){
							alert(value);
							
							if(paar == 0) {
								string_aux = value + '</option>';
							}
							else {
								paar=0;
								string_options += '<option val="' + value + '" class="addoption">' + string_aux;
								alert(string_options);
							}
						});
 						*/
						
						//alert(dist);
						$('#domdist').val(dist);
						$('#domlocal').val(conc);
						/*$('.addoption').remove();
						$('#domfreg').append(string_options);*/
					}
				}		
			
				$('#zip1').keyup(function(){
					zipping();
					//domdist
				});
					
				$('#zip2').keyup(function(){
					zipping();
					//domdist
				});
					
				$('#zip1').trigger('keyup');
					
				$('#zip2').trigger('keyup');
					
				$('#sidebarToggle').trigger('click');
					
				if(<?php echo ((isset($estado_selected) AND $estado_selected==100) ? '1' : '2'); ?> == '1') {
					$('#domref').dblclick(function(){
						Swal.fire({
							title: 'Tem a certeza?',
							text: "O registo vai ser PERMANENTEMENTE apagado",
							type: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Sim, apagar!'
						}).then((result) => {
							if (result.value) {
								$.post("_delete.php",
								{
									id_permanent_delete: $(this).val()
								},
								function(data, status){
									alert("Data: " + data + "\nStatus: " + status);
								});
								location.replace('listaimoveis.php');
							}
						});
					});
						
				}
					
				

            });
        </script>
        
        <style>
            .deletefile:hover {
            color: darkred;
            cursor: pointer;
            }

            .ang_deletefile:hover {
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

            #addfile:hover {
            color: blue;
            cursor: pointer;
            }

            #addfilebutton_ang:hover {
            color: blue;
            cursor: pointer;
            }

            .filetype_option:hover {
            cursor: pointer;
            }

            .tab-pane{
            min-height:58vh;
            }

            .report_click:hover {
            background-color: white !important;
            cursor: pointer;
            }

            .datetimebox {
            width: 500px;
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

            .deletefile:hover {
            color:darkred;
            cursor:pointer
            }

            .circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border-color: white;
            font-size: small;
            color: white;
            line-height: 35px;
            text-align: center;
            background-color: #c5c5c5;
            display: inline-block;
            margin-right:10px;
            }

            .downloadfile{
                padding-right:1vh;
            }

            .downloadfile:hover{
                color:darkblue;
                cursor:pointer;
            }

            .select2-container .select2-selection--single {
                height: 4.5vh;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 4.5vh;
            }

            #criar_ang:hover {
                background-color:#4e73df;
                cursor:pointer;
            }

            .contato_click  {
                cursor: pointer;
            }

            .contato_click:hover  {
                background-color: pointer;
            }

        </style>

    </head>


    <body id="page-top">
        <div id="wrapper">
            <?php include '../partials/sidebar.php'; ?>
            <div id="content-wrapper" class="d-flex flex-column">





                <div id="content">
                    <?php include '../partials/topbar.php'; ?>
                    <div class="container-fluid">

                        <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?></h1>


                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                            <a class="nav-link active" id="detalhes-tab" data-toggle="tab" href="#detalhes" role="tab" aria-controls="detalhes" aria-selected="true">Detalhes</a>
                            </li>
                            <li class="nav-item" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>
                            <a class="nav-link" id="ficheiros-tab" data-toggle="tab" href="#ficheiros" role="tab" aria-controls="ficheiros" aria-selected="false">Ficheiros</a>
                            </li>
                            <li class="nav-item" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>
                            <a class="nav-link" id="entidades-tab" data-toggle="tab" href="#entidades" role="tab" aria-controls="entidades" aria-selected="false">Entidades</a>
                            </li>
                            <li class="nav-item" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>
                            <a class="nav-link" id="angariacao-tab" data-toggle="tab" href="#angariacao" role="tab" aria-controls="angariacao" aria-selected="false">Angariação</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">


                            <div class="tab-pane fade show active" id="detalhes" role="tabpanel" aria-labelledby="detalhes-tab">
                                <div class="row">

                                    <form id="subjectForm" action="_imovel.php" method="POST" style="" class="border-left-primary m-lg-2 col-8 row">

                                        <div class="<?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'col-6' : 'col'); ?>" style="padding-top:4vh;" >

                                        <input type="text" id="domid" name="domid" value="<?php echo $imovel_id; ?>" hidden>
                                        <input type="text" id="domuser" name="domuser" value="<?php echo $user_id; ?>" hidden>

                                        <div class="form-group">
                                            <label for="domtitle">Nome</label>
                                            <input type="text" class="form-control" id="domtitle" name="domtitle"
                                            value="<?php echo $imovel_title;?>" required>
                                        </div>

											<div class="row">

												<div class="form-group col-6">
													<label for="domkwid">ID Imobiliária</label>
													<input type="text" class="form-control" id="domkwid" name="domkwid"
														   value="<?php echo $imovel_kwid;?>" required>
												</div>

												<br>

												<div class="form-group col-6">
													<label for="domref">ID</label>
													<input type="text" class="form-control" id="domref" name="domref" value="<?php echo $imovel_ref;?>"
														   required readonly>
												</div>

											</div>

											<div class="form-group">
												<div class="row">
													<label for="domaddr" style="margin-left:2.4vh;">Morada</label>
													<a id="mapslink" target="_blank" style="float:right;margin-left:20px">
														<i class="fas fa-map"></i><span style="margin-left:10px"> Google Maps </span>
													</a>
												</div>
											</div>
											

											
											







											<div class="form-group row">
												<label for="domlocal"  style="margin-left:9vw; margin-right:1vw; padding-top:0.5vh;text-align:right;">Código postal:</label>
												<input class="form-control col-3" style="text-align: right;" id="zip1" name="domzip1" type="text"
													   maxlength="4" value="<?php echo $imovel_zip1; ?>">
												<span style="font-size:3vh; margin-left:0.3vw;margin-right:0.3vw; "> - </span>
												<input class="form-control " style=" width:4vw;" id="zip2" name="domzip2" type="text" maxlength="3"
													   value="<?php echo $imovel_zip2;?>">
											</div>
											
											<div class="form-group">
												<input class="form-control" id="domdist" name="domdist" type="text"
														placeholder="Distrito" style="margin-bottom:1vh;right:0;padding-right:1vw;" value="" readonly>
											</div>

											<div class="form-group">
												<input class="form-control" id="domlocal" name="domlocal" type="text"
														placeholder="Localidade" style="margin-bottom:1vh;right:0;padding-right:1vw;" value="" readonly>
											</div>
											
											
											<div class="form-group" hidden>
												<select class="form-control" id="domfreg" name="domfreg" type="text"
														placeholder="Freguesia" value="" readonly>
													<option disabled selected id="disoption">Freguesia</option>
												</select>
											</div>
											
											<div class="form-group">
												<textarea type="text" class="form-control" id="domaddr" name="domaddr"
														  value="" style="height: 9vh;"><?php echo $imovel_domaddr;?></textarea >
											</div>

											
											


											




                                        <div class="form-group">
                                            <div class="mapouter" style="margin:14px">
                                            <div class="gmap_canvas"><iframe width="468" height="270" id="gmap_canvas"
                                                src="https://maps.google.com/maps?q=&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"
                                                scrolling="no" marginheight="0" marginwidth="0"></iframe>Google Maps Generator by <a
                                                href="https://www.embedgooglemap.net">embedgooglemap.net</a></div>
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


                                        <div class="form-group">
                                            <label for="domdescr">Descrição</label>
                                            <textarea type="text" class="form-control" id="domdescr" name="domdescr"
                                            value=""><?php echo $imovel_description;?></textarea>
                                        </div>

                                        <br>

                                        <button id="submitbtn" type="submit" class="btn btn-success" name="sbmtd">Salvar</button>
                                        <div id="delbtn" class="btn btn-danger" name="delbtn" style="cursor:pointer">Apagar</div>
                                        <div id="editbtn" class="btn btn-primary" name="editbtn" style="cursor:pointer" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>Editar</div>
                                        </div>

										<div class="col" style="padding-top:4vh;" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>
											<div class="row">
												<div class="col-6">
													<label for="domval_neg">Valor de Negócio</label><br><br>
													<label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">€</span>
														</div>
														<input id="domvalor" name="domvalor" type="text" style="text-align: right;" class="form-control"
															   aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value; ?>">
														<div class="input-group-append">
															<span class="input-group-text">.00</span>
														</div>
													</div>
													<br>
												</div>

												<div class="col-6">
													<label for="domval_co_contra">Valor de Comissão Contratado</label><br><br>
													<label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">€</span>
														</div>
														<input id="domvalor2" name="domvalor2" type="text" style="text-align: right;" class="form-control"
															   aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value2; ?>">
														<div class="input-group-append">
															<span class="input-group-text">.00</span>
														</div>
													</div>
													<br>
												</div>
											</div>

											<div class="row">
												<div class="col-6">
													<label for="domvalor">Valor de Comissão Cobrada</label>
													<label><span style="font-size:x-small">(Vazio singifica sob consulta)</span></label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">€</span>
														</div>
														<input id="domvalor3" name="domvalor3" type="text" style="text-align: right;" class="form-control"
															   aria-label="Amount (to the nearest dollar)" value="<?php echo $imovel_value3; ?>">
														<div class="input-group-append">
															<span class="input-group-text">.00</span>
														</div>
													</div>
													<br>
												</div>
											</div>

											<div class="form-group">
												<div class="row">
													<div class="col">
														<label for="checkstate">Estado</label>
														<select class="form-control" id="exampleFormControlSelect1" name="domestado">
															<?php
															$estados = ($result = $conn->query('SELECT * FROM estados')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
															foreach($estados as $estado) {
																echo '<option value="' . $estado['id'] . '"';
																if($estado_selected==$estado['id']) { 
																	echo 'selected'; 
																}
																echo '>' . $estado['nome'] . '</option>';
															}
															?>
														</select>
													</div>
													<div class="col">
														<label for="domnegocio">Negocio</label>
														<select class="form-control" id="exampleFormControlSelect2" name="domnegocio">
															<?php
															$negocios = ($result = $conn->query('SELECT * FROM negocios')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
															foreach($negocios as $negocio) {
																echo '<option value="' . $negocio['id'] . '"';
																if($negocio_selected==$negocio['id']) { 
																	echo 'selected'; 
																}
																echo '>' . $negocio['nome'] . '</option>';
															}
															?>
														</select>
													</div>
												</div>
											</div>




											<div class="form-group">
												<div class="row">
													<div class="col">
														<label for="domtipocasa">Tipo de lar</label>
														<select class="form-control" id="exampleFormControlSelect3" name="domtipocasa">
															<?php
															$tiposcasa = ($result = $conn->query('SELECT * FROM tiposcasa')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
															foreach($tiposcasa as $item) {
																echo '<option value="' . $item['id'] . '"';
																if($tipocasa_selected==$item['id']) { 
																	echo 'selected'; 
																}
																echo '>' . $item['nome'] . '</option>';
															}
															?>
														</select>
													</div>
													<div class="col">
														<label for="domtipologia">Tipologia</label>
														<select class="form-control" id="exampleFormControlSelect4" name="domtipologia">
															<?php
															$tipologias = ($result = $conn->query('SELECT * FROM tipologias')) ? $result->fetch_all(MYSQLI_ASSOC) : array();
															foreach($tipologias as $item) {
																echo '<option value="' . $item['id'] . '"';
																if($tipologia_selected==$item['id']) { 
																	echo 'selected'; 
																}
																echo '>' . $item['nome'] . '</option>';
															}
															?>
														</select>
													</div>
												</div>
											</div>




											<br><br>
											<?php 
											if($imovel_dist==1)
												echo '<button class="btn btn-dark" id="publish">Publicar na rede</button>';
											elseif($imovel_dist==2)
												echo '<div style="float:left;width:120px;" class="btn btn-success" id="publish" >PUBLICADO</div>';
											?>






										</div>

									</form>


									<div class="m-lg-2 col " style="padding-top:4vh;" id="fotografias" <?php echo ((isset($estado_selected) AND $estado_selected==100) ? 'hidden' : ''); ?>>
										<span style="font-size:3vh;">Fotografias</span>
										<i class="fa fa-plus" id="add_photo"></i>
										<div class="card" style="margin-top:1vh;">
											<div class="card-header">
												Nomes e miniaturas
											</div>
											<ul class="list-group list-group-flush">
                                            <li class="list-group-item" id="add_li_ph" style="display: none;">


                                            <!----------------------------IMAGE FORM---------------------------------->
                                            <!--    subjectid   subject_file                                        -->
                                            <!-------------------------------------------------------------------------->
                                            <form name="imgform" enctype="multipart/form-data" id="imgform" method="POST"
                                                action="_upload_subject_image.php">
                                                <input name="subjectid" type="text" value="<?php echo $imovel_id;?>" hidden>
                                                <div class="row">
                                                    <div class="input-group">
                                                    <div class="custom-file">
                                                        <input name="subject_file" type="file" class="custom-file-input" id="image_file_group" />
                                                        <label class="custom-file-label" for="image_file_group">Selecione</label>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:5px">
                                                    <div class="input-group col card" style="padding:0;">
                                                    <div id="image_preview"
                                                        style="height:12vh; width:94%; background-size:cover; background-position:center; background-image:url('img/default.jpg'); margin:auto; margin-top:5px; margin-bottom:5px">
                                                    </div>
                                                    </div>
                                                    <div class="input-group col">
                                                    <div class="font-weight-bold text-uppercase"
                                                        style="color:gray; width:4vw; margin:auto; text-align:center;">
                                                        <i class="fas fa-check-circle" id="savenewphoto" style="height:3vh; font-size:3.2vh;"></i>
                                                    </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-------------------------------------------------------------------------->


                                            </li>
                                            <?php
                                                $counter_photos=0;
                                                foreach($photolist as $photo) {
                                                $counter_photos++;
                                                echo '
                                                <li class="list-group-item" id="add_li_ph">
                                                    <div class="row" style="margin-top:5px">
                                                    <div class="input-group col card" style="padding:0;">
                                                        <div id="image_preview" style="height:12vh; width:94%; background-size:cover; background-position:center; ';
                                                echo 			"background-image:url('../../img/uploads/imoveis/" . $photo['url'] . "');";
                                                echo 			' margin:auto; margin-top:5px; margin-bottom:5px">';
                                                echo '
                                                        </div>
                                                    </div>
                                                    <div class="input-group col">
                                                        <span class="badge badge-pill badge-light" style="padding-top:45%">'.$counter_photos.'</span>
                                                        <div class="font-weight-bold text-uppercase" style="color:red; width:4vw; margin:auto; text-align:center;">
                                                        <i data-photoid="' . $photo['id'] . '" class="fas fa-trash deletenewphoto" style="height:1vh; font-size:2vh;">
                                                        </i>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </li>';
                                                }
                                            ?>
                                        </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="ficheiros" role="tabpanel" aria-labelledby="ficheiros-tab">
                                <!----------------------------------------------------ADD A FILE------------------------------------------------>
                                <form id="documents_div" class="col-12 m-1" action="_controlador_ficheiros.php" method="POST" enctype="multipart/form-data" style="padding-top:4vh;">
                                    <span style="font-size:2.5vh; padding-left:2vh;">Documentos do Imóvel</span>&nbsp;&nbsp;&nbsp;<i id="upload_file" class="fas fa-check-circle" style="height:1vh; font-size:3.2vh;margin:0.8vh"></i><span style="font-size:X-small">CLIQUE NO VERDE PARA CARREGAR O NOVO FICHEIRO</span><br>
                                    <div class="card" style="width: 100%; margin-top:1vh;">
                                        <div id="ficheirosbody" >
                                            <div id="display_card_group" class="card-group">
                                                <div class="card">
                                                    <div class="card-header text-center">Categorias</div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header text-center">Nome</div>
                                                </div>
                                            </div>
                                            <!----//LISTA DE FICHEIROS DO IMOVEL----->
                                            <?php
                                            foreach($imo_files as $imo_file) {
                                            echo '
                                            <div id="display_card_group" class="card-group">
                                                <div class="card">
                                                    <div class="card-body" style="padding:5px; ">';
                                                        echo $conn->query("SELECT nome FROM tipo_ficheiro WHERE id = " . $imo_file['tipo'])->fetch_assoc()['nome']; echo '
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body" style="padding:5px;">';
                                                        echo substr($imo_file['url'], strrpos($imo_file['url'], '/')+1, strlen($imo_file['url']) - strrpos($imo_file['url'], '/')-1);
                                                        echo '
                                                        <span style="margin-right:10px; float:right;">
                                                            <a style="unset:all;" href="'.$imo_file['url'].'" target="_blank"><i data-fileid="' . $imo_file['id'] . '" class="fas fa-download downloadfile" style="height:1vh; font-size:1.5vh; margin:0">
                                                            </i></a>
                                                            <i data-fileid="' . $imo_file['id'] . '" class="fas fa-trash deletefile" style="height:1vh; font-size:1.5vh; margin:0">
                                                            </i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>';
                                            }
                                            ?>
                                            <!----//FIM LISTA DE FICHEIROS DO IMOVEL----->

                                            <input type="text" value="1" name="type_of_contract" hidden>
                                            <input type="text" value="<?php if(isset($_GET['id'])) echo $_GET['id']; else echo 0; ?>" name="idimo" hidden>
                                            <div id="input_card_group" class="card-group">
                                                <div class="card">
                                                    <select class="form-control" id="btn_tipo" style="margin:auto"
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
                                                    <div class="custom-file col">
                                                    <input type="file" class="custom-file-input" id="ifile" name="ifile">
                                                    <label id="filenamedisplay" class="custom-file-label" for="customFile">Ficheiro</label>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                            <div class="" style="height:4vh; width:95%; margin:auto; padding:10px; text-align:center;">
                                                <span>
                                                <i class="fas fa-plus" id="addfile" style="height:1vh; font-size:1.5vh;"> </i> Adicionar
                                                ficheiro &nbsp;&nbsp;&nbsp;
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </form>

                            </div>



                            <div class="tab-pane fade" id="entidades" role="tabpanel" aria-labelledby="entidades-tab">
                                <div class="row" style="padding-top:4vh;">
                                    <div class="col">
                                        <span style="font-size:3vh;">Proprietários</span>
                                        <i class="fa fa-plus" id="add_contact"></i>
                                        <div class="card" style="margin-top:1vh;">
                                            <div class="card-header">
                                            Nomes
                                            </div>
                                            <ul class="list-group list-group-flush">
                                            <?php
                                            foreach($entidades as $entidade) {
                                                echo '
                                                <li class="list-group-item" >
                                                <a href="../entidade/entidade?id='. $entidade['id'] .'">' .
                                                $entidade['nome'] .
                                                '</a>
                                                <i class="fas fa-times delcontato" data-idc="'. $entidade['id'].'" style="float:right"></i>
                                                </li>';
                                            }
                                            ?>
                                            <li class="list-group-item" id="add_li" style="display: none;">
                                                <div class="form-group row">
                                                <select class="browser-default custom-select col-10" onfocus='this.size=5;' onblur='this.size=1;'
                                                    onchange='this.size=1; this.blur();' id="domcontatoid" name="domcontatoid">
                                                    <option disabled selected value> </option>
                                                    <?php
                                                foreach($all_entidades as $umaentidade) {
                                                if(strlen($umaentidade['nome'])>1)
                                                echo '<option class="opt" value="' . $umaentidade['id'] . '" ';
                                                if(/*$umaentidade['id'] == $contato_id OR*/ (isset($_GET['who']) AND $umaentidade['id'] == $_GET['who'])) {
                                                    echo 'selected';
                                                }
                                                echo '>'. $umaentidade['nome'] . "</option>";
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
                                    </div>
                                    <div class="border-left-primary col" style="">
                                        <a href="../contato/contato.php?with=<?php echo $imovel_id; ?>" style="" class="btn btn-primary btn-icon-split col-3" hidden>
                                            <span class="icon text-white-50" style="position:absolute; left:0;">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="text" style="padding-left: 2vw;">Contato</span>
                                        </a>
                                        <div style="font-size:small;">
                                        <?php include '../partials/tabela_contatos.php'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="angariacao" role="tabpanel" aria-labelledby="angariacao-tab">
                                
                                <form id="abitazione" class="col-lg-6 col-sm-6 border-left-primary" style="height:60vh; padding:10px;" method="POST" action="relacoes.php">

                                    <p> Clique em criar angariação se este imóvel será angariado sozinho. Se a angariação inclui mais imóveis, estes poderão ser adicionados em baixo pertencendo ao mesmo número de angariação. 
                                        Os documentos e informações relativas exclusivamente ao contrato de angariação devem ser colocadas neste separador.</p>
                                    <input name="entidadeA" value="<?php echo $imovel_ref; ?>" hidden>
                                    
                                    <div class="form-group">
                                        <div class="row" style="padding-left: 1.2vh;">
                                            <input class="form-control col-3" id="angariacao_input" name="angariacao" value="<?php echo $angariacao; ?>" style="margin-right:1vw;" readonly> 
                                            <div id="criar_ang" class="<?php echo ($angariacao==0) ? 'btn btn-dark' : 'btn btn-primary'; ?> col-6 btn-copy" ><?php echo ($angariacao==0) ? 'Criar angariação' : 'Copiar número de angariação'; ?></div>
                                            <div id="destruir_ang" class="btn btn-danger col-1 ml-2" <?php echo ($angariacao==0) ? ' hidden >' : '><i class="fas fa-trash"></i>'; ?></div>
                                        </div>
                                    </div>

                                    <h4>Imóveis relacionados</h4>
                                    <div class="form-group" id="associar_familiar">
                                        <label class="form-check-label" for="domabitazione">Associe o imóvel ou então&nbsp;</label><a href="#">crie uma nova</a>
                                        <select class=" form-control js-example-basic-single" id="domabitazione" name="domabitazione">
                                        <option selected disabled>Escolha...</option>
                                        <?php
                                        foreach($listaimoveis as $item) {
                                            echo '<option style="font-size:1.5vh" value="' . $item['id'] . '">' . $item['titulo'] . '</option>';
                                        }
                                        ?>
                                        </select>
                                        <br><br>
                                        <ul class="list-group list-group-flush">
                                        <?php
                                        foreach($imoveis_selected as $item) {
                                        echo '<li class="list-group-item" style="border:1px solid #caccd9; border-radius:8px">';
                                            echo '<div class="row">';
                                            echo '<div class="col-10"><a  href="#'.$item['id'].'">'.$item['titulo'].'</a></div>';
                                            echo '<div class="col-2">'.'<i data-idang="'.$item['angariacao'].'" data-idimo="'.$item['id'].'" class="fas fa-trash delimo"></i>'.'</div>';
                                            echo '</div>';
                                        echo '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>

                                    <div class="modal hide fade" id="abitazione_modal" tabindex="-1" role="dialog" aria-labelledby="abitazione_modal" aria-hidden="true" >
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
                                                foreach($listarelacoes_abitazione as $item) {
                                                    echo '<option value="' . $item['id'] . '">';
                                                    echo  $item['nome'];
                                                    echo '</option>';
                                                }
                                                ?>
                                            </select>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel_abitazione">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
            </div> 
            <?php include '../partials/footer.php'; ?>
        </div>

        <!-------------------------------------------------------------------------------------------------------------------------->
        <!-------------------                                   MODALS                                          -------------------->
        <div id="modal_contato" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="modal_contato_form">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span>Agendamento</span>
                            <div class="form-group row mt-2">
                                <div class="input-group date col" id="agendado_h_picker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#agendado_h_picker" name="agendado_d" id="agendado_d" />
                                    <div class="input-group-append" data-target="#agendado_h_picker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                    </div>
                                </div>
                                <div class="input-group date col" id="agendado_t_picker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#agendado_t_picker" name="agendado_t" id="agendado_t" />
                                    <div class="input-group-append" data-target="#agendado_t_picker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                            <span>Tipo de contato</span>
                            <div class="form-group mt-2">
                            <select class="form-control" id="modal_tipos_contato" >
                                <?php $tipo_contato = $conn->query('SELECT * FROM tipo_contato')->fetch_all(MYSQLI_ASSOC);
                                foreach($tipo_contato as $item) {
                                    echo '<option  value="' . $item['id'] . '">';
                                    echo $item['nome'];
                                    echo '</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <span>Descrição</span>
                            <div class="form-group mt-2">
                                <textarea class="form-control" id="modal_descricao" name="modal_descricao"></textarea>
                            </div>
                            <span></span>
                            <div class="form-group mt-2 row">
                                <div class="col">
                                    <div class="pretty p-icon p-round p-pulse">
                                        <input type="checkbox" id="modal_estado_input" name="modal_estado_input" />
                                        <div class="state p-success">
                                            <label id="estado_label">Estado</label>
                                            <i class="icon mdi mdi-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <input style="line-height: 3.5vh; height: 3.5vh;"
                                        type="datetime-local" class="form-control" id="timestamp_completed" name="timestamp_completed" readonly value="" />
                                </div>
                            </div>
                        
                            
                        <div class="modal-footer" >
                            <button type="button" type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>			
</html>