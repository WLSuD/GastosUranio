<?php
    include "./vistas/inc/admin_security.php";
    
    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-calendar-minus fa-fw"></i> &nbsp; Lista de programas
    </h3>
    <?php include "./vistas/desc/desc_regresos.php"; ?>
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>programa-new/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-calendar-plus fa-fw"></i> &nbsp; Nuevo programa
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>programa-lista/">
                <i class="fas fa-calendar-minus fa-fw"></i> &nbsp; Lista de programas
            </a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/programaControlador.php";
        $ins_programa = new programaControlador();

        echo $ins_programa->paginador_programa_controlador($pagina[1],15,$pagina[0],$campos['obraId']);
    ?>
</div>
<?php  } ?>