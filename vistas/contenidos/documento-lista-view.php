<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo documento
    </h3>
    <!--<?php include "./vistas/desc/desc_empresa.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>documento-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo documento
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>documento-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de documentos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/documentoControlador.php";
        $ins_documento = new documentoControlador();

        echo $ins_documento->paginador_documento_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>