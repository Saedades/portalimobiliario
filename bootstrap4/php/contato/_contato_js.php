

  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>


<script>
	$(document).ready(function(){
    var mainid=<?php if(isset($_GET['id'])) { echo $_GET['id']; } else { echo 0; }?>;


		$('#history_contact').DataTable({
            "scrollY": "700px",
            "scrollCollapse": true,
            "paging": false,
            "bInfo" : false
        } );

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
					  url: "php/_delete.php",
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

			$('#submitbtn').hide();
			$('#delbtn').hide();

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
				window.location.replace('contato.php?id=' + $(this).data('contatoid'));
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
        if (sel != null) {
            if ($(this).css('color') === 'rgb(0, 128, 0)') {
                $.ajax({
                    url: "_controlador_contato.php",
                    method: 'POST',
                    async: false,
                    data: {
                        'action_type': 10,
                        'id_imovel': sel,
                        'id':mainid
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

        if (sel != null) {

          $.ajax({
              url: "_controlador_contato.php",
              method: 'POST',
              async: false,
              data: {
                  'action_type': 11,
                  'id_imovel': sel,
                  'id':mainid
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
                    text: "Não foi possível remover esta associação.",
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

        swal({
        text: "Relação apagada!",
        icon: "warning",
      }).then( function() { location.reload(); });
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
      url: "_controlador_contato.php",
      method: 'POST',
      async: false,
      data: {
        'action_type' : 31,
        'id_entidade': sel,
        'id_contato': <?php echo $contato_id;?>
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
        url: "_controlador_contato.php",
        method: 'POST',
        async: false,
        data: {
          'action_type' : 30, //relate imovel and entity
          'id_entidade': sel,
          'id': <?php echo $contato_id;?>
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


  $('#datetimepicker2').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ss', // or 'l' (lowercase L) for non-zero-padded
      date: '<?php echo $date;?>'
  });



});

</script>
