<?php
    include "./vistas/inc/admin_security.php";
    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);
    $campos=$datos_obra->fetch(); 
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fa fa-diagram-project fa-fw"></i> &nbsp; MENU DE PROCESOS
    </h3>
    <p class="text-justify">Ejecute uno de los procesos principales en la obra <strong><?php echo $campos['abreviatura'] ?></strong>
    <div class="row">
        <div class="col-12 col-md-4">
        </div>
        <div class="col-12 col-md-4">
            <p class="text-right"><a href="<?php echo SERVERURL; ?>dashboard/" class="btn  btn-dark"><i class="fab fa-dashcube"></i> &nbsp; Regresar a dasboard</a></p>
        </div>
        <div class="col-12 col-md-4">
        </div>
    </div>
    
</div>
<div class="container-fluid">
    <div class="full-box tile-container">
<?php
    require_once "./controladores/dashboardControlador.php";
    $ins_dashboard = new dashboardControlador();
    echo $ins_dashboard->mostrar_menu_controlador($campos['obraId']);
?>
    </div>
</div>
<?php   ?>