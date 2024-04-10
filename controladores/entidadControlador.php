<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class entidadControlador extends mainModel{

        /*---------- Controlador agregar entidad ----------*/
        public function agregar_entidad_controlador(){
            
            $entidad    =   mainModel::limpiar_cadena(strtoupper($_POST['entidad_reg']));

            /*== comprobar campos vacios ==*/
            if($entidad ==  ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,100}",$entidad)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la entidad no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de la entidad ==*/
			$check_entidad=mainModel::ejecutar_consulta_simple("SELECT entidad FROM entidades WHERE entidad='$entidad'");
			if($check_entidad->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la entidad ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_entidad->closeCursor();
			$check_entidad  =   mainModel::desconectar($check_entidad);
            
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
			$datos_entidad_reg  = [
				"entidad" =>[
					"campo_marcador"=>":Entidad",
					"campo_valor"=>$entidad
				]
			];
            
            $agregar_entidad    =   mainModel::guardar_datos("entidades",$datos_entidad_reg);

			if($agregar_entidad->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Entidad registrada!",
					"Texto"=>"La entidad se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la entidad, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_entidad->closeCursor();
			$agregar_entidad=mainModel::desconectar($agregar_entidad);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador entidad ----------*/
		public function paginador_entidad_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM entidades WHERE entidad LIKE '%$busqueda%'  ORDER BY entidad ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM entidades ORDER BY entidad ASC LIMIT $inicio,$registros";
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
							<th>NOMBRE</th>
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
                            <td>'.$rows['entidad'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'entidad-update/'.mainModel::encryption($rows['entidadId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/entidadAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="entidadId_del" value="'.mainModel::encryption($rows['entidadId']).'">
									<input type="hidden" name="modulo_entidad" value="eliminar">
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
        

        /*---------- Controlador actualizar entidad ----------*/
		public function actualizar_entidad_controlador(){

            /*== Recuperando id de la entidad ==*/
			$id=mainModel::decryption($_POST['entidadId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_entidad  =   mainModel::ejecutar_consulta_simple("SELECT * FROM entidades WHERE entidadId='$id'");
            if($check_entidad->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la entidad en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_entidad->fetch();
			}
			$check_entidad->closeCursor();
			$check_entidad=mainModel::desconectar($check_entidad);
            
            $entidad=mainModel::limpiar_cadena(strtoupper($_POST['entidad_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$entidad)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la entidad no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de entidad ==*/
            if($entidad !=  $campos['entidad']){
                $check_nombre=mainModel::ejecutar_consulta_simple("SELECT entidad FROM entidades WHERE entidad='$entidad'");
                if($check_nombre->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de la entidad ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_nombre->closeCursor();
				$check_nombre=mainModel::desconectar($check_nombre);
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
			$datos_entidad_up=[
				"entidad"=>[
					"campo_marcador"=>":Entidad",
					"campo_valor"=>$entidad
				]
			];
 
			$condicion=[
				"condicion_campo"=>"entidadId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("entidades",$datos_entidad_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Entidad actualizada!",
					"Texto"=>"La entidad se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la entidad, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar entidad ----------*/
		public function eliminar_entidad_controlador(){

            /*== Recuperando id de la entidad ==*/
			$id=mainModel::decryption($_POST['entidadId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando entidad en la DB ==*/
            $check_entidad=mainModel::ejecutar_consulta_simple("SELECT entidadId FROM entidades WHERE entidadId='$id'");
            if($check_entidad->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La entidad que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_entidad->closeCursor();
			$check_entidad=mainModel::desconectar($check_entidad);

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

			$eliminar_entidad   =  mainModel::eliminar_registro("entidades","entidadId",$id);

			if($eliminar_entidad->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Entidad eliminada!",
					"Texto"=>"La entidad ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la entidad del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_entidad->closeCursor();
			$eliminar_entidad=mainModel::desconectar($eliminar_entidad);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }