<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_gasto'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/gastoControlador.php";
        $ins_gasto = new gastoControlador();

        /*--------- Agregar gasto ---------*/
        if($_POST['modulo_gasto']=="registrar"){
            echo $ins_gasto->agregar_gasto_controlador();
        }
        
        /*--------- Actualizar gasto ---------*/
        if($_POST['modulo_gasto']=="actualizar"){
            echo $ins_gasto->actualizar_gasto_controlador();
		}
        
        /*--------- Eliminar gasto ---------*/
        if($_POST['modulo_gasto']=="eliminar"){
            echo $ins_gasto->eliminar_gasto_controlador();
		}

        /*-------- Programar gasto -------*/
        if($_POST['modulo_gasto'] == "programar"){
            echo $ins_gasto->programar_gasto_controlador();
        }

        /*-------- Quitar gasto -------*/
        if($_POST['modulo_gasto'] == "quitar"){
            echo $ins_gasto->quitar_gasto_controlador();
        }

        /*-------- Quitar gasto -------*/
        if($_POST['modulo_gasto'] == "pagar"){
            echo $ins_gasto->pagar_gasto_controlador();
        }

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}