<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-people-carry-box fa-fw"></i> &nbsp; Nuevo proveedor
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>proveedor-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo proveedor
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>proveedor-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de proveedores
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        echo $ins_proveedor->paginador_proveedor_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>