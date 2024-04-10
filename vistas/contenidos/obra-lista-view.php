<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-trowel-bricks fa-fw"></i> &nbsp; Lista de obras
    </h3>
    <!--<?php include "./vistas/desc/desc_obra.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>obra-new/">
                <i class="fas fa-trowel fa-fw"></i> &nbsp; Nueva obra
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>obra-lista/">
                <i class="fas fa-trowel-bricks fa-fw"></i> &nbsp; Lista de obras
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/obraControlador.php";
        $ins_obra = new obraControlador();

        echo $ins_obra->paginador_obra_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>