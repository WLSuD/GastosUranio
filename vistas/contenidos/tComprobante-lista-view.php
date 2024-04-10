<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo tipo de comprobante
    </h3>
    <!--<?php include "./vistas/desc/desc_empresa.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a  href="<?php echo SERVERURL; ?>tComprobante-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo tipo de comprobante
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>tComprobante-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de tipo de comprobantes
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/tComprobanteControlador.php";
        $ins_asignacion = new tcomprobanteControlador();

        echo $ins_asignacion->paginador_tComprobante_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>