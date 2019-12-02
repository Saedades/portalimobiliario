  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/pretty-checkbox.min" />
  <script src="../../js/jquery.formatCurrency-1.4.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
  
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
      "scrollY": "700px",
      "scrollCollapse": true,
      "paging": false,
      "bInfo": false
    });

    $('.contato_click').dblclick(function(){
      window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
    });




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

      var newitem = <?php if(!isset($_GET['id'])) { echo 1; } else { echo 0; } ?>;
      

      if(!newitem) {
        alert(newitem);
        $('#submitbtn').hide();
        $('#delbtn').hide();
      }
      else {
        alert(newitem);
        $('#delbtn').hide();
        $('#editbtn').hide();
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
			  alert("sawal");
			  /*
			  Swal({
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
				});*/
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


        $('.contato_click').dblclick(function(){
          window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
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


  
  });


</script>
