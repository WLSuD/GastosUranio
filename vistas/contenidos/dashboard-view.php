<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
    </h3>
    <p class="text-justify">
        ¡hola Bienvenido <strong><?php echo $_SESSION['nombre_svi']." ".$_SESSION['apellido_svi']; ?></strong>! Este es el panel principal del sistema acá podrá encontrar atajos para acceder a los distintos listados de cada módulo del sistema.
    </p>
</div>
<div class="container-fluid">
    <div class="full-box tile-container">
    <!-- INICIO VISTA DASHBOARD ADMINISTRADOR -->
    
    <?php
        if($_SESSION['cargo_svi']=="Administrador"){

            require_once "./controladores/dashboardControlador.php";
            $ins_dashboard = new dashboardControlador();
            echo $ins_dashboard->mostrar_obras_controlador();
        }
    ?>
    
    <!-- FIN VISTA DASHBOARD ADMINISTRADOR -->

    <!-- INICIO VISTA DASHBOARD CAJERO -->
    
    <?php
        if($_SESSION['cargo_svi']=="Cajero"){
    ?>
   

    <?php } ?>
    <!-- INICIO VISTA DASHBOARD CAJEROR -->
    </div>
</div>