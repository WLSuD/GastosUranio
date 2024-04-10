<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_programa'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/programaControlador.php";
        $ins_programa = new programaControlador();

        /*--------- Agregar programa ---------*/
        if($_POST['modulo_programa']=="registrar"){
            echo $ins_programa->agregar_programa_controlador();
        }
        
        /*--------- Actualizar programa ---------*/
        if($_POST['modulo_programa']=="actualizar"){
            echo $ins_programa->actualizar_programa_controlador();
		}
        
        /*--------- Eliminar programa ---------*/
        if($_POST['modulo_programa']=="eliminar"){
            echo $ins_programa->eliminar_programa_controlador();
		}

        /*--------- CERRAR programa ---------*/
        if($_POST['modulo_programa']=="cerrar"){
            echo $ins_programa->cerrar_programa_controlador();
        }
	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}