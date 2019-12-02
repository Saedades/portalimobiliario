
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>



<script charset="UTF-8">
$(document).ready(function() {

  var mainid=<?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; }?>;

  $('#contatos_table').DataTable({
      "scrollY": "700px",
      "scrollCollapse": true,
      "paging": false,
      "bInfo": false
  });

  $('#contacts').DataTable({
      "scrollY": "700px",
      "scrollCollapse": true,
      "paging": false,
      "bInfo": false
  });

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

      /*
      $('.contato_click').dblclick(function(){
				//window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
			});
      */

  $('#add_li').hide();
  $('#add_entidade').click(function() {
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

  $('#domentidadeid').change(function() {
      var saveit = $('#savenewrole');
      saveit.css('color', 'green');
      saveit.css('cursor', 'pointer');
  });

  $('#imovelassoc').change(function() {
      var saveit = $('#savenewimovelassoc');
      saveit.css('color', 'green');
      saveit.css('cursor', 'pointer');
  });

  $('.delcontato').click(function() {
      var sel = $(this).data('idc');

      swal({
          title: "Tem a certeza?",
          text: "Esta relação irá desaparecer.",
          icon: "warning",
          buttons: ["Cancelar", "Apagar"],
          dangerMode: true,
      }).then((willDelete) => {
        $.ajax({
            url: "_controlador_entidade.php",
            method: 'POST',
            async: false,
            data: {
                'action_type': 7,
                'id_of_relation': sel
            },
            success: function(result) {
                window.location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
      });
  });

  $('#savenewrole').click(function() {
      var sel = $("#domentidadeid option:selected").val();
      var sel2 = $("#domrelationid option:selected").val();

        if (sel != null && sel2 != null) {
            if ($(this).css('color') === 'rgb(0, 128, 0)') {
                $.ajax({
                    url: "_controlador_entidade.php",
                    method: 'POST',
                    async: false,
                    data: {
                        'action_type': 6,
                        'id_entidade': sel,
                        'id_base_entidade':mainid,
                        'id_relation' : sel2
                    },
                    success: function(result) {
                      //location.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        }

        swal({
        text: "Relação adicionada!",
        icon: "success",
      }).then( function() { location.reload(); });
  });

  $('#savenewimovelassoc').click(function() {
      var sel = $("#imovelassoc option:selected").val();

        if (sel != null) {
            if ($(this).css('color') === 'rgb(0, 128, 0)') {
                $.ajax({
                    url: "_controlador_entidade.php",
                    method: 'POST',
                    async: false,
                    data: {
                        'action_type': 10,
                        'id_imovel': sel,
                        'id_entidade':mainid
                    },
                    success: function(result) {
                      //alert(result);
                      //location.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        }

        swal({
        text: "Relação adicionada!",
        icon: "success",
      }).then( function() { location.reload(); });
  });

  $('.deletenewimovelassoc').click(function() {
      var sel = $(this).data('imo');

        if (sel != null) {

          $.ajax({
              url: "_controlador_entidade.php",
              method: 'POST',
              async: false,
              data: {
                  'action_type': 11,
                  'id_imovel': sel,
                  'id_entidade':mainid
              },
              success: function(result) {
                //alert(result);
                //location.reload();
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alert(xhr.status);
                  alert(thrownError);
              }
          });

        }

        swal({
        text: "Relação apagada!",
        icon: "warning",
      }).then( function() { location.reload(); });
  });


  $('#delbtn').click(function() {
      swal({
          title: "Tem a certeza?",
          text: "Este registo vai ser eliminado permanentemente.",
          icon: "warning",
          buttons: ["Cancelar", "Apagar"],
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
              $.ajax({
                  type: "POST",
                  url: "_controlador_entidade.php",
                  data: {
                      id_delete: mainid,
                      //action_type: 4
					  action_type: 49
                  },
                  cache: false,
                  success: function(data) {
                      swal({
                          text: "Registo apagado com sucesso! " + data,
                          icon: "success",
                      }).then(function() {
                          location.replace('listaentidades.php');
                      });
                  },
                  error: function(jqXhr, textStatus, errorMessage) {
                      alert('Error: ' + errorMessage);
                  }
              });
          }
      });
  });

  $('#forgetbtn').click(function() {
      swal({
          title: "Tem a certeza?",
          text: "Esquecer irá apagar permanentemente todos os dados desta entidade.",
          icon: "warning",
          buttons: ["Cancelar", "Apagar"],
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {

              $.ajax({
                  type: "POST",
                  url: "_controlador_entidade.php",
                  data: {
                      id_delete: mainid,
                      action_type: 5
                  },
                  cache: false,
                  success: function(data) {
                      swal({
                          text: "Registo apagado com sucesso! " + data,
                          icon: "success",
                      }).then(function() {
                          location.replace('entidade.php?id=' + theid);
                      });
                  },
                  error: function(jqXhr, textStatus, errorMessage) {
                      alert('Error: ' + errorMessage);
                  }
              });
          }
      });
  });




  //---------------------------- visual cues etc -----------------------//

  $('#zip1').keyup(function() {
      var myString = $(this).val();
      var lastchar = myString.substr(myString.length - 1, myString.length);
      if (isNaN(parseInt(lastchar, 10))) {
          // Is not a number
          $(this).val(myString.substr(0, myString.length - 1));
      }
      if (myString.length > 4) {
          $(this).val(myString.substr(0, 4));
      }
  });

  $('#zip2').keyup(function() {
      var myString = $(this).val();
      var lastchar = myString.substr(myString.length - 1, myString.length);
      if (isNaN(parseInt(lastchar, 10))) {
          // Is not a number
          $(this).val(myString.substr(0, myString.length - 1));
      }
      if (myString.length > 3) {
          $(this).val(myString.substr(0, 3));
      }
  });

  var newitem = <?php if(!isset($_GET['id'])) { echo 1; } else { echo 0; }?>;

  if (!newitem) {
      $('#submitbtn').hide();
      $('#delbtn').hide();
  } else {
      $('#delbtn').hide();
      $('#editbtn').hide();
  }

  $('#editbtn').click(function() {
      if ($('#submitbtn').is(":hidden")) {
          $('#submitbtn').show();
          $('#delbtn').show();
          $(this).text('Cancelar');
      } else {
          $('#submitbtn').hide();
          $('#delbtn').hide();
          $(this).text('Editar');
      }
  });

  $('#forgetbtn').hide();
  if ($('#domactive').val() == 0) {
      $('#forgetbtn').show();
  }




//-------------------------//

/*
$('.a_contact').dblclick(function () {
  var the_id = $(this).data("theid");
  window.location.replace('entidade.php?id=' + the_id);
});*/

//--------------------------------------------------------------------------------------------kkkkkkkkkkkkkkkkkkkk--//

$('#entity').modal({show: false});




























$('.a_contact').click(function(){
    //$(this).data('contatoid');
	
    $('#entity').modal({show: true});
    var ident = $(this).data('theid');
    var entity_info = [];
    var entity_notes = [];
    var entity_tasks = [];
	var entity_users = [];

    $('#entidade').text(ident);

    $.ajax({
        async: false,
        dataType: "json",
        type: "POST",
        url: "_entidade_modal.php",
        data: {
            entity: ident
        },
        cache: false,
        success: function (data) {
            entity_info = JSON.parse(JSON.stringify(data));
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error: ' + errorMessage + ' ' + textStatus);
        }
    });

    $('#vista_completa').click(function() {
		window.location.href = "/bootstrap4/php/entidade/entidade.php?id=" + ident;
    });


    if(entity_info.nome.length>1 && entity_info.apelido.length>1)
        $('#nomeapelido').text(entity_info.nome + ' ' + entity_info.apelido);
    else
        $('#nomeapelido').text('');

    if(entity_info.telemovel!=0)
        $('#telemovel').text(entity_info.telemovel);
    else
        $('#telemovel').text('');


    if(entity_info.email.length>1)
        $('#email').text(entity_info.email);
    else
        $('#email').text('');


    if(entity_info.categoria.length>1)
        $('#categoria').text(entity_info.categoria);
    else
        $('#categoria').text('');


    if(entity_info.rating.length>1)
        $('#rating').text(entity_info.rating);
    else
        $('#rating').text('');


    if(entity_info.lead.length>1)
        $('#lead').text(entity_info.lead);
    else
        $('#lead').text(entity_info.lead);

	//---------------notes-------------//
    $.ajax({
        async: false,
        dataType: "json",
        type: "POST",
        url: "_entidade_modal.php",
        data: {
            entity: ident,
            action: 2
        },
        cache: false,
        success: function (data) {
            entity_notes = JSON.parse(JSON.stringify(data));
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error: ' + errorMessage);
        }
    });

    $('.entity_notes_li').remove();

    $.each(entity_notes, function(key, value){
        $('#input_div_entity_notes').append('<li class="list-group-item entity_notes_li" data-noteid="' + value['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + value['criado'] + '</p>' + value['descricao'] + '</li>');
    });
	//--------------------------------//
	
	
	//---------------tasks-------------//
    $.ajax({
        async: false,
        dataType: "json",
        type: "POST",
        url: "_entidade_modal.php",
        data: {
            entity: ident,
            action: 3
        },
        cache: false,
        success: function (data) {
            entity_tasks = JSON.parse(JSON.stringify(data));
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error: ' + errorMessage);
        }
    });

    $('.task').remove();

    $.each(entity_tasks, function(key, value){
        var dtdb = new Date(value['agendado']);
        var dtnow = new Date();
        var labeltime = '';
        var labelefetuar = '';
        var labelcolor = '';
        var classtate = 'undone';


        labelefetuar = '<i id="efetuar" style="float:right;padding-top:1vh;font-size:2vh;" class="far fa-check-square"></i>';



        if(dtdb.getFullYear() === dtnow.getFullYear() && dtdb.getMonth() === dtnow.getMonth() && dtdb.getDate() === dtnow.getDate()) {
          labeltime = 'HOJE';
        }
        else if(dtdb > dtnow) {
          labeltime = 'PRÓXIMO';

        }
        else if(dtdb < dtnow ) {
          labeltime = 'POR FAZER';
        }
        else {
          labeltime = 'DEFAULT';
          //alert(dtdb.getDate() + ' - ' + dtnow.getDate())
        }



        if(value['estado']==1) {
          labeltime = 'EFETUADO';
          labelcolor = ' greentask';  //green
          labelefetuar = '';
          classtate = 'done';
        }
        else if(labeltime === 'POR FAZER') {
          labelcolor = ' redtask'; //red
        }
        else if(labeltime === 'HOJE') {
          labelcolor = ' yellowtask';    //yellow
        }
	
        var string = '<a class="list-group-item list-group-item-action task ' + labelcolor + ' ' + classtate + '" data-taskid="' + value['id'] + '"> ' + '<div class="row" style="font-size:1+5vh;">   <div class="col"><p style="float:left;  margin:0; ">' + value['agendado'] + '</p></div><div class="col"><p style="float:right; margin:0; font-weight:600;">' + labeltime + '</p></div></div>        <div class="row"><div class="col-10">' + value['titulo'] + '</div><div class="col">' + labelefetuar + '</div></div></a>';

        $('#div_entity_tasks').append(string);
    });
	//--------------------------------//


	//---------------users-------------//
	 $.ajax({
        async: false,
        dataType: "json",
        type: "POST",
        url: "_people.php",
        data: {
            entity: ident,
            action: 80
        },
        cache: false,
        success: function (data) {
            entity_users = JSON.parse(JSON.stringify(data));
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error: ' + errorMessage);
        }
    });
	
	//--------------------------------//


    $('.list-group-item-action').click(function(e){

      var idseg = $(this).data('taskid');
      $('#segid').text($(this).data('taskid'));
	  //alert(idseg);

      if(e.target.nodeName == 'I') {
        $.ajax({
            async: false,
            type: "POST",
            url: "_entidade_modal.php",
            data: {
                idseg: idseg,
                action: 7
            },
            cache: false,
            success: function (data) {
              Swal.fire(
                  'Seguimento',
                  'Terminado!',
                  'success'
              ).then(function(){
                    var ident = data;
					var test1 = window.location.href;
					//alert(test1);
					test1 = test1.replace(/[&?]emodal=[0-9]*/, '');
					//alert(test1);
                    if (test1.indexOf("?") >= 0) { 
						//alert(test1 + "&emodal=" + ident);
						window.location.replace(test1 + "&emodal=" + ident);
					}
					else {
						//alert(test1 + "?emodal=" + ident);
						window.location.replace(test1 + "?emodal=" + ident);
					}
              });
                //alert(task_info.toSource());
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert('Error: ' + errorMessage);
            }
        });
      }
      else {
        $('#segid').val($(this).data('taskid'));
        var task_info = [];
        $.ajax({
            async: false,
            dataType: "json",
            type: "POST",
            url: "_entidade_modal.php",
            data: {
                idseg: idseg,
                action: 5
            },
            cache: false,
            success: function (data) {
                task_info = JSON.parse(JSON.stringify(data));
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert('Error: ' + errorMessage);
            }
        });

        // segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
        $('#segtitle').val(task_info.titulo);
        $('#segdescricao').val(task_info.descricao);
        $("#tiposeg_select option[value=" + task_info.tipo + "]").attr('selected', 'selected');
        var date_seg = moment(task_info.agendado).format("YYYY-MM-DD");
        var time_seg = moment(task_info.agendado).format("HH:mm");
        $("#segdata").val(date_seg);
        /*
        if(time_seg !== '00:00:00') {
            $("#seghour").val(time_seg);
            $('#hourtype option:eq(2)').attr('selected', 'selected');
            $("#seghourform").show();
        }
        else {
            $("#seghour").val('00:00:00');
            $('#hourtype option:eq(1)').attr('selected', 'selected');
            $("#seghourform").hide();
        }
        */

        $("#seghour").val(time_seg);
        if(task_info.tipohora==1) {
          $('#hourtype').val(1);
          $("#seghourform").hide();
        }
        else{
          $('#hourtype').val(2);
          $("#seghourform").show();
        }


        if(task_info.estado=='1') {
            $("#btn_segestado").removeClass('btn-nao-efetuado');
            $("#btn_segestado").addClass('btn-efetuado');
            $("#btn_segestado").text('EFETUADO');
			$("#segestado").prop( "checked", true );
        }
        $('#seguimento_modal').modal({show: true});

      }
    });

    //--------------filtering of tabs-----------------//
    $(".tab-filter-tasks").click(function() {
        var selected_tab = this.id;
        // todosseg-tab
        // porfazer-tab
        // historico-tab
        switch(selected_tab) {
            case 'todosseg-tab':$(".undone").show(); $(".done").show(); break;
            case 'porfazer-tab': $(".undone").show(); $(".done").hide(); break;
            case 'historico-tab': $(".undone").hide(); $(".done").show(); break;
        }

    });

    $('#porfazer-tab').click();


});
























$('#seguimento_modal').modal({show: false});

$('#addnote').click(function(){
  var inputnote = $('#newnote').val();
  var ident = $('#entidade').text();

  $.ajax({
      async: false,
      dataType: "json",
      type: "POST",
      url: "_entidade_modal.php",
      data: {
          entity: ident,
          descricao: inputnote,
          action: 4
      },
      cache: false,
      success: function (data) {
          note_info = JSON.parse(JSON.stringify(data));
      },
      error: function (jqXhr, textStatus, errorMessage) {
          alert('Error: ' + errorMessage);
      }
  });
    $('#inputdiv_newnote').after('<li class="list-group-item entity_notes_li" data-noteid="' + note_info['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + note_info['criado'] + '</p>' + note_info['descricao'] + '</li>');
    $('#newnote').val('');

});
	
	
$('#addnote_vista_completa').click(function(){
  var inputnote = $('#newnote').val();
  var ident = <?php echo (isset($_GET['id']) ? $_GET['id'] : 0); ?>;

  $.ajax({
      async: false,
      dataType: "json",
      type: "POST",
      url: "_entidade_modal.php",
      data: {
          entity: ident,
          descricao: inputnote,
          action: 4
      },
      cache: false,
      success: function (data) {
          note_info = JSON.parse(JSON.stringify(data));
      },
      error: function (jqXhr, textStatus, errorMessage) {
          alert('Error: ' + errorMessage);
      }
  });
    $('#inputdiv_newnote').after('<li class="list-group-item entity_notes_li" data-noteid="' + note_info['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + note_info['criado'] + '</p>' + note_info['descricao'] + '</li>');
    $('#newnote').val('');

});
	
	
	

$('#seg_addnote').click(function(){
  var inputnote = $('#seg_newnote').val();
  var idseg = $('#segid').text();
	//alert(inputnote);
	//alert(idseg);

  $.ajax({
      async: false,
      dataType: "json",
      type: "POST",
      url: "_entidade_modal.php",
      data: {
          idseg: idseg,
          descricao: inputnote,
          action: 6
      },
      cache: false,
      success: function (data) {
          note_info = JSON.parse(JSON.stringify(data));
      },
      error: function (jqXhr, textStatus, errorMessage) {
          alert('Error: ' + errorMessage);
      }
  });

    $('#seg_inputdiv_newnote').after('<li class="list-group-item seg_notes_li" data-noteid="' + note_info['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">' + note_info['criado'] + '</p>' + note_info['descricao'] + '</li>');
    $('#seg_newnote').val('');

});


//--------------------------------------------------------------------------------------------kkkkkkkkkkkkkkkkkkkk--//

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
          url: "", //sara
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


  //-------------------------------------------------------//

  $('#domcargolabel').hide();
  $('#domcargo').hide();
  $('#domtipoxlabel').hide();
  $('#domtipox').hide();
  //$('#formgroup_estado').hide();

  $('#domtipo').change(function(){
    var valueopt = $('#domtipo option:selected').val();
    if(valueopt==2) {
      //$('#domtipoxlabel').show();
      //$('#domtipox').show();
      //$('#domcargolabel').show();
      //$('#domcargo').show();
      $('#domnamelabel').text('Nome do banco:');
    }
    else if(valueopt==3) {
      //$('#domtipoxlabel').show();
      //$('#domtipox').show();
      //$('#domcargolabel').show();
      //$('#domcargo').show();
      $('#domnamelabel').text('Nome da empresa');
    }
    else {
      //$('#domtipoxlabel').hide();
      //$('#domtipox').hide();
      //$('#domcargolabel').hide();
      //$('#domcargo').hide();
      $('#domnamelabel').text('Nome');
    }
  });

  $('#domtipo').trigger('change');


  var isnew = <?php
  if(strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 'entidade.php') !== false AND (!isset($_GET['id'])))
    echo 1;
  else
    echo 0; ?>;


/*
  if(isnew) {
    $('#content').hide();

    $('#modal_2').modal({
      backdrop: 'static',
      keyboard: false
    });
    $('#modal_2').modal('show');

    $('#domtipomodal').change(function(){
      $('#tipo_seguinte').prop("disabled", false);
    });

    var nthsel = 1;
    $('#tipo_seguinte').click(function(){

      $('#domtipomodal > option').each(function(){
        if(this.selected)
          return false;
        nthsel++;
      });

      nthsel = nthsel-1;
      var it = 1;
      while(it<4) {
        if(it==nthsel)
          $('#domtipo option:eq('+nthsel+')').attr('selected', 'selected');
        else
          $('#domtipo option:eq('+it+')').attr('disabled', 'disabled');
        it++;
      }

      $('#domtipo').attr('readonly', true);

      if(nthsel>1) {
        $('#domname').prop('required',true);
        $('#domaddr').prop('required',true);
        $('#domlocal').prop('required',true);
        $('#domnif').prop('required',true);
        $('#domemail').prop('required',true);
        $('#domphnr').prop('required',true);
      }
      $('#content').show();
    });
  }*/


  var alerts = <?php echo (isset($_GET['code'])) ? $_GET['code'] : 0; ?>;
  if(alerts==2) {
    swal({
      text: "Entidade criada!",
      icon: "success",
    })
  }


  //-------------------------check box --------------------//


  $('.js-example-basic-single').select2({
    width: '100%'
  });

//------------------------------------------------TABS------------------------------------------------------//
var tab = <?php echo (isset($_GET['tt'])) ? $_GET['tt'] : 0 ?>;
switch(tab) {
  case 0:
  case 1: break;
  case 2: $('#relacoes-tab').click(); break;
  case 3: $('#moradatab-tab').click(); break;
}



//--------------------------------------------RELATIONSSSSSS------------------------------------------------//

  $('.delidrel').click(function(){
    var idrel = $(this).data('idrel');
    $.ajax({
      type: "POST",
      url: 'relacoes.php',
      data: {
        'idrel' : idrel
      },
      success: function(result){
        window.location.href = window.location.href + "?tt=" + result;
        window.location.reload();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("some error " + errorThrown);
      }
    });
  });



  $('#domsocieta').on('select2:select', function (e) {
    $('#societa_modal').modal('show');
  });


  $('#domfamiglia').on('select2:select', function (e) {
		var id = $(this).children(":selected").val();
	  	$.ajax({
			type: "POST",
			url: 'relacoes.php',
			data: {
				'entidade' : id,
				'get_gender' : 1
			},
			success: function(result){
				$("#domrelfam").val(0);
				if(result==1) {
					$('.gen2').hide();
					$('.gen1').show();
				}
				if(result==2) {
					$('.gen1').hide();
					$('.gen2').show();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("some error " + errorThrown);
			}
		});
    	$('#famiglia_modal').modal('show');
  });



  $('#famiglia_modal').modal({
    backdrop: 'static',
    keyboard: false,
    show: false
  });

  $('#cancel_famiglia').click(function(){
    $("#domfamiglia").val('').change();
  });



  $('#societa_modal').modal({
    backdrop: 'static',
    keyboard: false,
    show: false
  });

  $('#cancel_societa').click(function(){
    $("#domsocieta").val('').change();
  });



//-------------------------- dates -------------- //

  $('#domnascpicker').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('#datetimepicker1').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('#segdata_form').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('#seghour_form').datetimepicker({
    format: 'HH:mm'
  });


  if($('#hourtype option:selected').val()==2) {
      $('#seghourform').show();
  }
  else {
      $('#seghourform').hide();
  }

  $('#hourtype').change(function(){
      var tyyype = $("#hourtype option:selected").val();
      if(tyyype == 2) {
        $('#seghourform').show();
      }
      else {
        $('#seghourform').hide();
      }
  });

  $('#segestado').click(function(){
      if($(this).hasClass('btn-nao-efetuado')) {
          $(this).removeClass('btn-nao-efetuado');
          $(this).addClass('btn-efetuado');
          $(this).text('EFETUADO');
      } else {
          $(this).addClass('btn-nao-efetuado');
          $(this).removeClass('btn-efetuado');
          $(this).text('POR FAZER');
      }
  });



  ///---------------------------------MORADA--------------------------------------//
  //---------------------------- visual cues etc -----------------------//

  $('#domzip1').keyup(function() {
      var myString = $(this).val();
      var lastchar = myString.substr(myString.length - 1, myString.length);
      if (isNaN(parseInt(lastchar, 10))) {
          // Is not a number
          $(this).val(myString.substr(0, myString.length - 1));
      }
      if (myString.length > 4) {
          $(this).val(myString.substr(0, 4));
      }
      if($('#submitbtn_morada').hasClass('btn-dark'))
        $('#submitbtn_morada').removeClass('btn-dark');
      if(!$('#submitbtn_morada').hasClass('btn-primary'))
        $('#submitbtn_morada').addClass('btn-primary');
  });

  $('#domzip2').keyup(function() {
      var myString = $(this).val();
      var lastchar = myString.substr(myString.length - 1, myString.length);
      if (isNaN(parseInt(lastchar, 10))) {
          // Is not a number
          $(this).val(myString.substr(0, myString.length - 1));
      }
      if (myString.length > 3) {
          $(this).val(myString.substr(0, 3));
      }
      if($('#submitbtn_morada').hasClass('btn-dark'))
        $('#submitbtn_morada').removeClass('btn-dark');
      if(!$('#submitbtn_morada').hasClass('btn-primary'))
        $('#submitbtn_morada').addClass('btn-primary');
  });

  $('#domaddr').keyup(function(){
      var que = $(this).val();
      var que2 = $('#dompais option:selected').text();
      var que3 = $('#domlocal').val();
      var search_map = que.replace(" ", "+") + '+' + que3.replace(" ", "+") + '+' + que2.replace("Escolha...", "").replace(" ", "+");
      $('#mapslink').attr("href", "https://maps.google.com/maps?q=" + search_map);
      $('#gmap_canvas').attr("src", "https://maps.google.com/maps?q=" + search_map + "&t=&z=15&ie=UTF8&iwloc=&output=embed");

      if($('#submitbtn_morada').hasClass('btn-dark'))
        $('#submitbtn_morada').removeClass('btn-dark');
      if(!$('#submitbtn_morada').hasClass('btn-primary'))
        $('#submitbtn_morada').addClass('btn-primary');
    });

  $('#domlocal').keyup(function(){
    if($('#submitbtn_morada').hasClass('btn-dark'))
      $('#submitbtn_morada').removeClass('btn-dark');
    if(!$('#submitbtn_morada').hasClass('btn-primary'))
      $('#submitbtn_morada').addClass('btn-primary');
  });

  $('#domaddr').trigger('keyup');
  $('#submitbtn_morada').removeClass('btn-primary');
  $('#submitbtn_morada').addClass('btn-dark');


  $('#novavis').click(function(){
        $('#segid').val(0);
        var task_info = [];

        // segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//

        $('#segtitle').val('');
        $('#segdescricao').val('');
        $("#segtipo option[value='3']").attr('selected', 'selected');
        var date_seg = moment(task_info.agendado).format("YYYY-MM-DD");
        var time_seg = moment(task_info.agendado).format("HH:mm:ss");
        $("#segdata").val(date_seg);

        $("#seghour").val(time_seg);
        if(task_info.tipohora==1) {
          $('#hourtype').val(1);
          $("#seghourform").hide();
        }
        else{
          $('#hourtype').val(2);
          $("#seghourform").show();
        }

        $('#seguimento_modal').modal({show: true});
  });




  $('#conttable').DataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    "language": {
        "search": "Procurar:"
    },
    "scrollY":        '15vh',
    "scrollCollapse": true,
    "bSort": false
  });

  $('#conttable tr').click(function(){

    //alert('modal: ' + $(this).data('id'));
    var idseg = $(this).data('id');
	$('#segid').val(idseg);
	//alert($('#segid').val());

    //get the task details
    var task_details = [];
    $.ajax({
        async: false,
        type: "POST",
        dataType: 'JSON',
        url: "_entidade_modal.php",
        data: {
            idseg: idseg,
            action: 5
        },
        cache: false,
        success: function (data) {
            //alert(data.toSource());
            task_details = JSON.parse(JSON.stringify(data));
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error from task details: ' + errorMessage);
        }
    });


    //get the task notes
    var task_notes = [];
    $.ajax({
        async: false,
        dataType: "json",
        type: "POST",
        url: "_entidade_modal.php",
        data: {
            idseg: idseg,
            action: 8
        },
        cache: false,
        success: function (data) {
            task_notes = JSON.parse(JSON.stringify(data));
            //alert(data.toSource());
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert('Error from task notes: ' + errorMessage);
        }
    });

    //instantiate the modal
    // segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
    $('#segid').text(idseg);
    $('#segtitle').val(task_details.titulo);
    $('#segdescricao').val(task_details.descricao);
    $("#tiposeg_select option[value=" + task_details.tipo + "]").attr('selected', 'selected');
    $("#segdata").val(moment(task_details.agendado).format("YYYY-MM-DD"));
    $("#seghour").val(moment(task_details.agendado).format("HH:mm"));
    if(task_details.tipohora==1) {
      $('#hourtype').val(1);
      $("#seghourform").hide();
    }
    else{
      $('#hourtype').val(2);
      $("#seghourform").show();
    }
    if(task_details.estado=='1') {
        $("#btn_segestado").removeClass('btn-nao-efetuado');
        $("#btn_segestado").addClass('btn-efetuado');
        $("#btn_segestado").text('EFETUADO');
		$("#segestado").prop( "checked", true );
    }
    $('#seguimento_modal').modal({show: true});
	  
	

	/*$.each(task_notes, function(index, value) {
		$('<li class="list-group-item seg_notes_li" data-noteid="' + value['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">'+value['criado']+'</p>'+value['descricao']+'</li>').insertAfter('#seg_inputdiv_newnote');
	});*/
	  
	$.each(task_notes, function(index, value) {
		$('.seg_notes_li').remove();
		$('<li class="list-group-item seg_notes_li" data-noteid="' + value['id'] + '"><p style="font-size:x-small;margin-bottom: 0.5vh;">'+value['criado']+'</p>'+value['descricao']+'</li>').insertAfter('#seg_inputdiv_newnote');
	});
	  
	  $("#colnotes").show();

  });

  $('#novoseg').click(function(){
	  	$('.seg_notes_li').remove();
        $('#seguimento_modal').modal({show: true});
        //alert("segid!: " + $('#segid').val());
        $('#segid').val(0);
	  	$('#segentidade').val(<?php echo (isset($_GET['id']) ? $_GET['id'] : 0); ?>);
	  	//$('#entidade').text(<?php echo (isset($_GET['id']) ? $_GET['id'] : 0); ?>);
	  	$('#seguser').text(<?php echo (isset($_SESSION['login']) ? $_SESSION['login'] : 0); ?>);
	  	//alert($('#seguser').text());
        //alert("segid!: " + $('#segid').val());
        $('#segtitle').val('');
        $('#segdescricao').val('');
        $("#segtipo option[value='3']").attr('selected', 'selected');
        var date_seg = moment().format("YYYY-MM-DD");
        var time_seg = moment().format("HH:mm");
        $("#segdata").val(date_seg);
        $("#seghour").val(time_seg);
        $('#hourtype').val(1);
        $("#seghourform").hide();
	  	$("#colnotes").hide();
	  	$('#seguimento_modal .modal-title').text('Novo Seguimento');
  });

  $('#btn_segestado').click(function(){
      if($("#btn_segestado").hasClass('btn-nao-efetuado')) {
          $("#btn_segestado").removeClass('btn-nao-efetuado');
          $("#btn_segestado").addClass('btn-efetuado');
          $("#btn_segestado").text('EFETUADO');
          $("#segestado").prop('checked', true);
      }
      else {
          $("#btn_segestado").addClass('btn-nao-efetuado');
          $("#btn_segestado").removeClass('btn-efetuado');
          $("#btn_segestado").text('POR FAZER');
          $("#segestado").prop('checked', false);
      }
  });



  // segid ; segtitle ; segdescricao ; segtipo ; segdata ; segtypehour ; seghour ; segestado -//
  $('#save_seg').click(function(){
	  
        var segid =         $('#segid').val();
        var segtitle =      $('#segtitle').val();
        var segdescricao =  $('#segdescricao').val();
        var segtipo =       $('#segtipo option:selected').val();
        var segdata =       $('#segdata').val();
        var segtypehour =   $('#hourtype option:selected').val();
        var seghour =       $('#seghour').val();
        var segestado =     ($('#segestado').is(":checked") ? 1 : 0);
        var entidade =      $('#entidade').text();
	  	var user =      	<?php echo $_SESSION['login']; ?>;
		
	  
	  /*
	  	alert('segid : ' + segid + 'segtitle : ' + segtitle + 'segdescricao : ' + segdescricao + 'segtipo : ' + segtipo + 'segid : ' + segid + 'segdata : ' + segdata + 'segtypehour : ' + segtypehour + 'seghour : ' + seghour + 'segestado : ' + segestado + 'entidade : ' + entidade + 'user : ' + user
		);*/

        $.ajax({
            async: false,
            type: "POST",
            url: "seg_controller.php",
            data: {
                'segid' :        segid,
                'segtitle' :     segtitle,
                'segdescricao' : segdescricao,
                'segtipo' :      segtipo,
                'segdata' :      segdata,
                'segtypehour' :  segtypehour,
                'seghour' :      seghour,
                'segestado' :    segestado,
                'entidade' :     entidade,
				'user' :		 user
            },
            cache: false,
            success: function (data) {
                Swal.fire(
                    '',
                    'As modificações foram salvas! ',
                    'success'
                ).then(function(){
                    $('#seguimento_modal').modal('toggle');
                    var ident = data;
					var test1 = window.location.href;
					//alert(test1);
					test1 = test1.replace(/[&?]emodal=[0-9]*/, '');
					//alert(test1);
                    if (test1.indexOf("?") >= 0) { 
						//alert(test1 + "&emodal=" + ident);
						window.location.replace(test1 + "&emodal=" + ident);
					}
					else {
						//alert(test1 + "?emodal=" + ident);
						window.location.replace(test1 + "?emodal=" + ident);
					}
                });
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert('Error: ' + errorMessage);
            }
        });
	  
    });


    var modal_action = <?php echo ((isset($_GET['emodal'])) ? $_GET['emodal'] : 0); ?>;
    if(modal_action!=0) {
      	//$('#conttable tr[data-theid="' + modal_action + '"]').click();
		$('#conttable tr[data-theid="' + modal_action + '"]').click();
		$('.a_contact[data-theid="' + modal_action + '"]').click();
    }


    $('#conttable tr[data-estado="1"]').css('background-color', '#e7f2e8');
    $('#listaentidadestable tr[data-estado="0"]').css('background-color', '#ffe3e3');

	$('#domutilizador').change(function(){
		var iduser = $(this).val();
		var identidade = <?php echo (isset($_GET['id'])) ? $_GET['id'] : 0; ?>;
		$.ajax({
			async: false,
			type: "POST",
			url: "_people.php",
			data: {
				iduser: iduser,
				identidade: identidade,
				action: 1
			},
			cache: false,
			success: function (data) {
			},
			error: function (jqXhr, textStatus, errorMessage) {
				alert('Error from user managing: ' + errorMessage + ' - ' + textStatus);
			}
    	});
		$.ajax({
			async: false,
			type: "POST",
			url: "_notify.php",
			data: {
				iduser: iduser,
				identidade: identidade,
				action: 1
			},
			cache: false,
			success: function (data) {
				window.location.reload();
			},
			error: function (jqXhr, textStatus, errorMessage) {
				alert('Error from user managing: ' + errorMessage + ' - ' + textStatus);
			}
    	});
	});
	

	$('.del_people').click(function(){
		var iduser = $(this).data('iduser');
		var identidade = <?php echo (isset($_GET['id'])) ? $_GET['id'] : 0; ?>;
		$.ajax({
			async: false,
			type: "POST",
			url: "_people.php",
			data: {
				iduser: iduser,
				identidade: identidade,
				action: 2
			},
			cache: false,
			success: function (data) {
				//window.location.reload();
			},
			error: function (jqXhr, textStatus, errorMessage) {
				alert('Error from user managing: ' + errorMessage + ' - ' + textStatus);
			}
    	});
		
		$.ajax({
			async: false,
			type: "POST",
			url: "_notify.php",
			data: {
				iduser: iduser,
				identidade: identidade,
				action: 2
			},
			cache: false,
			success: function (data) {
				window.location.reload();
			},
			error: function (jqXhr, textStatus, errorMessage) {
				alert('Error from user managing: ' + errorMessage + ' - ' + textStatus);
			}
    	});
	});
	
	$('#sidebarToggle').trigger('click');
	
});
</script>
