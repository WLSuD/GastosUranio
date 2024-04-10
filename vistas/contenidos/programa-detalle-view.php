<?php
    include "./vistas/inc/admin_security.php";
?>
<?php
       
    $datos_programa   =   $lc->datos_tabla("Unico","programas","programaId",$pagina[1]);

    if($datos_programa->rowCount()==1){
        $campos=$datos_programa->fetch();
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-rectangle-list fa-fw"></i> &nbsp; <?php echo $campos['codigo'] ?>
    </h3>
 
    <h5>
        Saldo : &nbsp; S/. <?php echo number_format($campos['saldo'],2,'.',',') ?>
    </h5>
    <h6>
        Estado : &nbsp; S/. <?php echo $campos['estado']?>
    </h6>
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