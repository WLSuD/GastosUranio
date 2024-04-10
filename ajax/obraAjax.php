<?php
    $peticion_ajax = true;
    require_once "../config/APP.php";
    include "../vistas/inc/session_start.php";

    if(isset($_POST['modulo_obra'])){

        /*------------ instancia al controlador ---------*/
        require_once "../controladores/obraControlador.php";
        $ins_obra = new obraControlador();

        /*------------- agregar obra ---------------------*/
        if($_POST['modulo_obra'] == "registrar"){
            echo $ins_obra -> agregar_obra_controlador();
        }

        /*------------- actualizar obra ---------------- */
        if($_POST['modulo_obra'] == "actualizar"){
            echo $ins_obra -> actualizar_obra_controlador();
        }

        /*-------------- eliminar obra ----------------- */
        if($_POST['modulo_obra'] == "eliminar"){
            echo $ins_obra -> eliminar_obra_controlador();
        }

    }else{
        session_destroy();
        header("Location: ".SERVERURL."login/");
    }