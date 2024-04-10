<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_asignacion'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/asignacionControlador.php";
        $ins_asignacion = new asignacionControlador();

        /*--------- Agregar empresa ---------*/
        if($_POST['modulo_asignacion']=="registrar"){
            echo $ins_asignacion->agregar_asignacion_controlador();
        }
        
        /*--------- Actualizar empresa ---------*/
        if($_POST['modulo_asignacion']=="actualizar"){
            echo $ins_asignacion->actualizar_asignacion_controlador();
		}
        
        /*--------- Eliminar empresa ---------*/
        if($_POST['modulo_asignacion']=="eliminar"){
            echo $ins_asignacion->eliminar_asignacion_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}