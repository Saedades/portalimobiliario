<?php

if($result=$conn->query('SELECT idang FROM foco20.angariacoes_imoveis WHERE idimo=' . $imovel_id) AND $result->num_rows!=0) {
    
    $action = 2;
    if($aangariacao = $conn->query("SELECT * FROM angariacoes WHERE id =" . $result->fetch_assoc()['idang'] . " AND iduser=" . $_SESSION['login'])->fetch_assoc()) {

        $id =           (isset($aangariacao['id'])) ?             $aangariacao['id']             : 0;
        $estado =       (isset($aangariacao['estado'])) ?         $aangariacao['estado']         : 0;
        $created_at =   (isset($aangariacao['created_at'])) ?     $aangariacao['created_at']    : " ";
        $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $aangariacao['id'];
        $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
        $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $aangariacao['id'];
        $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
        $query = '(SELECT idimo FROM angariacoes_imoveis WHERE idang =' . $aangariacao['id'] . ')';
        $title_top = "Editar Angariação";
        $history = $conn->query("SELECT * FROM contatos WHERE id IN (SELECT DISTINCT contato FROM contatos_imoveis as CTS WHERE imovel IN (SELECT idimo FROM angariacoes_imoveis as ANGI WHERE idang =" . $id .") AND imovel<>0 AND id<>0 ) OR id IN (SELECT contato_fecho FROM angariacoes WHERE id = " .   $id . ")")->fetch_all(MYSQLI_ASSOC);
        $file_query='SELECT * FROM angariacoes_ficheiros WHERE idangariacoes = ' . $id;
        $ang_files= $conn->query($file_query)->fetch_all(MYSQLI_ASSOC);
        $risco_query='SELECT * FROM angariacoes_risco ';
        $angrisco =  $conn->query($risco_query)->fetch_all(MYSQLI_ASSOC);
    }
}
else {
    
    $action = 1;
    $id =           (isset($aangariacao['id'])) ?             $aangariacao['id']             : 0;
    $estado =       (isset($aangariacao['estado'])) ?         $aangariacao['estado']         : 0;
    $created_at =   (isset($aangariacao['created_at'])) ?     $aangariacao['created_at']    : " ";
    $query = 'SELECT * FROM angariacoes_entidades WHERE idang =' . $id;
    $ang_entidades = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    $query = 'SELECT * FROM angariacoes_imoveis WHERE idang =' . $id;
    $ang_imoveis = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
    $title = " ";
    $entidade_id = 0;
    $imovel_id = 0;
    $entidade_nome = " ";
    $description = " ";
    $date = '"NULL"';
    $hour='00:00';
    $title_top = "Nova Angariação";
    $history = [];
    $ang_files=[];
}

$subquery ='(SELECT DISTINCT contato FROM contatos_imoveis as CTS WHERE imovel IN (SELECT idimo FROM angariacoes_imoveis as ANGI WHERE idang =' . $id . ') AND imovel<>0 AND id<>0)';
$entidades = $conn->query("SELECT * FROM entidades WHERE id IN (SELECT ident FROM angariacoes_entidades WHERE idang=" . $id . ")");
$imoveis = $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND id in (SELECT idimo FROM angariacoes_imoveis WHERE idang = " . $id . ")")->fetch_all(MYSQLI_ASSOC);
$all_imoveis =  $conn->query("SELECT * FROM imoveis WHERE iduser=" . $_SESSION['login'] . " AND ID NOT IN(SELECT imovel FROM contatos_imoveis WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
$all_entidades = $conn->query("SELECT * FROM entidades WHERE pertence_a=" . $_SESSION['login'] . " AND ID NOT IN(SELECT entidade FROM contatos_entidades WHERE contato IN " . $subquery . " AND deleted_at IS NULL)")->fetch_all(MYSQLI_ASSOC);
$filetypes = $conn->query("SELECT * FROM tipoficheiro")->fetch_all(MYSQLI_ASSOC);
$angstates = $conn->query("SELECT * FROM angariacoes_estados")->fetch_all(MYSQLI_ASSOC);
$selected_filetype = $conn->query("SELECT estado FROM angariacoes")->fetch_assoc()['estado'];
$selected_risco = $conn->query("SELECT risco FROM angariacoes")->fetch_assoc()['risco'];

?>


</html>
