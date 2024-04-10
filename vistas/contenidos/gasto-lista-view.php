<?php
    include "./vistas/inc/admin_security.php";

    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Lista de gasto
    </h3>
    <?php include "./vistas/desc/desc_regresos.php"; ?>
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>gasto-new/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Nuevo gasto
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>gasto-lista/">
                <i class="fas fa-sack-xmark fa-fw"></i> &nbsp; Lista de gastos
            </a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/gastoControlador.php";
        $ins_gasto = new gastoControlador();
        echo $ins_gasto->paginador_gasto_controlador($pagina[1],15,$pagina[0],$campos['obraId']);
    ?>
</div>

<?php } ?>