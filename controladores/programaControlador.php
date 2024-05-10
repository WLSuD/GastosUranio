<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class programaControlador extends mainModel{

        /*---------- Controlador agregar programa ----------*/
        public function agregar_programa_controlador(){
            
            $codigo        =   mainModel::limpiar_cadena(strtoupper($_POST['codigo_reg']));
            $ingresoId     =   mainModel::limpiar_cadena($_POST['ingresoId_reg']);
            $fecha         =   mainModel::limpiar_cadena($_POST['fecha_reg']);
            $obraId        =   mainModel::limpiar_cadena($_POST['obraId_reg']);
            $saldoP        =   mainModel::limpiar_cadena($_POST['saldo_reg']);
            $programaAnt   =   mainModel::limpiar_cadena($_POST['programaAnt_reg']);
            
            /*------ capturo el monto del ingreso para registrar como saldo */
            $saldo_ingreso  =   mainModel::ejecutar_consulta_simple("SELECT monto FROM ingresos WHERE ingresoID	=	'$ingresoId'");
            if($saldo_ingreso->rowCount()==1){ 
                $campos_saldo = $saldo_ingreso->fetch();
                $saldo = $campos_saldo['monto'];
				$saldo = $saldo + $saldoP;
            }

            /*== comprobar campos vacios ==*/
            if($codigo ==  "" || $ingresoId == "" ){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{1,11}",$codigo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La descripcion del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*
            if(mainModel::verificar_datos("[0-9.]{1,25}",$monto)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El monto del ingreso no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }*/

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
			$check_codigo	=	mainModel::ejecutar_consulta_simple("SELECT codigo FROM programas WHERE codigo	=	'$codigo'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El codigo del programa ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_codigo->closeCursor();
			$check_codigo  =   mainModel::desconectar($check_codigo);

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
			$datos_programa_reg  = [
				"codigo" =>[
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo
                ],
                "ingresoId" =>[
					"campo_marcador"=>":Ingreso",
					"campo_valor"=>$ingresoId
				],
                "obraId" =>[
					"campo_marcador"=>":ObraId",
					"campo_valor"=>$obraId
				],
                "saldo" =>[
					"campo_marcador"=>":Saldo",
					"campo_valor"=>$saldo
				],
                "fecha" =>[
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				]
			];
            
            $agregar_programa  =   mainModel::guardar_datos("programas",$datos_programa_reg);

			if($agregar_programa->rowCount()==1){

				$cambio_estado = mainModel::cambio_estado("ingresos","estado","asignado","ingresoId",$ingresoId);
				if($saldoP>0){
					//cambio de saldo de programa cerrado con saldo
					$cambio_saldo = mainModel::cambio_saldo("programar","programas","saldo",$saldoP,"programaId",$programaAnt);
					$cambio_saldo->closeCursor();
					$cambio_saldo = mainModel::desconectar($cambio_saldo);
				}
				$cambio_estado->closeCursor();
				$cambio_estado = mainModel::desconectar($cambio_estado);
			
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Programa registrada!",
					"Texto"=>"El programa se registró con éxito en el sistema",
					"Tipo"=>"success"
			
			];

			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el programa, por favor intente nuevamente",
					"Tipo"=>"error"
				];
				
			}

			$agregar_programa->closeCursor();
			$agregar_programa=mainModel::desconectar($agregar_programa);

			echo json_encode($alerta);
			
			


        } /*-- Fin controlador --*/


        /*---------- Controlador paginador programa ----------*/
		public function paginador_programa_controlador($pagina,$registros,$url,$busqueda){

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

			$campos_tablas = "programas.programaId,programas.codigo,programas.ingresoId,programas.saldo,programas.fecha,programas.estado,
								ingresos.ingresoId,ingresos.descripcion";

			$consulta="SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
						FROM programas INNER JOIN ingresos ON programas.ingresoId = ingresos.ingresoId
						WHERE programas.obraId = $busqueda
						ORDER BY programas.estado ASC LIMIT $inicio,$registros";

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
						<tr class="text-center ">
							<th>#</th>
							<th>CODIGO</th>
							<th>INGRESO</th>
							<th>SALDO</th>
							<th>FECHA</th>
							<th>ESTADO</th>
							<th>DETALLE</th>
							<th>PROGRAMAR</th>
							<th>CERRAR</th>
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
                            <td>'.$rows['codigo'].'</td>
							<td>'.$rows['descripcion'].'</td>
							<td>'.number_format($rows['saldo'],2,'.',',').'</td>
							<td>'.$rows['fecha'].'</td>
							<td>'.$rows['estado'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'programa-detalle/'.mainModel::encryption($rows['programaId']).'/" >
									<i class="fas fa-info fa-fw" ></i>
								</a>
							</td>';
							
							if($rows['estado'] == 'cerrado'){
								$tabla .='
							
							<td>
								<a disabled  class="btn btn-success" href="" >
									<i class="fas fa-rectangle-list fa-fw" ></i>
								</a>
							</td>
							<td>
								<a disabled  class="btn btn-primary" href="" >
									<i class="fas fa-store-slash fa-fw" ></i>
								</a>
							</td>
							<td>
								<a disabled  class="btn btn-success" href="" >
									<i class="fas fa-sync fa-fw" ></i>
								</a>
							</td>
                            <td>
								<button disabled type="submit" class="btn btn-danger">
									<i class="far fa-trash-alt"></i>
								</button>
                            </td>';
							 }else{ 
								$tabla .=' 
								<td>
									<a class="btn btn-secondary" href="'.SERVERURL.'gasto-programa/'.mainModel::encryption($rows['programaId']).'/" >
										<i class="fas fa-rectangle-list fa-fw" ></i>
									</a>
								</td>
								<td>
									<form class="FormularioAjax" action="'.SERVERURL.'ajax/programaAjax.php" method="POST" data-form="cerrar" autocomplete="off" >
										<input type="hidden" name="programaId_cer" value="'.mainModel::encryption($rows['programaId']).'">
										<input type="hidden" name="modulo_programa" value="cerrar">
										<button type="submit" class="btn btn-warning">
											<i class="fas fa-store-slash fa-fw"></i>
										</button>
									</form>
								</td>
								<td>
									<a class="btn btn-success" href="'.SERVERURL.'programa-update/'.mainModel::encryption($rows['programaId']).'/" >
										<i class="fas fa-sync fa-fw"></i>
									</a>
								</td>
								<td>
									<form class="FormularioAjax" action="'.SERVERURL.'ajax/programaAjax.php" method="POST" data-form="delete" autocomplete="off" >
										<input type="hidden" name="programaId_del" value="'.mainModel::encryption($rows['programaId']).'">
										<input type="hidden" name="modulo_programa" value="eliminar">
										<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
										</button>
									</form>
								</td>';
							 }
						$tabla .='
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
			/*
			if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando programas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar programa ----------*/
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


        /*---------- Controlador eliminar programa ----------*/
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


		/*---------- Controlador CERRAR PROGRAMA ----------*/
		public function cerrar_programa_controlador(){

			/*== Recuperando id del programa ==*/
			$id=mainModel::decryption($_POST['programaId_cer']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
			$check_programa=mainModel::ejecutar_consulta_simple("SELECT programaId FROM programas WHERE programaId='$id'");
			if($check_programa->rowCount()<=0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El programa que intenta cerrar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_programa->closeCursor();
			$check_programa=mainModel::desconectar($check_programa);

			/*== Comprobando si programa tiene gasto sin pagar ==*/
			$check_gasto = mainModel::ejecutar_consulta_simple("SELECT gastoId FROM gastos WHERE programaId = $id AND estadoGasto IS NULL ");
			if($check_gasto -> rowCount() >= 1){

				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El programa tiene gastos sin pagar.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_gasto ->closeCursor();
			$check_gasto = mainModel::desconectar($check_gasto);

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

			//cambio de estado a programa
		
				$cerrar_programa	=	mainModel::cambio_estado("programas","estado","cerrado","programaId",$id);
				if($cerrar_programa->rowCount()==1){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"programa Cerrado!",
						"Texto"=>"El programa ha sido cerrado del sistema exitosamente.",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido cerrar el programa del sistema, por favor intente nuevamente.",
						"Tipo"=>"error"
					];
				}

				$cerrar_programa->closeCursor();
				$cerrar_programa=mainModel::desconectar($cerrar_programa);

			echo json_encode($alerta);
		} /*-- Fin controlador --*/
    }