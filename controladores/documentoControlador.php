<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class documentoControlador extends mainModel{

        /*---------- Controlador agregar documento ----------*/
        public function agregar_documento_controlador(){
            
            $documento    =   mainModel::limpiar_cadena(strtoupper($_POST['documento_reg']));

            /*== comprobar campos vacios ==*/
            if($documento ==  ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,50}",$documento)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del documento no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de documento ==*/
			$check_documento   =   mainModel::ejecutar_consulta_simple("SELECT documento FROM documentos WHERE documento='$documento'");
			if($check_documento->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del documento ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_documento->closeCursor();
			$check_documento  =   mainModel::desconectar($check_documento);
            
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
			$datos_documento_reg  = [
				"documento" =>[
					"campo_marcador"=>":Documento",
					"campo_valor"=>$documento
				]
			];
            
            $agregar_documento=mainModel::guardar_datos("documentos",$datos_documento_reg);

			if($agregar_documento->rowCount()==1){
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

			$agregar_documento->closeCursor();
			$agregar_documento=mainModel::desconectar($agregar_documento);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador documento ----------*/
		public function paginador_documento_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM documentos WHERE documento LIKE '%$busqueda%'  ORDER BY documento ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM documentos ORDER BY documento ASC LIMIT $inicio,$registros";
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
							<th>DOCUMENTO</th>
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
                            <td>'.$rows['documento'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'documento-update/'.mainModel::encryption($rows['documentoId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/documentoAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="documentoId_del" value="'.mainModel::encryption($rows['documentoId']).'">
									<input type="hidden" name="modulo_documento" value="eliminar">
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
				$tabla.='<p class="text-right">Mostrando documentos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar documento ----------*/
		public function actualizar_documento_controlador(){

            /*== Recuperando id de la documento ==*/
			$id=mainModel::decryption($_POST['documentoId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando documento en la DB ==*/
            $check_documento    =   mainModel::ejecutar_consulta_simple("SELECT * FROM documentos WHERE documentoId='$id'");
            if($check_documento->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la asignacion en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_documento->fetch();
			}
			$check_documento->closeCursor();
			$check_documento=mainModel::desconectar($check_documento );
            
            $documento  =   mainModel::limpiar_cadena(strtoupper($_POST['documento_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,50}",$documento)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del documento no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de documento ==*/
            if($documento  !=  $campos['documento']){
                $check_documento=mainModel::ejecutar_consulta_simple("SELECT documento FROM documentos WHERE documento='$documento'");
                if($check_documento->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre del documento ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_documento->closeCursor();
				$check_asignacion=mainModel::desconectar($check_documento);
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
			$datos_documento_up=[
				"documento"=>[
					"campo_marcador"=>":Documento",
					"campo_valor"=>$documento
				]
			];
 
			$condicion=[
				"condicion_campo"=>"documentoId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("documentoS",$datos_documento_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Documento actualizada!",
					"Texto"=>"La documento se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la documento, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar documento ----------*/
		public function eliminar_documento_controlador(){

            /*== Recuperando id de la documento ==*/
			$id=mainModel::decryption($_POST['documentoId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando asignacion en la DB ==*/
            $check_documento=mainModel::ejecutar_consulta_simple("SELECT documentoId FROM documentos WHERE documentoId='$id'");
            if($check_documento->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El docuemento que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_documento->closeCursor();
			$check_documento=mainModel::desconectar($check_documento);

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

			$eliminar_documentoId=mainModel::eliminar_registro("documentos","documentoId",$id);

			if($eliminar_documentoId->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"DocumentoId eliminada!",
					"Texto"=>"El documento ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la documento del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_documentoId->closeCursor();
			$eliminar_documentoId=mainModel::desconectar($eliminar_documentoId);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }