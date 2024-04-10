<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de categorías
    </h3>
    <?php include "./vistas/desc/desc_categoria.php"; ?>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>category-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva categoría
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>category-list/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de categorías
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>category-search/">
                <i class="fas fa-search fa-fw"></i> &nbsp; Buscar categoría
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/categoriaControlador.php";
        $ins_categoria = new categoriaControlador();

        echo $ins_categoria->paginador_categoria_controlador($pagina[1],15,$pagina[0],"");
    ?>
</div>