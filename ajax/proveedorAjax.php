<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_proveedor'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        /*--------- Agregar proveedor ---------*/
        if($_POST['modulo_proveedor']=="registrar"){
            echo $ins_proveedor->agregar_proveedor_controlador();
        }
        
        /*--------- Actualizar proveedor ---------*/
        if($_POST['modulo_proveedor']=="actualizar"){
            echo $ins_proveedor->actualizar_proveedor_controlador();
		}
        
        /*--------- Eliminar proveedor ---------*/
        if($_POST['modulo_proveedor']=="eliminar"){
            echo $ins_proveedor->eliminar_proveedor_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}