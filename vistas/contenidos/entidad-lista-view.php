<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-landmark-flag fa-fw"></i> &nbsp; Lista de entidad
    </h3>
    <!--<?php include "./vistas/desc/desc_categoria.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>entidad-new/">
                <i class="fas fa-landmark-dome fa-fw"></i> &nbsp; Nueva entidad
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>entidad-list/">
                <i class="fas fa-landmark-flag fa-fw"></i> &nbsp; Lista de entidades
            </a>
        </li>
        <!--<li>
            <a href="<?php echo SERVERURL; ?>empresa-search/">
                <i class="fas fa-search fa-fw"></i> &nbsp; Buscar empresa
            </a>
        </li>-->
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/entidadControlador.php";
        $ins_entidad = new entidadControlador();

        echo $ins_entidad->paginador_entidad_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>