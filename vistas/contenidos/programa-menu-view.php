<?php
    include "./vistas/inc/admin_security.php";
    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);
    $campos=$datos_obra->fetch(); 
?>
<div class="row">
    <div class="col-12 col-md-8">
        <h3 class="text-left">
            <i class="fa fa-diagram-project fa-fw"></i> &nbsp; MENU DE PROCESOS
        </h3>
        <p class="text-justify">Ejecute uno de los procesos principales en la obra <strong><?php echo $campos['abreviatura'] ?></strong>
    </div>
    <div class="col-12 col-md-4">
        <div class="col-12 col-md-4 " style="text-align: center !important; ">
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <i class="fab fa-dashcube fa-fw"></i> <a style="padding: 0 5px 0 5px; border: 1px solid #000" href="<?php echo SERVERURL; ?>dashboard/" class="btn  btn-dark">Dashboard</a>
            </div>
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