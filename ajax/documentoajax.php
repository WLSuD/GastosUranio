<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_documento'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/documentoControlador.php";
        $ins_documento = new documentoControlador();

        /*--------- Agregar documento ---------*/
        if($_POST['modulo_documento']=="registrar"){
            echo $ins_documento->agregar_documento_controlador();
        }
        
        /*--------- Actualizar documento ---------*/
        if($_POST['modulo_documento']=="actualizar"){
            echo $ins_documento->actualizar_documento_controlador();
		}
        
        /*--------- Eliminar documento ---------*/
        if($_POST['modulo_documento']=="eliminar"){
            echo $ins_documento->eliminar_documento_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}