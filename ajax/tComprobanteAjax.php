<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_tComprobante'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/tComprobanteControlador.php";
        $ins_tComprobante = new tComprobanteControlador();

        /*--------- Agregar tComprobante ---------*/
        if($_POST['modulo_tComprobante']=="registrar"){
            echo $ins_tComprobante->agregar_tComprobante_controlador();
        }
        
        /*--------- Actualizar tComprobante ---------*/
        if($_POST['modulo_tComprobante']=="actualizar"){
            echo $ins_tComprobante->actualizar_tComprobante_controlador();
		}
        
        /*--------- Eliminar tComprobante ---------*/
        if($_POST['modulo_tComprobante']=="eliminar"){
            echo $ins_tComprobante->eliminar_tComprobante_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}