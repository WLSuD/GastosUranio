<?php
    include "./vistas/inc/admin_security.php";

    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Lista de ingreso
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>ingreso-new/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-hand-holding-dollar fa-fw"></i> &nbsp; Nuevo ingreso
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>ingreso-lista/">
                <i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Lista de ingresos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/ingresoControlador.php";
        $ins_ingreso = new ingresoControlador();

        echo $ins_ingreso->paginador_ingreso_controlador($pagina[1],15,$pagina[0],$campos['obraId']);
    ?>
</div>

<?php  } ?>