<?php
    $peticion_ajax=true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

	if(isset($_POST['modulo_subcategoria'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/subcategoriaControlador.php";
        $ins_subcategoria = new subcategoriaControlador();

        /*--------- Agregar categoria ---------*/
        if($_POST['modulo_subcategoria']=="registrar"){
            echo $ins_subcategoria->agregar_subcategoria_controlador();
        }
        
        /*--------- Actualizar categoria ---------*/
        if($_POST['modulo_subcategoria']=="actualizar"){
            echo $ins_subcategoria->actualizar_subcategoria_controlador();
		}
        
        /*--------- Eliminar categoria ---------*/
        if($_POST['modulo_subcategoria']=="eliminar"){
            echo $ins_subcategoria->eliminar_subcategoria_controlador();
		}

	}else{
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}