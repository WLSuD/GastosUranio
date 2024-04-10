<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class proveedorControlador extends mainModel{

        /*---------- Controlador agregar proveedor ----------*/
        public function agregar_proveedor_controlador(){
            
            $proveedor      =   mainModel::limpiar_cadena(strtoupper($_POST['proveedor_reg']));
            $documentoId    =   mainModel::limpiar_cadena($_POST['documentoId_reg']);
            $numeroDoc      =   mainModel::limpiar_cadena($_POST['numeroDoc_reg']);
            $direccion      =   mainModel::limpiar_cadena(strtoupper($_POST['direccion_reg']));


            /*== comprobar campos vacios ==*/
            if($proveedor ==  "" || $documentoId == "" || $numeroDoc == "" || $direccion == ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ .-]{10,100}",$proveedor)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del proveedor no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[0-99 ]{8,11}",$numeroDoc)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El numero del proveedor no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ .-]{10,150}",$direccion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La direccion no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre del proveedor ==*/
			$check_proveedor=mainModel::ejecutar_consulta_simple("SELECT proveedor FROM proveedores WHERE proveedor='$proveedor'");
			if($check_proveedor->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del proveedor ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_proveedor->closeCursor();
			$check_proveedor  =   mainModel::desconectar($check_proveedor);

            /*== Comprobando el numero del documento del proveedor ==*/
            $check_numeroDoc=mainModel::ejecutar_consulta_simple("SELECT numeroDoc FROM proveedores WHERE numeroDoc='$numeroDoc'");
			if($check_numeroDoc->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El numero del documento del proveedor ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_numeroDoc->closeCursor();
			$check_numeroDoc  =   mainModel::desconectar($check_numeroDoc);
            
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
			$datos_proveedor_reg  = [
				"proveedor" =>[
					"campo_marcador"=>":Proveedor",
					"campo_valor"=>$proveedor
                ],
                "documentoId" =>[
					"campo_marcador"=>":DocumentoId",
					"campo_valor"=>$documentoId
				],
                "numeroDoc" =>[
					"campo_marcador"=>":NumeroDoc",
					"campo_valor"=>$numeroDoc
				],
                "direccion" =>[
					"campo_marcador"=>":direccion",
					"campo_valor"=>$direccion
				]
			];
            
            $agregar_proveedor  =   mainModel::guardar_datos("proveedores",$datos_proveedor_reg);

			if($agregar_proveedor->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Proveedor registrada!",
					"Texto"=>"El proveedor se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la proveedor, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_proveedor->closeCursor();
			$agregar_proveedor=mainModel::desconectar($agregar_proveedor);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador proveedor ----------*/
		public function paginador_proveedor_controlador($pagina,$registros,$url,$busqueda){

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

			$campos_tablas = "proveedores.proveedorId,proveedores.proveedor,proveedores.documentoId,proveedores.numeroDoc,proveedores.direccion,
							documentos.documentoId,documentos.documento";

			$consulta="SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
			FROM proveedores INNER JOIN documentos ON proveedores.documentoId = documentos.documentoId
			ORDER BY proveedores.documentoId DESC LIMIT $inicio,$registros";

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
							<th>PROVEEDOR</th>
							<th>DOCUMENTO</th>
							<th>NUMERO DE DOC</th>
							<th>DIRECCION</th>
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
                            <td>'.$rows['proveedor'].'</td>
							<td>'.$rows['documento'].'</td>
							<td>'.$rows['numeroDoc'].'</td>
							<td>'.$rows['direccion'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'proveedor-update/'.mainModel::encryption($rows['proveedorId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/proveedorAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="proveedorId_del" value="'.mainModel::encryption($rows['proveedorId']).'">
									<input type="hidden" name="modulo_proveedor" value="eliminar">
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
				$tabla.='<p class="text-right">Mostrando proveedores <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar proveedor ----------*/
		public function actualizar_proveedor_controlador(){

            /*== Recuperando id de la proveedor ==*/
			$id=mainModel::decryption($_POST['proveedorId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
            $check_proveedor	=	mainModel::ejecutar_consulta_simple("SELECT * FROM proveedores WHERE proveedorId='$id'");
            if($check_proveedor->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado proveedor en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_proveedor->fetch();
			}
			$check_proveedor->closeCursor();
			$check_proveedor=mainModel::desconectar($check_proveedor);
            
            $proveedor		=	mainModel::limpiar_cadena(strtoupper($_POST['proveedor_up']));
			$documentoId	=	mainModel::limpiar_cadena(strtoupper($_POST['documentoId_up']));
			$numeroDoc		=	mainModel::limpiar_cadena(strtoupper($_POST['numeroDoc_up']));
			$direccion		=	mainModel::limpiar_cadena(strtoupper($_POST['direccion_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ .-]{10,100}",$proveedor)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del proveedor no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[0-99 ]{8,11}",$numeroDoc)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El numero del proveedor no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ .-]{10,150}",$direccion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La direccion no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre del proveedor ==*/
			if($proveedor != $campos['proveedor']){
				$check_proveedor=mainModel::ejecutar_consulta_simple("SELECT proveedor FROM proveedores WHERE proveedor='$proveedor'");
				if($check_proveedor->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El nombre del proveedor ingresado ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
				$check_proveedor->closeCursor();
				$check_proveedor  =   mainModel::desconectar($check_proveedor);
			}

            /*== Comprobando el numero del documento del proveedor ==*/
			if($numeroDoc != $campos['numeroDoc']){
				$check_numeroDoc=mainModel::ejecutar_consulta_simple("SELECT numeroDoc FROM proveedores WHERE numeroDoc='$numeroDoc'");
				if($check_numeroDoc->rowCount()>0){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El numero del documento del proveedor ingresado ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
				$check_numeroDoc->closeCursor();
				$check_numeroDoc  =   mainModel::desconectar($check_numeroDoc);
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
			$datos_proveedor_up  = [
				"proveedor" =>[
					"campo_marcador"=>":Proveedor",
					"campo_valor"=>$proveedor
                ],
                "documentoId" =>[
					"campo_marcador"=>":DocumentoId",
					"campo_valor"=>$documentoId
				],
                "numeroDoc" =>[
					"campo_marcador"=>":NumeroDoc",
					"campo_valor"=>$numeroDoc
				],
                "direccion" =>[
					"campo_marcador"=>":direccion",
					"campo_valor"=>$direccion
				]
			];
 
			$condicion=[
				"condicion_campo"=>"proveedorId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("proveedores",$datos_proveedor_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡proveedor actualizado!",
					"Texto"=>"El proveedor se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la empresa, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar proveedor ----------*/
		public function eliminar_proveedor_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['proveedorId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando proveedor en la DB ==*/
            $check_proveedor=mainModel::ejecutar_consulta_simple("SELECT proveedorId FROM proveedores WHERE proveedorId='$id'");
            if($check_proveedor->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El proveedor que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_proveedor->closeCursor();
			$check_proveedor=mainModel::desconectar($check_proveedor);

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

			$eliminar_proveedor	=	mainModel::eliminar_registro("proveedores","proveedorId",$id);

			if($eliminar_proveedor->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Empresa eliminada!",
					"Texto"=>"El proveedor ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el proveedor del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_proveedor->closeCursor();
			$eliminar_proveedor=mainModel::desconectar($eliminar_proveedor);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }