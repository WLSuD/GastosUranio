<?php
    include "./vistas/inc/admin_security.php";
?>
<?php
       
    $datos_programa   =   $lc->datos_tabla("Unico","programas","programaId",$pagina[1]);

    if($datos_programa->rowCount()==1){
        $campos=$datos_programa->fetch();
?>

<div class="row">
    <div class="col-12 col-md-8">
        <h3 class="text-left">
            <i class="fas fa-rectangle-list fa-fw"></i> &nbsp;Programar Gasto
        </h3>
    </div>
    <?php include "./vistas/desc/desc_regresos.php"; ?>
</div>

<div class="full-box page-header row">
    <div class="col-12 col-md-6">
    <h4>
        Programa : &nbsp; <?php echo $campos['codigo'] ?>
    </h4>
    <h5>
        Saldo : &nbsp; S/. <?php echo number_format($campos['saldo'],2,'.',',') ?>
    </h5>
    </div>
    <div class="col-12 col-md-6">
        <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
            <li>
                <a href="<?php echo SERVERURL; ?>programa-detalle/<?php echo $lc->encryption($campos['programaId'])?>/">
                    <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Detalle
                </a>
            </li>
        </ul>
    </div>
</div>
<?php 

 }

?>


<div class="container-fluid">

    <?php
        require_once "./controladores/gastoControlador.php";
        $ins_gasto = new gastoControlador();

        echo $ins_gasto->mostrar_gasto_controlador($pagina[1],15,$pagina[0],$campos['programaId']);
    ?>
</div>