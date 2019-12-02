<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
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

      $('.contato_click').dblclick(function(){
				window.location.replace('../contato/contato.php?id=' + $(this).data('contatoid'));
			});

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
    alert("save new role");
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
          text: "Este registo vai passar a estar inativo.",
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
                      action_type: 4
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

$('.a_contact').dblclick(function () {
  var the_id = $(this).data("theid");
  window.location.replace('entidade.php?id=' + the_id);
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
  }


  var alerts = <?php echo (isset($_GET['code'])) ? $_GET['code'] : 0; ?>;
  if(alerts==2) {
    swal({
      text: "Entidade criada!",
      icon: "success",
    })
  }





});
</script>
