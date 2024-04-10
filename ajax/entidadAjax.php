<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_entidad'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/entidadControlador.php";
        $ins_entidad = new entidadControlador();

        /*--------- Agregar entidad ---------*/
        if($_POST['modulo_entidad']=="registrar"){
            echo $ins_entidad->agregar_entidad_controlador();
        }
        
        /*--------- Actualizar entidad ---------*/
        if($_POST['modulo_entidad']=="actualizar"){
            echo $ins_entidad->actualizar_entidad_controlador();
		}
        
        /*--------- Eliminar entidad ---------*/
        if($_POST['modulo_entidad']=="eliminar"){
            echo $ins_entidad->eliminar_entidad_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}