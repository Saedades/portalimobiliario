<?php
$array = explode('/', $_SERVER['REQUEST_URI']);
$end = end($array);
function checkuri($uri, $string) {
    if(strcmp($string, $uri)==0) {
        echo ' active';
    }
}
function checkurigroup($uri, $array) {
    foreach($array as $item) {
        if(strcmp($item, $uri)==0) {
            echo ' active';
            break;
        }
    }
}
function checkurigroupshow($uri, $array) {
    foreach($array as $item) {
        if(strcmp($item, $uri)==0) {
            echo ' show';
            break;
        }
    }
}

$isadmin = $conn->query('SELECT admin FROM users WHERE id =' . $_SESSION['login'])->fetch_assoc()['admin'];
?>


<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/bootstrap4/php/profile/profile.php">
        <div class="sidebar-brand-text mx-3" hidden>Key <sup>2</sup></div>
		<div id="rgsymbol" style="background-image:url('../../img/rgconsultores.png'); background-size:contain; height:80px; width:100%; background-repeat:no-repeat; background-position:left;"></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item  <?php checkuri($end, '/bootstrap4/php/profile/profile.php')?>">
        <a class="nav-link" href="/bootstrap4/php/profile/profile.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Painel de Controlo</span>
		</a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Páginas
    </div>

    <li class="nav-item <?php checkurigroup($end, ['roles.php', 'documents.php', 'relations.php'])?>">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseentidades" aria-expanded="true" aria-controls="collapseUtilities" style="cursor:pointer">
            <i class="fas fa-address-book"></i>
            <span>Entidades</span>
        </a>
        <div id="collapseentidades" class="collapse <?php checkurigroupshow($end, ['/bootstrap4/php/imovel/imovel.php', '/bootstrap4/php/imovel/listaimoveis.php?mn=1', '/bootstrap4/php/imovel/listaimoveis.php?mn=2', '/bootstrap4/php/imovel/listaimoveis.php?mn=3'])?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/entidade/entidade.php') ?>" href="/bootstrap4/php/entidade/entidade.php?" <?php if($isadmin>=1) { echo ''; } else { echo 'hidden'; } ?>>  <span style="margin-left: 10px;" >Nova Entidade</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/entidade/listaentidades.php?mn=1') ?>" href="/bootstrap4/php/entidade/listaentidades.php?mn=1"><span style="margin-left: 10px;">As Minhas Entidades</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/entidade/listaentidades.php?mn=2') ?>" href="/bootstrap4/php/entidade/listaentidades.php?mn=2"><span style="margin-left: 10px;">Entidades da Rede</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/entidade/listaentidades.php?tt=2') ?>" href="/bootstrap4/php/entidade/listaentidades.php?tt=2"><span style="margin-left: 10px;">Leads</span></a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseimoveis" aria-expanded="true" aria-controls="collapseUtilities" style="cursor:pointer">
            <i class="fas fa-fw fa-home"></i>
            <span>Angariações</span>
        </a>
        <div id="collapseimoveis" class="collapse <?php checkurigroupshow($end, ['/bootstrap4/php/imovel/imovel.php', '/bootstrap4/php/imovel/listaimoveis.php?mn=1', '/bootstrap4/php/imovel/listaimoveis.php?mn=2', '/bootstrap4/php/imovel/listaimoveis.php?mn=3'])?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/imovel/imovel.php') ?>" href="/bootstrap4/php/imovel/imovel.php?">                     <span style="margin-left: 10px;">Novo Imóvel</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/imovel/listaimoveis.php?mn=1') ?>" href="/bootstrap4/php/imovel/listaimoveis.php?mn=1"><span style="margin-left: 10px;">Os Meus Imóveis</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/imovel/listaimoveis.php?mn=2') ?>" href="/bootstrap4/php/imovel/listaimoveis.php?mn=2"><span style="margin-left: 10px;">Imóveis da Rede</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/imovel/listaimoveis.php?mn=3') ?>" href="/bootstrap4/php/imovel/listaimoveis.php?mn=3"><span style="margin-left: 10px;">Imóveis Favoritos</span></a>
            </div>
        </div>
    </li>


    <li class="nav-item <?php checkuri($end, '/keychain/bootstrap4/php/contato/listacontatos.php')?>" hidden>
        <a class="nav-link" href="/keychain/bootstrap4/php/contato/listacontatos.php">
            <i class="fas fa-tasks"></i>
            <span>Contatos</span></a>
    </li>


    <li class="nav-item" hidden>
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseseguimentos" aria-expanded="true" aria-controls="collapseUtilities" style="cursor:pointer">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Seguimentos</span>
        </a>
        <div id="collapseseguimentos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/contato/contato.php') ?>" href="/bootstrap4/php/contato/contato.php?">                     <span style="margin-left: 10px;">Novo Seguimento</span></a>
                <a class="collapse-item <?php checkuri($end, '/bootstrap4/php/contato/listacontatos.php?mn=1') ?>" href="/bootstrap4/php/contato/contatos.php?mn=1"><span style="margin-left: 10px;">Os Meus Seguimentos</span></a>
            </div>
        </div>
    </li>

	<li class="nav-item <?php checkuri($end, '/keychain/bootstrap4/php/contrato/listacontratos.php')?>" hidden>
        <a class="nav-link" href="/keychain/bootstrap4/php/contrato/listacontratos.php">
            <i class="fas fa-sign-in-alt"></i>
            <span>Contratos</span></a>
    </li>

    <li class="nav-item <?php checkuri($end, '/keychain/bootstrap4/php/calendario/calendarpage.php')?>" hidden>
        <a class="nav-link" href="/keychain/bootstrap4/php/calendario/calendarpage.php">
        <i class="fas fa-calendar"></i>
            <span>Calendário</span></a>
    </li>



    <li class="nav-item <?php checkuri($end, 'statistics.php')?>" style="pointer-events: none;" hidden>
        <a class="nav-link" href="statistics.php">
        <i class="fas fa-chart-bar"></i>
            <span>Estatística</span></a>
    </li>

    <li class="nav-item <?php checkuri($end, 'history.php')?>" style="pointer-events: none;" hidden>
        <a class="nav-link" href="history.php">
        <i class="fas fa-history"></i>
            <span>História</span></a>
    </li>

    <div class="sidebar-heading" hidden>
        Definições
    </div>

    <li class="nav-item <?php checkurigroup($end, ['roles.php', 'documents.php', 'relations.php'])?>" <?php echo ($isadmin==0) ? 'hidden' : ''; ?>>
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities" style="cursor:pointer">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Objetos</span>
        </a>
        <div id="collapseUtilities" class="collapse <?php checkurigroupshow($end, ['classificadores.php'])?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item <?php checkuri($end, 'classificadores.php') ?>" href="/bootstrap4/php/objetos/classificadores.php" ><i class="fas fa-user-friends"></i><span style="margin-left: 10px;">Classificadores</span></a>
				<a class="collapse-item <?php checkuri($end, 'tiposdecontato.php') ?>" href="/bootstrap4/php/objetos/tiposdecontato.php" ><i class="fas fa-envelope"></i><span style="margin-left: 10px;">Tipos de contato</span></a>
                <!--<a class="collapse-item <?php //checkuri($end, 'roles.php') ?>" href="roles.php" ><i class="fas fa-id-badge"></i><span style="margin-left: 10px;">Cargos</span></a>-->
                <!--<a class="collapse-item <?php //checkuri($end, 'documents.php') ?>" href="documents.php" ><i class="fas fa-file-alt"></i><span style="margin-left: 10px;">Documentos</span></a>-->
                <!--<a class="collapse-item <?php //checkuri($end, 'relations.php') ?>" href="relations.php" ><i class="fas fa-user-friends"></i><span style="margin-left: 10px;">Relações</span></a>-->
            </div>
        </div>
    </li>

    <div class="text-center d-none d-md-inline" hidden>
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
