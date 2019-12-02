<?php
require_once '../partials/connectDB.php';
include_once '../partials/validate_session.php';


if(isset($_POST['sbmtd'])) {

  $idcontato =   (isset($_POST['domcontato']))    ?   $_POST['domcontato']  : 0;
  $iduser =     (isset($_POST['domuserid']))    ?   $_POST['domuserid']     : 0;
  $title =      (isset($_POST['domtitle']))     ?   $_POST['domtitle']      : " ";
  //$identidade =  (isset($_POST['domcontatoid'])) ?   $_POST['domcontatoid']  : 0;
  //$idimovel =   (isset($_POST['domassid']))     ?   $_POST['domassid']      : 0;
  $descr =      (isset($_POST['domdescr']))     ?   $_POST['domdescr']      : " ";
  $date =       (isset($_POST['domdate']))      ?   date_format(date_create($_POST['domdate']), 'Y-m-d H:i:s')      : "NULL";
  //$date =       (strlen($date)<8)               ?   "NULL"                  :  '"' . $date . '"';
  //$time =       (isset($_POST['domtime']))      ?   $_POST['domtime']       : "0000";
  //$hour =       (strlen($time)<4)               ?   "0000"                  :  str_replace(':', '', $time);
  $seguimento  = (isset($_POST['domseguimento']))  ?    $_POST['domseguimento']      : 0;


    if($conn->query('SELECT * FROM contatos WHERE id = ' . $idcontato)->num_rows) {
        $update_query =
       'UPDATE contatos SET
        descricao ="' . $descr . '",
        titulo="' . $title . '",
        modified="'. date('Y-m-d h:i:s') . '",
        agendado="'. $date. '",
        seguimento =' . $seguimento . '
        WHERE id=' . $idcontato;

        if($conn->query($update_query)) {
          header('Location: contato.php?id=' . $idcontato . "&code=1");
        }
        else {
          echo "ERROR: Cannot update.<br>" . $update_query;
        }
    }
    else {
      $insert_query =
      'INSERT INTO contatos(  descricao,          user,          titulo,           agendado,        seguimento      )
        VALUES             ("' . $descr . '", '. $iduser . ', "' . $title . '", "'. $date . '",' .  $seguimento . ' )';
 
      echo "<p>" . $insert_query . "</p>";
      if($newid = $conn->query($insert_query)) {
          header('Location: contato.php?id=' . $conn->insert_id . "&code=2");
      }
      else {
        echo "ERROR: Cannot insert.<br>";
      }

    }
}
else {
  if(isset($_POST['action_type'])) {

    function add_imovel_contato_relation($conn,$imovel,$contato) {
      if($contato==0) {
        return 0;
      }

      $test_query = 'SELECT * FROM  contatos_imoveis WHERE imovel=' . $imovel . ' AND contato = ' . $contato;
      if($conn->query($test_query)->num_rows>0) {
        $query = 'UPDATE contatos_imoveis SET deleted_at=null WHERE imovel=' . $imovel . ' AND contato = ' . $contato;
      }
      else {
        $query = 'INSERT INTO contatos_imoveis(contato, imovel) VALUES(' . $contato . ',' . $imovel . ')';
      }

      if($conn->query($query))
        return 1;
      else
        return 0;

    }

    function delete_imovel_contato_relation($conn,$imovel,$contato) {
      if($contato==0) {
        return 0;
      }

      $test_query = 'SELECT * FROM  contatos_imoveis WHERE imovel=' . $imovel . ' AND contato = ' . $contato;
      if($conn->query($test_query)->num_rows>0) {
        $query = 'UPDATE contatos_imoveis SET deleted_at="' . date("Y-m-d\TH:i:s") . '" WHERE imovel=' . $imovel . ' AND contato = ' . $contato;
      }
      if($conn->query($query))
        return 1;
      else
        return 0;
    }

    function add_contato_entidades_relation($conn,$entidade,$contato) {
      if($contato == 0)
        return 0;
      $test_query = 'SELECT * FROM  contatos_entidades WHERE contato=' . $contato . ' AND entidade = ' . $entidade;
      if($conn->query($test_query)->num_rows>0) {
        $query = 'UPDATE contatos_entidades SET date_deleted=null WHERE contato=' . $contato . ' AND entidade = ' . $entidade;
      }
      else {
        $query = 'INSERT INTO contatos_entidades(contato, entidade) VALUES(' . $contato . ',' . $entidade . ')';
      }

      if($conn->query($query))
        echo 1;
      else
        echo 0;
    }


    function delete_contato_entidades_relation($conn,$entidade,$contato) {
      if($contato==0) {
        return 0;
      }

      $test_query = 'SELECT * FROM  contatos_entidades WHERE contato=' . $contato . ' AND entidade = ' . $entidade;
      if($conn->query($test_query)->num_rows>0) {
        $query = 'UPDATE contatos_entidades SET deleted_at="' . date("Y-m-d\TH:i:s") . '" WHERE contato=' . $contato . ' AND entidade = ' . $entidade;
      }
      if($conn->query($query))
        return 1;
      else
        return 0;
    }


    switch($_POST['action_type']) {
      case 10: echo         add_imovel_contato_relation($conn,$_POST['id_imovel'],$_POST['id']); break;
      case 11: echo      delete_imovel_contato_relation($conn,$_POST['id_imovel'],$_POST['id']); break;
      case 30: echo      add_contato_entidades_relation($conn,$_POST['id_entidade'],$_POST['id']); break;
      case 31: echo   delete_contato_entidades_relation($conn,$_POST['id_entidade'],$_POST['id']); break;
    }
  }
}

