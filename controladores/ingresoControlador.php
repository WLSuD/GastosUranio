<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class ingresoControlador extends mainModel{

        /*---------- Controlador agregar ingreso ----------*/
        public function agregar_ingreso_controlador(){
            
            $descripcion        =   mainModel::limpiar_cadena(strtoupper($_POST['descripcion_reg']));
            $entidadId          =   mainModel::limpiar_cadena($_POST['entidadId_reg']);
            $obraId             =   mainModel::limpiar_cadena($_POST['obraId_reg']);
            $monto              =   mainModel::limpiar_cadena($_POST['monto_reg']);
            $fecha              =   mainModel::limpiar_cadena($_POST['fecha_reg']);


            /*== comprobar campos vacios ==*/
            if($descripcion ==  "" || $entidadId == "" || $obraId == "" || $monto == "" || $fecha == ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{10,200}",$descripcion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La descripcion del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[0-9.]{1,25}",$monto)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El monto del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

			if(mainModel::verificar_fecha($fecha)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El fecha de vencimiento del producto no es correcta.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
            
            /*== Comprobando la descripcion del ingreso ==*/
			$check_descripcion	=	mainModel::ejecutar_consulta_simple("SELECT descripcion FROM ingresos WHERE descripcion	=	'$descripcion'");
			if($check_descripcion->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La descripcion del ingreso ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_descripcion->closeCursor();
			$check_descripcion  =   mainModel::desconectar($check_descripcion);

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
			$datos_ingreso_reg  = [
				"descripcion" =>[
					"campo_marcador"=>":Descripcion",
					"campo_valor"=>$descripcion
                ],
                "entidadId" =>[
					"campo_marcador"=>":EntidadId",
					"campo_valor"=>$entidadId
				],
				"obraId" =>[
					"campo_marcador"=>":ObraId",
					"campo_valor"=>$obraId
				],
                "monto" =>[
					"campo_marcador"=>":Monto",
					"campo_valor"=>$monto
				],
                "fecha" =>[
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				]
			];
            
            $agregar_ingreso  =   mainModel::guardar_datos("ingresos",$datos_ingreso_reg);

			if($agregar_ingreso->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Ingreso registrada!",
					"Texto"=>"El ingreso se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el ingreso, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_ingreso->closeCursor();
			$agregar_ingreso=mainModel::desconectar($agregar_ingreso);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador ingreso ----------*/
		public function paginador_ingreso_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			/*--if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE proveedor LIKE '%$busqueda%'  ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}--*/

			$campos_tablas = "ingresos.ingresoId,ingresos.descripcion,ingresos.entidadId,ingresos.obraId,ingresos.monto,ingresos.fecha,ingresos.estado,
								entidades.entidadId,entidades.entidad,
								obras.obraId,obras.obra";

			$consulta="SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
						FROM ingresos INNER JOIN entidades ON ingresos.entidadId = entidades.entidadId
										INNER JOIN obras ON ingresos.obraId = obras.obraId
						WHERE ingresos.obraId = $busqueda
						ORDER BY ingresos.ingresoId DESC LIMIT $inicio,$registros";

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
				<div class="table-responsive">
				<table id="example1" class="table table-dark table-sm" style="width:100%">
					<thead>
						<tr class="text-center">
							<th>#</th>
							<th>DESCRIPCION</th>
							<th>ENTIDAD</th>
							<th>OBRA</th>
							<th>MONTO</th>
							<th>FECHA</th>
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
                            <td>'.$rows['descripcion'].'</td>
							<td>'.$rows['entidad'].'</td>
							<td>'.$rows['obra'].'</td>
							<td>'.number_format($rows['monto'],2,'.',',').'</td>
							<td>'.$rows['fecha'].'</td>';
							if($rows['estado']=='libre'){ 
								$tabla.='
									<td>
										<a class="btn btn-success" href="'.SERVERURL.'ingreso-update/'.mainModel::encryption($rows['ingresoId']).'/" >
											<i class="fas fa-sync fa-fw"></i>
										</a>
									</td>
									<td>
										<form class="FormularioAjax" action="'.SERVERURL.'ajax/ingresoAjax.php" method="POST" data-form="delete" autocomplete="off" >
											<input type="hidden" name="ingresoId_del" value="'.mainModel::encryption($rows['ingresoId']).'">
											<input type="hidden" name="modulo_ingreso" value="eliminar">
											<button type="submit" class="btn btn-warning">
												<i class="far fa-trash-alt"></i>
											</button>
										</form>
									</td>';
							}else{
								$tabla.='
									<td>
										Ingreso
									</td>
									<td>
										Asignado
									</td>';
							}
						$tabla.='
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

			/*if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando proveedores <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar ingreso ----------*/
		public function actualizar_ingreso_controlador(){

            /*== Recuperando id de la ingreso ==*/
			$id=mainModel::decryption($_POST['ingresoId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
            $check_ingreso	=	mainModel::ejecutar_consulta_simple("SELECT * FROM ingresos WHERE ingresoId='$id'");
            if($check_ingreso->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado el ingreso en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_ingreso->fetch();
			}
			$check_ingreso->closeCursor();
			$check_ingreso=mainModel::desconectar($check_ingreso);

			$descripcion        =   mainModel::limpiar_cadena(strtoupper($_POST['descripcion_up']));
            $entidadId          =   mainModel::limpiar_cadena($_POST['entidadId_up']);
            $obraId             =   mainModel::limpiar_cadena($_POST['obraId_up']);
            $monto              =   mainModel::limpiar_cadena($_POST['monto_up']);
            $fecha              =   mainModel::limpiar_cadena($_POST['fecha_up']);

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{10,200}",$descripcion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La descripcion del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[0-9.]{1,25}",$monto)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El monto del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

			if(mainModel::verificar_fecha($fecha)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El fecha de vencimiento del producto no es correcta.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

            /*== Comprobando la descripcion del ingreso ==*/
			if($descripcion!=$campos['descripcion']){
			$check_descripcion	=	mainModel::ejecutar_consulta_simple("SELECT descripcion FROM ingresos WHERE descripcion	=	'$descripcion'");
			if($check_descripcion->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La descripcion del ingreso ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_descripcion->closeCursor();
			$check_descripcion  =   mainModel::desconectar($check_descripcion);
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
			$datos_ingreso_up  = [
				"descripcion" =>[
					"campo_marcador"=>":Descripcion",
					"campo_valor"=>$descripcion
                ],
                "entidadId" =>[
					"campo_marcador"=>":EntidadId",
					"campo_valor"=>$entidadId
				],
				"obraId" =>[
					"campo_marcador"=>":ObraId",
					"campo_valor"=>$obraId
				],
                "monto" =>[
					"campo_marcador"=>":Monto",
					"campo_valor"=>$monto
				],
                "fecha" =>[
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				]
			];
 
			$condicion=[
				"condicion_campo"=>"ingresoId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("ingresos",$datos_ingreso_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Ingreso actualizado!",
					"Texto"=>"El ingreso se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos del ingreso, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar ingreso ----------*/
		public function eliminar_ingreso_controlador(){

            /*== Recuperando id de la ingreso ==*/
			$id=mainModel::decryption($_POST['ingresoId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
            $check_ingreso=mainModel::ejecutar_consulta_simple("SELECT ingresoId FROM ingresos WHERE ingresoId='$id'");
            if($check_ingreso->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El ingreso que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_ingreso->closeCursor();
			$check_ingreso=mainModel::desconectar($check_ingreso);

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

			$eliminar_ingreso	=	mainModel::eliminar_registro("ingresos","ingresoId",$id);

			if($eliminar_ingreso->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Ingreso eliminado!",
					"Texto"=>"El ingreso ha sido eliminado del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el ingreso del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_ingreso->closeCursor();
			$eliminar_ingreso=mainModel::desconectar($eliminar_ingreso);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }