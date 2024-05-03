<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class dashboardControlador extends mainModel{

        //muestra las obras activas en cajas para selecionarlas y entrar a los procesos
        public function mostrar_obras_controlador(){

            $tabla = "";

            //consulta de obras
            $consulta = "SELECT * FROM obras WHERE estadoObra = 'activo'";
            $conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();
            
            foreach($datos as $rows){
                $tabla .= '<a href="'.SERVERURL.'programa-menu/'.mainModel::encryption($rows['obraId']).'/" class="tile">
                <div class="tile-tittle">'.$rows['abreviatura'].'</div>
                <div class="tile-icon">
                    <!-- <i class="fas fa-trowel-bricks"></i> -->
                    <img src="'.$rows['logoObra'].'" alt="img-logo" >
                </div>
            </a>';
            }

            /*foreach($datos as $rows){
                $tabla .= '<a href="'.SERVERURL.'programa-new/'.mainModel::encryption($rows['obraId']).'/" class="tile">
                <div class="tile-tittle">'.$rows['abreviatura'].'</div>
                <div class="tile-icon">
                    <!-- <i class="fas fa-trowel-bricks"></i> -->
                    <img src="'.$rows['logoObra'].'" alt="img-logo" >
                </div>
            </a>';
            }*/
            return $tabla;
        }

        
        //muestra los procesos a realizar en la obra selccionada
        public function mostrar_menu_controlador($obraId){
            
            $obraId =mainModel::limpiar_cadena($obraId);

            $tabla = "";

            $tabla .= '
            <a href="'.SERVERURL.'gasto-lista/'.mainModel::encryption($obraId).'/" class="tile">
                <div class="tile-tittle">GASTOS</div>
                <br><br>
                <div class="tile-icon">
                    <i class="fas fa-sack-xmark"></i>
                </div>
            </a>
            <a href="'.SERVERURL.'programa-lista/'.mainModel::encryption($obraId).'/" class="tile">
                <div class="tile-tittle">PROGRAMAS</div>
                <br><br>
                <div class="tile-icon">
                    <i class="fas fa-calendar-days"></i>
                </div>
            </a>
            <a href="'.SERVERURL.'ingreso-lista/'.mainModel::encryption($obraId).'/" class="tile">
                <div class="tile-tittle">INGRESOS</div>
                <br><br>
                <div class="tile-icon">
                    <i class="fas fa-sack-dollar"></i>
                </div>
            </a>';
            
            return $tabla;
        }

    }