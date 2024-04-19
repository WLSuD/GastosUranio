<?php
    include "./vistas/inc/admin_security.php";

    $datos_programa   =   $lc->datos_tabla("Unico","programas","programaId",$pagina[1]);

    if($datos_programa->rowCount()==1){
        $campos=$datos_programa->fetch();
?>
<div class="row">
    <div class="col-12 col-md-8">
        <h3 class="text-left">
            <i class="fas fa-rectangle-list fa-fw"></i> &nbsp;Detalle <?php echo $campos['codigo'] ?>
        </h3>
    </div>
    <?php include "./vistas/desc/desc_regresos.php"; ?>
</div>

<div class="full-box page-header row">
    <div class="col-12 col-md-6">
        <h5>
            Saldo : &nbsp; S/. <?php echo number_format($campos['saldo'],2,'.',',') ?>
        </h5>
        <h7>
            Estado : &nbsp; <?php echo $campos['estado']?>
        </h7>
    </div>
    <div class="col-12 col-md-6">
    <p class="text-right"><a href="#" class="btn btn-raised btn-info btn-go-back"><i class="fas fa-reply"></i> &nbsp; Regresar atrás</a></p>
    </div>
</div>
<?php 

 }

?>

<div class="container-fluid">

    <?php
        require_once "./controladores/gastoControlador.php";
        $ins_gasto = new gastoControlador();

        echo $ins_gasto->gasto_programados_controlador($pagina[1],15,$pagina[0],$campos['programaId']);
    ?>
</div>