<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class empresaControlador extends mainModel{

        /*---------- Controlador agregar empresa ----------*/
        public function agregar_empresa_controlador(){
            
            $empresa    =   mainModel::limpiar_cadena(strtoupper($_POST['empresa_reg']));

            /*== comprobar campos vacios ==*/
            if($empresa ==  ""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$empresa)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de empresa no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de empresa ==*/
			$check_empresa=mainModel::ejecutar_consulta_simple("SELECT empresa FROM empresas WHERE empresa='$empresa'");
			if($check_empresa->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la empresa ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_empresa->closeCursor();
			$check_empresa  =   mainModel::desconectar($check_empresa);
            
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
			$datos_empresa_reg  = [
				"empresa" =>[
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$empresa
				]
			];
            
            $agregar_empresa=mainModel::guardar_datos("empresas",$datos_empresa_reg);

			if($agregar_empresa->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Empresa registrada!",
					"Texto"=>"La empresa se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la empresa, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_empresa->closeCursor();
			$agregar_empresa=mainModel::desconectar($agregar_empresa);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador empresa ----------*/
		public function paginador_empresa_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM empresas WHERE empresa LIKE '%$busqueda%'  ORDER BY empresa ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM empresas ORDER BY empresa ASC LIMIT $inicio,$registros";
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
                            <td>'.$rows['empresa'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'empresa-update/'.mainModel::encryption($rows['empresaId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/empresaAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="empresa_id_del" value="'.mainModel::encryption($rows['empresaId']).'">
									<input type="hidden" name="modulo_empresa" value="eliminar">
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
        

        /*---------- Controlador actualizar empresa ----------*/
		public function actualizar_empresa_controlador(){

            /*== Recuperando id de la empresa ==*/
			$id=mainModel::decryption($_POST['empresa_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_empresa=mainModel::ejecutar_consulta_simple("SELECT * FROM empresas WHERE empresaId='$id'");
            if($check_empresa->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la empresa en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_empresa->fetch();
			}
			$check_empresa->closeCursor();
			$check_empresa=mainModel::desconectar($check_empresa);
            
            $nombre=mainModel::limpiar_cadena(strtoupper($_POST['empresa_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de empresa no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de categoria ==*/
            if($nombre!=$campos['empresa']){
                $check_nombre=mainModel::ejecutar_consulta_simple("SELECT empresa FROM empresas WHERE empresa='$nombre'");
                if($check_nombre->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de empresa ingresado ya se encuentra registrado en el sistema",
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
			$datos_empresa_up=[
				"empresa"=>[
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				]
			];
 
			$condicion=[
				"condicion_campo"=>"empresaId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("empresas",$datos_empresa_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Empresa actualizada!",
					"Texto"=>"La empresa se actualizo con éxito en el sistema",
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


        /*---------- Controlador eliminar categoria ----------*/
		public function eliminar_empresa_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['empresa_id_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando empresa en la DB ==*/
            $check_empresa=mainModel::ejecutar_consulta_simple("SELECT empresaId FROM empresas WHERE empresaId='$id'");
            if($check_empresa->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La empresa que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_empresa->closeCursor();
			$check_empresa=mainModel::desconectar($check_empresa);

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

			$eliminar_empresa=mainModel::eliminar_registro("empresas","empresaId",$id);

			if($eliminar_empresa->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Empresa eliminada!",
					"Texto"=>"La empresa ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la empresa del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_empresa->closeCursor();
			$eliminar_empresa=mainModel::desconectar($eliminar_empresa);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }