<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class asignacionControlador extends mainModel{

        /*---------- Controlador agregar asignacion ----------*/
        public function agregar_asignacion_controlador(){
            
            $asignacion    =   mainModel::limpiar_cadena(strtoupper($_POST['asignacion_reg']));

            /*== comprobar campos vacios ==*/
            if($asignacion ==  ""){
                $alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$asignacion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de asignacion no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de asignacion ==*/
			$check_asignacion=mainModel::ejecutar_consulta_simple("SELECT asignacion FROM asignaciones WHERE asignacion='$asignacion'");
			if($check_asignacion->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la asignacion ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_asignacion->closeCursor();
			$check_asignacion  =   mainModel::desconectar($check_asignacion);
            
            /*== Comprobando privilegios ==*/
			if($_SESSION['cargo_svi']!="Administrador"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes los permisos necesarios para realizar esta operación en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Preparando datos para enviarlos al modelo ==*/
			$datos_asignacion_reg  = [
				"asignacion" =>[
					"campo_marcador"=>":Asignacion",
					"campo_valor"=>$asignacion
				]
			];
            
            $agregar_asignacion=mainModel::guardar_datos("asignaciones",$datos_asignacion_reg);

			if($agregar_asignacion->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Asignacion registrada!",
					"Texto"=>"La asignacion se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la asignacion, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_asignacion->closeCursor();
			$agregar_asignacion=mainModel::desconectar($agregar_asignacion);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador asignacion ----------*/
		public function paginador_asignacion_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM asignaciones WHERE asignacion LIKE '%$busqueda%'  ORDER BY asignacion ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM asignaciones ORDER BY asignacion ASC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
				<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>#</th>
							<th>ASIGNACION</th>
							<th>ACTUALIZAR</th>
                            <th>ELIMINAR</th>
                        </tr>
					</thead>
					<tbody>
			';

			if($total>=1 && $pagina<=$Npaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="text-center" >
							<td>'.$contador.'</td>
                            <td>'.$rows['asignacion'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'asignacion-update/'.mainModel::encryption($rows['asignacionId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/asignacionAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="asignacion_id_del" value="'.mainModel::encryption($rows['asignacionId']).'">
									<input type="hidden" name="modulo_asignacion" value="eliminar">
									<button type="submit" class="btn btn-warning">
										<i class="far fa-trash-alt"></i>
									</button>
								</form>
                            </td>
                        </tr>
                    ';
                    $contador++;
				}
				$pag_final=$contador-1;
			}else{
				if($total>=1){
					$tabla.='
						<tr class="text-center" >
							<td colspan="7">
								<a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				}else{
					$tabla.='
						<tr class="text-center" >
							<td colspan="7">
								No hay registros en el sistema
							</td>
						</tr>
					';
				}
			}

			$tabla.='</tbody></table></div>';

			if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando categorías <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar asignacion ----------*/
		public function actualizar_asignacion_controlador(){

            /*== Recuperando id de la asignacion ==*/
			$id=mainModel::decryption($_POST['asignacion_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando asignacion en la DB ==*/
            $check_asignacion=mainModel::ejecutar_consulta_simple("SELECT * FROM asignaciones WHERE asignacionId='$id'");
            if($check_asignacion->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la asignacion en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_asignacion->fetch();
			}
			$check_asignacion->closeCursor();
			$check_asignacion=mainModel::desconectar($check_asignacion );
            
            $asignacion=mainModel::limpiar_cadena(strtoupper($_POST['asignacion_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$asignacion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de asignacion no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de asignacion ==*/
            if($asignacion  !=  $campos['asignacion']){
                $check_asignacion=mainModel::ejecutar_consulta_simple("SELECT asignacion FROM asignaciones WHERE asignacion='$asignacion'");
                if($check_asignacion->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de asignacion ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_asignacion->closeCursor();
				$check_asignacion=mainModel::desconectar($check_asignacion);
            }
            
            /*== Comprobando privilegios ==*/
			if($_SESSION['cargo_svi']!="Administrador"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes los permisos necesarios para realizar esta operación en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Preparando datos para enviarlos al modelo ==*/
			$datos_asignacion_up=[
				"asignacion"=>[
					"campo_marcador"=>":Asignacion",
					"campo_valor"=>$asignacion
				]
			];
 
			$condicion=[
				"condicion_campo"=>"asignacionId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("asignaciones",$datos_asignacion_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Asignacion actualizada!",
					"Texto"=>"La asignacion se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la asignacion, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar asignacion ----------*/
		public function eliminar_asignacion_controlador(){

            /*== Recuperando id de la asignacion ==*/
			$id=mainModel::decryption($_POST['asignacion_id_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando asignacion en la DB ==*/
            $check_asignacion=mainModel::ejecutar_consulta_simple("SELECT asignacionId FROM asignaciones WHERE asignacionId='$id'");
            if($check_asignacion->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La asignacion que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_asignacion->closeCursor();
			$check_asignacion=mainModel::desconectar($check_asignacion);

            /*== Comprobando privilegios ==*/
			if($_SESSION['cargo_svi']!="Administrador"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes los permisos necesarios para realizar esta operación en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$eliminar_asignacion=mainModel::eliminar_registro("asignaciones","asignacionId",$id);

			if($eliminar_asignacion->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Asignacion eliminada!",
					"Texto"=>"La asignacion ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la asignacion del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_asignacion->closeCursor();
			$eliminar_asignacion=mainModel::desconectar($eliminar_asignacion);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }