<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class gastoControlador extends mainModel{

        /*---------- Controlador agregar gasto ----------*/
        public function agregar_gasto_controlador(){
            
            $detalle            =   mainModel::limpiar_cadena(strtoupper($_POST['detalle_reg']));
            $monto              =   mainModel::limpiar_cadena($_POST['monto_reg']);
            $fecha              =   mainModel::limpiar_cadena($_POST['fecha_reg']);
            $categoriaId        =   mainModel::limpiar_cadena($_POST['categoriaId_reg']);
            $subcatId           =   mainModel::limpiar_cadena($_POST['subcatId_reg']);
			$asignacionId		=	mainModel::limpiar_cadena($_POST['asignacionId_reg']);
            $tipoGastoId        =   mainModel::limpiar_cadena($_POST['tipoGastoId_reg']);
			$empresaId          =   mainModel::limpiar_cadena($_POST['empresaId_reg']);
            $obraId             =   mainModel::limpiar_cadena($_POST['obraId_reg']);
            $proveedorId        =   mainModel::limpiar_cadena($_POST['proveedorId_reg']);
            $tipoComId          =   mainModel::limpiar_cadena($_POST['tipoComId_reg']);
            $numeroCom          =   mainModel::limpiar_cadena(strtoupper($_POST['numeroCom_reg']));


            

            /*== comprobar campos vacios ==*/
            if($detalle ==  ""  || $monto == "" || $fecha == "" || $categoriaId == "" || $subcatId == "" || $asignacionId == ""
				|| $tipoGastoId == "" || $empresaId == "" || $obraId == "" || $proveedorId == "" || $tipoComId == "" || $numeroCom == "" 
            	){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#º\- ]{10,200}",$detalle)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle del gasto no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{3,25}",$numeroCom)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle del gasto no coincide con el formato solicitado",
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
            
            /*== Comprobando la descripcion del detalle ==*/
			$check_detalle	=	mainModel::ejecutar_consulta_simple("SELECT detalle FROM gastos WHERE detalle	=	'$detalle'");
			if($check_detalle->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle del gasto ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_detalle->closeCursor();
			$check_detalle  =   mainModel::desconectar($check_detalle);

            /*== Comprobando la descripcion del ingreso ==*/
			$check_numeroCom	=	mainModel::ejecutar_consulta_simple("SELECT numeroCom FROM gastos WHERE numeroCom	=	'$numeroCom' AND proveedorId = '$proveedorId' " );
			if($check_numeroCom->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El numero del comprobante del proveedor ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_numeroCom->closeCursor();
			$check_numeroCom  =   mainModel::desconectar($check_numeroCom);

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
			$datos_gasto_reg  = [
				"detalle" =>[
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$detalle
                ],
				"monto" =>[
					"campo_marcador"=>":Monto",
					"campo_valor"=>$monto
				],
                "fecha" =>[
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				],
                "categoriaId" =>[
					"campo_marcador"=>":CategoriaId",
					"campo_valor"=>$categoriaId
				],
                "subcatId" =>[
					"campo_marcador"=>":SubcatId",
					"campo_valor"=>$subcatId
				],
				"asignacionId" =>[
					"campo_marcador"=>":AsignacionId",
					"campo_valor"=>$asignacionId
				],
                "tipoGastoId" =>[
					"campo_marcador"=>":TipoGastoId",
					"campo_valor"=>$tipoGastoId
				],
                "empresaId" =>[
					"campo_marcador"=>":EmpresaId",
					"campo_valor"=>$empresaId
				],
				"obraId" =>[
					"campo_marcador"=>":ObraId",
					"campo_valor"=>$obraId
				],
                "proveedorId" =>[
					"campo_marcador"=>":ProveedorId",
					"campo_valor"=>$proveedorId
				],
                "tipoComId" =>[
					"campo_marcador"=>":TipoComId",
					"campo_valor"=>$tipoComId
				],
                "numeroCom" =>[
                    "campo_marcador" => ":NumeroCom",
                    "campo_valor" => $numeroCom
                ]
			];
            
            $agregar_gasto	=	mainModel::guardar_datos("gastos",$datos_gasto_reg);

			if($agregar_gasto->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Gasto registrado!",
					"Texto"=>"El gasto se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el gasto, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_gasto->closeCursor();
			$agregar_gasto=mainModel::desconectar($agregar_gasto);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador gasto ----------*/
		public function paginador_gasto_controlador($pagina,$registros,$url,$busqueda){

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

			$campos_tablas = "gastos.gastoId,gastos.detalle,gastos.monto,gastos.fecha,gastos.numeroCom,programaId,
								categorias.categoriaId,categorias.categoria,
								sub_categorias.subcatId,sub_categorias.subcategoria,
								asignaciones.asignacionId,asignaciones.asignacion,
								tipo_gastos.tipoGastoId,tipo_gastos.tipoGasto,
								empresas.empresaId, empresas.empresa,
								obras.obraId,obras.obra,
								proveedores.proveedorId, proveedores.proveedor,
								tipo_comprobantes.tipoComId,tipo_comprobantes.codigo
								";

			$consulta	=	"SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
							FROM gastos INNER JOIN categorias 			ON gastos.categoriaId 	= categorias.categoriaId
										INNER JOIN sub_categorias 		ON gastos.subcatId 		= sub_categorias.subcatId
										INNER JOIN asignaciones 		ON gastos.asignacionId 	= asignaciones.asignacionId
										INNER JOIN tipo_gastos			ON gastos.tipogastoId	= tipo_gastos.tipoGastoId
										INNER JOIN empresas				ON gastos.empresaId		= empresas.empresaId
										INNER JOIN obras 				ON gastos.obraId 		= obras.obraId
										INNER JOIN proveedores			ON gastos.proveedorId	= proveedores.proveedorId
										INNER JOIN tipo_comprobantes	ON gastos.tipoComId		= tipo_comprobantes.tipoComId
							WHERE gastos.obraId = $busqueda
							ORDER BY gastos.gastoId DESC LIMIT $inicio,$registros";

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
				<div class="table-responsive">
				<table  id="example1" class="table table-dark table-sm" style="width:100%">
					<thead>
						<tr class="text-center ">
							<th>#</th>
							<th>FECHA</th>
							<th>DETALLE</th>
							<th>PROVEEDOR</th>
							<th>EMPRESA</th>
							<th>MONTO</th>
							<th>DOCUMENTO</th>
							<th>Nº</th>
							<th>EDITAR</th>
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
                            <td>'.$rows['fecha'].'</td>
							<td>'.$rows['detalle'].'</td>
							<td>'.$rows['proveedor'].'</td>
							<td>'.$rows['empresa'].'</td>
							<td>'.number_format($rows['monto'],2,'.',',').'</td>
							<td>'.$rows['codigo'].'</td>
							<td>'.$rows['numeroCom'].'</td>';
							if($rows['programaId'] == NULL){
								$tabla.='
									<td>
										<a class="btn btn-success" href="'.SERVERURL.'gasto-update/'.mainModel::encryption($rows['gastoId']).'/" >
											<i class="fas fa-sync fa-fw"></i>
										</a>
									</td>
									<td>
										<form class="FormularioAjax" action="'.SERVERURL.'ajax/gastoAjax.php" method="POST" data-form="delete" autocomplete="off" >
											<input type="hidden" name="gastoId_del" value="'.mainModel::encryption($rows['gastoId']).'">
											<input type="hidden" name="modulo_gasto" value="eliminar">
											<button type="submit" class="btn btn-warning">
												<i class="far fa-trash-alt"></i>
											</button>
										</form>
									</td>';
							}else{
								$tabla.='
									<td>
										Gasto
									</td>
									<td>
										Programado
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
				$tabla.='<p class="text-right">Mostrando gastos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar gasto ----------*/
		public function actualizar_gasto_controlador(){

            /*== Recuperando id de la gasto ==*/
			$id=mainModel::decryption($_POST['gastoId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando gasto en la DB ==*/
            $check_gasto	=	mainModel::ejecutar_consulta_simple("SELECT * FROM gastos WHERE gastoId='$id'");
            if($check_gasto->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado el gasto en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_gasto->fetch();
			}
			$check_gasto->closeCursor();
			$check_gasto=mainModel::desconectar($check_gasto);

            $detalle            =   mainModel::limpiar_cadena(strtoupper($_POST['detalle_up']));
            $monto              =   mainModel::limpiar_cadena($_POST['monto_up']);
            $fecha              =   mainModel::limpiar_cadena($_POST['fecha_up']);
            $categoriaId        =   mainModel::limpiar_cadena($_POST['categoriaId_up']);
            $subcatId           =   mainModel::limpiar_cadena($_POST['subcatId_up']);
			$asignacionId		=	mainModel::limpiar_cadena($_POST['asignacionId_up']);
            $tipoGastoId        =   mainModel::limpiar_cadena($_POST['tipoGastoId_up']);
			$empresaId          =   mainModel::limpiar_cadena($_POST['empresaId_up']);
            $obraId             =   mainModel::limpiar_cadena($_POST['obraId_up']);
            $proveedorId        =   mainModel::limpiar_cadena($_POST['proveedorId_up']);
            $tipoComId          =   mainModel::limpiar_cadena($_POST['tipoComId_up']);
            $numeroCom          =   mainModel::limpiar_cadena(strtoupper($_POST['numeroCom_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#º\- ]{10,200}",$detalle)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle del gasto no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{3,25}",$numeroCom)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle del gasto no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[0-9.,]{1,25}",$monto)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El monto del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
			// cambio de formato del monto
			$monto = number_format($monto,2,'.','');

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
            
            /*== Comprobando la descripcion del detalle ==*/
			if($detalle!=$campos['detalle']){
				$check_detalle	=	mainModel::ejecutar_consulta_simple("SELECT detalle FROM gastos WHERE detalle	=	'$detalle'");
				if($check_detalle->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El detalle del gasto ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
				$check_detalle->closeCursor();
				$check_detalle  =   mainModel::desconectar($check_detalle);
			}

            /*== Comprobando la descripcion del ingreso ==*/
			if($numeroCom != $campos['numeroCom'] && $proveedorId != $campos['proveedorId'] ){
				$check_numeroCom	=	mainModel::ejecutar_consulta_simple("SELECT numeroCom FROM gastos WHERE numeroCom	=	'$numeroCom' AND proveedorId = '$proveedorId' " );
				if($check_numeroCom->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El numero del comprobante del proveedor ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
				$check_numeroCom->closeCursor();
				$check_numeroCom  =   mainModel::desconectar($check_numeroCom);
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
			$datos_gasto_up  = [
				"detalle" =>[
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$detalle
				],
				"monto" =>[
					"campo_marcador"=>":Monto",
					"campo_valor"=>$monto
				],
				"fecha" =>[
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				],
				"categoriaId" =>[
					"campo_marcador"=>":CategoriaId",
					"campo_valor"=>$categoriaId
				],
				"subcatId" =>[
					"campo_marcador"=>":SubcatId",
					"campo_valor"=>$subcatId
				],
				"asignacionId" =>[
					"campo_marcador"=>":AsignacionId",
					"campo_valor"=>$asignacionId
				],
				"tipoGastoId" =>[
					"campo_marcador"=>":TipoGastoId",
					"campo_valor"=>$tipoGastoId
				],
				"empresaId" =>[
					"campo_marcador"=>":EmpresaId",
					"campo_valor"=>$empresaId
				],
				"obraId" =>[
					"campo_marcador"=>":ObraId",
					"campo_valor"=>$obraId
				],
				"proveedorId" =>[
					"campo_marcador"=>":ProveedorId",
					"campo_valor"=>$proveedorId
				],
				"tipoComId" =>[
					"campo_marcador"=>":TipoComId",
					"campo_valor"=>$tipoComId
				],
				"numeroCom" =>[
					"campo_marcador" => ":NumeroCom",
					"campo_valor" => $numeroCom
				]
			];
 
			$condicion=[
				"condicion_campo"=>"gastoId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("gastos",$datos_gasto_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Gasto actualizado!",
					"Texto"=>"El gasto se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos del gasto, por favor intente nuevamente",
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

		/*---------- Controlador mostrar gastos en programa ----------*/
		public function mostrar_gasto_controlador($pagina,$registros,$url,$programaId){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			//obtengo el id de la obra para filtrar los gastos a programar
			$programaId=mainModel::limpiar_cadena($programaId);
			$datos_programas = mainModel::ejecutar_consulta_simple_publica("SELECT * FROM programas WHERE programaId = $programaId"); 
			$campos_programa = $datos_programas->fetch();
			$obraId = $campos_programa['obraId'];

			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			/*--if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE proveedor LIKE '%$busqueda%'  ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}--*/

			$campos_tablas = "gastos.gastoId,gastos.detalle,gastos.monto,gastos.fecha,gastos.numeroCom,gastos.programaId,
								categorias.categoriaId,categorias.categoria,
								sub_categorias.subcatId,sub_categorias.subcategoria,
								asignaciones.asignacionId,asignaciones.asignacion,
								tipo_gastos.tipoGastoId,tipo_gastos.tipoGasto,
								empresas.empresaId, empresas.empresa,
								obras.obraId,obras.obra,
								proveedores.proveedorId, proveedores.proveedor,
								tipo_comprobantes.tipoComId,tipo_comprobantes.codigo
								";
        	
			$consulta	=	"SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
							FROM gastos INNER JOIN categorias 			ON gastos.categoriaId 	= categorias.categoriaId
										INNER JOIN sub_categorias 		ON gastos.subcatId 		= sub_categorias.subcatId
										INNER JOIN asignaciones 		ON gastos.asignacionId 	= asignaciones.asignacionId
										INNER JOIN tipo_gastos			ON gastos.tipogastoId	= tipo_gastos.tipoGastoId
										INNER JOIN empresas				ON gastos.empresaId		= empresas.empresaId
										INNER JOIN obras 				ON gastos.obraId 		= obras.obraId
										INNER JOIN proveedores			ON gastos.proveedorId	= proveedores.proveedorId
										INNER JOIN tipo_comprobantes	ON gastos.tipoComId		= tipo_comprobantes.tipoComId
							WHERE gastos.programaId IS NULL AND gastos.obraId = $obraId
							ORDER BY gastos.gastoId DESC LIMIT $inicio,$registros";

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);
		

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);
		
			### Cuerpo de la tabla ###
			$tabla.='
				<div class="table-responsive">
				<table  id="example1" class="table table-dark table-sm">
					<thead>
						<tr class="text-center ">
							<th>#</th>
							<th>FECHA</th>
							<th>DETALLE</th>
							<th>PROVEEDOR</th>
							<th>EMPRESA</th>
							<th>MONTO</th>
							<th>DOCUMENTO</th>
							<th>Nº</th>
							<th>PROGRAMAR</th>
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
							<td>'.$rows['fecha'].'</td>
							<td>'.$rows['detalle'].'</td>
							<td>'.$rows['proveedor'].'</td>
							<td>'.$rows['empresa'].'</td>
							<td>'.number_format($rows['monto'],2,'.',',').'</td>
							<td>'.$rows['codigo'].'</td>
							<td>'.$rows['numeroCom'].'</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/gastoAjax.php" method="POST" data-form="programar" autocomplete="off" >
									<input type="hidden" name="gastoId_pro" value="'.mainModel::encryption($rows['gastoId']).'">
									<input type="hidden" name="programaId_pro" value="'.$programaId.'">
									<input type="hidden" name="monto_pro" value="'.$rows['monto'].'">
									<input type="hidden" name="modulo_gasto" value="programar">
									<button type="submit" class="btn btn-success">
										<i class="far fa-square-check"></i>
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

			/*if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando gastos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
		
		} /*-- Fin controlador --*/

		/*---------- Controlador programar gasto ----------*/
		public function programar_gasto_controlador(){

			/*== Recuperando id de la ingreso ==*/
			$id=mainModel::decryption($_POST['gastoId_pro']);
			$id=mainModel::limpiar_cadena($id);
			$monto_gasto = mainModel::limpiar_cadena($_POST['monto_pro']);

			$programaId		= mainModel::limpiar_cadena($_POST['programaId_pro']);

			/*== Comprobando proveedor en la DB ==*/
			$check_gasto=mainModel::ejecutar_consulta_simple("SELECT gastoId FROM gastos WHERE gastoId='$id'");
			if($check_gasto->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El gasto que intenta programar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_gasto->closeCursor();
			$check_gasto=mainModel::desconectar($check_gasto);

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

			//cambio de saldo de programa
			$cambio_saldo	 = mainModel::cambio_saldo("programar","programas","saldo",$monto_gasto,"programaId",$programaId);
			if($cambio_saldo->rowCount()==1){ 
				$programar_gasto	=	mainModel::cambio_estado("gastos","programaId",$programaId,"gastoId",$id);
				if($programar_gasto->rowCount()==1){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Gasto Programado!",
						"Texto"=>"El gasto ha sido programado del sistema exitosamente.",  
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido programar el gasto del sistema, por favor intente nuevamente.",
						"Tipo"=>"error"
					];
				}

				$programar_gasto->closeCursor();
				$programar_gasto=mainModel::desconectar($programar_gasto);
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido programar el gasto del sistema, saldo insuficiente.",
					"Tipo"=>"error"
				];
			}
			$cambio_saldo->closeCursor();

			echo json_encode($alerta);
		} /*-- Fin controlador --*/

		/*---------- Controlador gastos de un programa ----------*/
		public function gasto_programados_controlador($pagina,$registros,$url,$programaId){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$programaId=mainModel::limpiar_cadena($programaId);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			/*--if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE proveedor LIKE '%$busqueda%'  ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedores ORDER BY proveedor ASC LIMIT $inicio,$registros";
			}--*/

			$campos_tablas = "gastos.gastoId,gastos.detalle,gastos.monto,gastos.fecha,gastos.numeroCom,gastos.programaId,gastos.estadoGasto,
								categorias.categoriaId,categorias.categoria,
								sub_categorias.subcatId,sub_categorias.subcategoria,
								asignaciones.asignacionId,asignaciones.asignacion,
								tipo_gastos.tipoGastoId,tipo_gastos.tipoGasto,
								empresas.empresaId, empresas.empresa,
								obras.obraId,obras.obra,
								proveedores.proveedorId, proveedores.proveedor,
								tipo_comprobantes.tipoComId,tipo_comprobantes.codigo,
								programas.programaId,programas.estado
								";

			$consulta	=	"SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
							FROM gastos INNER JOIN categorias 			ON gastos.categoriaId 	= categorias.categoriaId
										INNER JOIN sub_categorias 		ON gastos.subcatId 		= sub_categorias.subcatId
										INNER JOIN asignaciones 		ON gastos.asignacionId 	= asignaciones.asignacionId
										INNER JOIN tipo_gastos			ON gastos.tipogastoId	= tipo_gastos.tipoGastoId
										INNER JOIN empresas				ON gastos.empresaId		= empresas.empresaId
										INNER JOIN obras 				ON gastos.obraId 		= obras.obraId
										INNER JOIN proveedores			ON gastos.proveedorId	= proveedores.proveedorId
										INNER JOIN tipo_comprobantes	ON gastos.tipoComId		= tipo_comprobantes.tipoComId
										INNER JOIN programas			ON gastos.programaId	= programas.programaId
							WHERE gastos.programaId = $programaId
							ORDER BY gastos.gastoId DESC LIMIT $inicio,$registros";

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='

			

				<div class="table-responsive">
				<table  id="example1" class="table table-dark table-sm">
					<thead>
						<tr class="text-center ">
							<th>#</th>
							<th>FECHA</th>
							<th>DETALLE</th>
							<th>PROVEEDOR</th>
							<th>EMPRESA</th>
							<th>MONTO</th>
							<th>DOCUMENTO</th>
							<th>Nº</th>
							<th>PAGAR</th>
							<th>QUITAR</th>
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
							<td>'.$rows['fecha'].'</td>
							<td>'.$rows['detalle'].'</td>
							<td>'.$rows['proveedor'].'</td>
							<td>'.$rows['empresa'].'</td>
							<td>'.number_format($rows['monto'],2,'.',',').'</td>
							<td>'.$rows['codigo'].'</td>
							<td>'.$rows['numeroCom'].'</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/gastoAjax.php" method="POST" data-form="pagar" autocomplete="off" >
									<input type="hidden" name="gastoId_pag" value="'.mainModel::encryption($rows['gastoId']).'">
									<input type="hidden" name="modulo_gasto" value="pagar">';
									if($rows['estado'] == 'cerrado' || $rows['estadoGasto'] == 'pagado'){ 
										$tabla .='
									<button disabled type="submit" class="btn btn-success">
										<i class="far fa-money-bill-1"></i>
									</button>';
									}else{    
										$tabla .='
									<button type="submit" class="btn btn-success">
										<i class="far fa-money-bill-1"></i>
									</button>';
									}
									$tabla.='
								</form>
							</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/gastoAjax.php" method="POST" data-form="quitar" autocomplete="off" >
									<input type="hidden" name="gastoId_qui" value="'.mainModel::encryption($rows['gastoId']).'">
									<input type="hidden" name="programaId_qui" value="'.$programaId.'">
									<input type="hidden" name="monto_qui" value="'.number_format($rows['monto'],2,'.',',').'">
									<input type="hidden" name="modulo_gasto" value="quitar">';
									if($rows['estado'] == 'cerrado' || $rows['estadoGasto'] == 'pagado'){ 
										$tabla .='
									<button disabled type="submit" class="btn btn-warning">
										<i class="far fa-rectangle-xmark"></i>
									</button>';
									}else{
										$tabla .='
									<button type="submit" class="btn btn-warning">
										<i class="far fa-rectangle-xmark"></i>
									</button>';
									}
									$tabla.='
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

			/*if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando gastos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
		} /*-- Fin controlador --*/

		/*---------- Controlador programar gasto ----------*/
		public function pagar_gasto_controlador(){

			/*== Recuperando id de la ingreso ==*/
			$id=mainModel::decryption($_POST['gastoId_pag']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
			$check_gasto=mainModel::ejecutar_consulta_simple("SELECT gastoId FROM gastos WHERE gastoId='$id'");
			if($check_gasto->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El gasto que intenta programar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_gasto->closeCursor();
			$check_gasto=mainModel::desconectar($check_gasto);

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

			//cambio de saldo de programa
		
				$pagar_gasto	=	mainModel::cambio_estado("gastos","estadoGasto","pagado","gastoId",$id);
				if($pagar_gasto->rowCount()==1){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Gasto Programado!",
						"Texto"=>"El gasto ha sido pagado del sistema exitosamente.",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido pagar el gasto del sistema, por favor intente nuevamente.",
						"Tipo"=>"error"
					];
				}

				$pagar_gasto->closeCursor();
				$pagar_gasto=mainModel::desconectar($pagar_gasto);

			echo json_encode($alerta);
		} /*-- Fin controlador --*/

		/*---------- Controlador quitar gasto ----------*/
		public function quitar_gasto_controlador(){

			/*== Recuperando id de la ingreso ==*/
			$id=mainModel::decryption($_POST['gastoId_qui']);
			$id=mainModel::limpiar_cadena($id);
			$monto_gasto = mainModel::limpiar_cadena($_POST['monto_qui']);

			$programaId = mainModel::limpiar_cadena($_POST['programaId_qui']);

			/*== Comprobando proveedor en la DB ==*/
			$check_gasto=mainModel::ejecutar_consulta_simple("SELECT gastoId FROM gastos WHERE gastoId='$id'");
			if($check_gasto->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El gasto que intenta programar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_gasto->closeCursor();
			$check_gasto=mainModel::desconectar($check_gasto);

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

			$programar_gasto	=	mainModel::cambio_programa("gastos","programaId","gastoId",$id);
			//cambio de saldo de programa
			$cambio_saldo	 = mainModel::cambio_saldo("quitar","programas","saldo",$monto_gasto,"programaId",$programaId);
			$cambio_saldo->closeCursor();

			if($programar_gasto->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Gasto Retirado!",
					"Texto"=>"El gasto ha sido retirado del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido retirar $id el gasto del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$programar_gasto->closeCursor();
			$programar_gasto=mainModel::desconectar($programar_gasto);

			echo json_encode($alerta);
		} /*-- Fin controlador --*/

    }

		