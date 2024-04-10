<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_ingreso'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/ingresoControlador.php";
        $ins_ingreso = new ingresoControlador();

        /*--------- Agregar ingreso ---------*/
        if($_POST['modulo_ingreso']=="registrar"){
            echo $ins_ingreso->agregar_ingreso_controlador();
        }
        
        /*--------- Actualizar ingreso ---------*/
        if($_POST['modulo_ingreso']=="actualizar"){
            echo $ins_ingreso->actualizar_ingreso_controlador();
		}
        
        /*--------- Eliminar ingreso ---------*/
        if($_POST['modulo_ingreso']=="eliminar"){
            echo $ins_ingreso->eliminar_ingreso_controlador();
		}
	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}