<div id="alert" class="alert alert-success alert-dismissible" <?php if(!(isset($_GET['code']))) { echo " hidden"; } ?>>

  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

  <span id="changes" <?php if(isset($_GET['code']) AND $_GET['code']!=1) { echo " hidden"; } ?> >
    <strong>Sucesso!</strong> Alterações foram guardadas!
  </span>

  <span id="newcont" <?php if(isset($_GET['code']) AND $_GET['code']!=2) { echo " hidden";} ?>>
    <strong>Sucesso!</strong> Novo registo adicionado com sodium_crypto_kx_client_session_keys!
  </span>

</div>
