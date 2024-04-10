<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de subcategorías
    </h3>
    <!--<?php include "./vistas/desc/desc_subcategoria.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>subcategoria-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva subcategoría
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>subcategoria-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de subcategorías
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/subcategoriaControlador.php";
        $ins_categoria = new subcategoriaControlador();

        echo $ins_categoria->paginador_subcategoria_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>