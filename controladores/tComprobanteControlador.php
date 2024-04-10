<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class tcomprobanteControlador extends mainModel{

        /*---------- Controlador agregar tComprobante ----------*/
        public function agregar_tComprobante_controlador(){
            
            $tComprobante   =   mainModel::limpiar_cadena(strtoupper($_POST['tComprobante_reg']));
            $codigo         =   mainModel::limpiar_cadena($_POST['codigo_reg']);
            $idSunat        =   mainModel::limpiar_cadena($_POST['idSunat_reg']);

            /*== comprobar campos vacios ==*/
            if($tComprobante ==  "" || $codigo == ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,50}",$tComprobante)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del tipo de comprobante no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            
            /*== Comprobando nombre del tipo de comprobante ==*/
			$check_tComprobante=mainModel::ejecutar_consulta_simple("SELECT tipoCom FROM tipo_comprobantes WHERE tipoCom='$tComprobante'");
			if($check_tComprobante->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del tipo de comprobante ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_tComprobante ->  closeCursor();
			$check_tComprobante  =  mainModel::desconectar($check_tComprobante);

            /*== Comprobando nombre del codigo ==*/
			$check_codigo=mainModel::ejecutar_consulta_simple("SELECT codigo FROM tipo_comprobantes WHERE codigo='$codigo'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del codigo ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_codigo ->  closeCursor();
			$check_codigo  =  mainModel::desconectar($check_codigo);
            
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
			$datos_tComprobante_reg  = [
				"tipoCom" =>[
					"campo_marcador"=>":tComprobante",
					"campo_valor"=>$tComprobante
                ],
                "codigo" =>[
					"campo_marcador"=>":codigo",
					"campo_valor"=>$codigo
                ],
                "idSunat" =>[
					"campo_marcador"=>":idSunat",
					"campo_valor"=>$idSunat
				]
			];
            
            $agregar_tComprobante   =   mainModel::guardar_datos("tipo_comprobantes",$datos_tComprobante_reg);

			if($agregar_tComprobante->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Tipo Comprobante registrada!",
					"Texto"=>"El tipo de comprobnate se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el tipo de comprobantes, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_tComprobante->closeCursor();
			$agregar_tComprobante=mainModel::desconectar($agregar_tComprobante);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador tComprobante ----------*/
		public function paginador_tComprobante_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM tipo_comprobantes WHERE tipoCom LIKE '%$busqueda%'  ORDER BY tipoCom ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM tipo_comprobantes ORDER BY tipoCom ASC LIMIT $inicio,$registros";
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
							<th>TIPO DE COMPROBANTE</th>
                            <th>CODIGO</th>
                            <th>CODIGO SUNAT</th>
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
                            <td>'.$rows['tipoCom'].'</td>
                            <td>'.$rows['codigo'].'</td>
                            <td>'.$rows['idSunat'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'tComprobante-update/'.mainModel::encryption($rows['tipoComId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/tComprobanteAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="tipoComId_del" value="'.mainModel::encryption($rows['tipoComId']).'">
									<input type="hidden" name="modulo_tComprobante" value="eliminar">
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
				$tabla.='<p class="text-right">Mostrando tipo comprobante <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar tComprobante ----------*/
		public function actualizar_tComprobante_controlador(){

            /*== Recuperando id de la asignacion ==*/
			$id=mainModel::decryption($_POST['tipoComId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando asignacion en la DB ==*/
            $check_tcomprobante=mainModel::ejecutar_consulta_simple("SELECT * FROM tipo_comprobantes WHERE tipoComId='$id'");
            if($check_tcomprobante->rowCount()<=0){
            	$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "No hemos encontrado el tipo de comprobante en el sistema.",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_tcomprobante->fetch();
			}
			$check_tcomprobante->closeCursor();
			$check_tcomprobante=mainModel::desconectar($check_tcomprobante );
            
            $tipoCom    =   mainModel::limpiar_cadena(strtoupper($_POST['tipoCom_up']));
            $codigo     =   mainModel::limpiar_cadena($_POST['codigo_up']);
            $idSunat    =   mainModel::limpiar_cadena($_POST['idSunat_up']);

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,50}",$tipoCom)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre del tipo de documento no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de asignacion ==*/
            if($tipoCom  !=  $campos['tipoCom']){
                $check_tipoCom=mainModel::ejecutar_consulta_simple("SELECT tipoCom FROM tipo_comprobantes WHERE tipoCom='$tipoCom'");
                if($check_tipoCom->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre del tipo de documento ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_tipoCom->closeCursor();
				$check_tipoCom=mainModel::desconectar($check_tipoCom);
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
			$datos_tComprobante_up  = [
				"tipoCom" =>[
					"campo_marcador"=>":tComprobante",
					"campo_valor"=>$tipoCom
                ],
                "codigo" =>[
					"campo_marcador"=>":codigo",
					"campo_valor"=>$codigo
                ],
                "idSunat" =>[
					"campo_marcador"=>":idSunat",
					"campo_valor"=>$idSunat
				]
			];
 
			$condicion=[
				"condicion_campo"=>"tipoComId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("tipo_comprobantes",$datos_tComprobante_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Tipo de Documento actualizada!",
					"Texto"=>"La tipo de documento se actualizo con éxito en el sistema",
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


        /*---------- Controlador eliminar tComprobante ----------*/
		public function eliminar_tComprobante_controlador(){

            /*== Recuperando id de la tComprobante ==*/
			$id=mainModel::decryption($_POST['tipoComId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando tComprobante en la DB ==*/
            $check_tComprobante=mainModel::ejecutar_consulta_simple("SELECT tipoComId FROM tipo_comprobantes WHERE tipoComId='$id'");
            if($check_tComprobante->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"el tipo de comprobante que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_tComprobante->closeCursor();
			$check_tComprobante=mainModel::desconectar($check_tComprobante);

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

			$eliminar_tComprobante  = mainModel::eliminar_registro("tipo_comprobantes","tipocomId",$id);

			if($eliminar_tComprobante->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Tipo de comprobante eliminada!",
					"Texto"=>"El tipo de comprobante ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar el tipo de comprobante del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_tComprobante->closeCursor();
			$eliminar_tComprobante=mainModel::desconectar($eliminar_tComprobante);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }