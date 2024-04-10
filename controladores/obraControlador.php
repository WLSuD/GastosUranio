<?php

    if($peticion_ajax){
        require_once "../modelos/mainModel.php";
    }else{
        require_once "./modelos/mainModel.php";
    }

    class obracontrolador extends mainModel{

        /*---------- Controlador agregar obra ----------*/
        public function agregar_obra_controlador(){

            $obra           =   mainModel::limpiar_cadena(strtoupper($_POST['obra_reg']));
            $abreviatura    =   mainModel::limpiar_cadena(strtoupper($_POST['abreviatura_reg']));

            /*=== comprobar campos vacios === */
            if($obra == "" || $abreviatura == ""){
                $alert = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un  problema",
                    "Texto" => "No haz llenado los campos obligatorios",
                    "Tipo" => "error"
                ];
                echo json_encode($alert);
                exit();
            }

            /*=== verificando integridad de los datos === */
            if(mainModel::verificar_datos("[a-aA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$obra)){
                $alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de empresa no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-aA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,25}",$abreviatura)){
                $alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La abreviatura no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de obra ==*/
            $check_obra =   mainModel::ejecutar_consulta_simple("SELECT obra FROM obras WHERE obra='$obra'");
			if($check_obra->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la obra ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_obra->closeCursor();
			$check_obra  =   mainModel::desconectar($check_obra);

            /*== Comprobando abreviatura de obra ==*/
            $check_abreviatura =   mainModel::ejecutar_consulta_simple("SELECT abreviatura FROM obras WHERE abreviatura='$abreviatura'");
			if($check_abreviatura->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La abreviatura de la obra ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_abreviatura->closeCursor();
			$check_abreviatura  =   mainModel::desconectar($check_abreviatura);
            
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
			$datos_obra_reg  = [
				"obra" =>[
					"campo_marcador"=>":Obra",
					"campo_valor"=>$obra
                ],
                "abreviatura" =>[
					"campo_marcador"=>":abreviatura",
					"campo_valor"=>$abreviatura
                ]
			];
            
            $agregar_obra   =   mainModel::guardar_datos("obras",$datos_obra_reg);

			if($agregar_obra->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Obra registrada!",
					"Texto"=>"La obra se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la obra, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_obra->closeCursor();
			$agregar_obra=mainModel::desconectar($agregar_obra);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador obra ----------*/
		public function paginador_obra_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM obras WHERE obra LIKE '%$busqueda%'  ORDER BY obra ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM obras ORDER BY obra ASC LIMIT $inicio,$registros";
			}

			$conexion   = mainModel::conectar();

			$datos      = $conexion->query($consulta);

			$datos      = $datos->fetchAll();

			$total      = $conexion->query("SELECT FOUND_ROWS()");
			$total      = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
				<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>#</th>
							<th>OBRA</th>
                            <th>ABREVIATURA</th>
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
                            <td>'.$rows['obra'].'</td>
                            <td>'.$rows['abreviatura'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'obra-update/'.mainModel::encryption($rows['obraId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/obraAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="obraId_del" value="'.mainModel::encryption($rows['obraId']).'">
									<input type="hidden" name="modulo_obra" value="eliminar">
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
        

        /*---------- Controlador actualizar obra ----------*/
		public function actualizar_obra_controlador(){

            /*== Recuperando id de la obra ==*/
			$id=mainModel::decryption($_POST['obraId_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando obra en la DB ==*/
            $check_obra =   mainModel::ejecutar_consulta_simple("SELECT * FROM obras WHERE obraId='$id'");
            if($check_obra->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la obra en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_obra->fetch();
			}
			$check_obra->closeCursor();
			$check_obra=mainModel::desconectar($check_obra);
            
            $obra           =   mainModel::limpiar_cadena(strtoupper($_POST['obra_up']));
            $abreviatura    =   mainModel::limpiar_cadena(strtoupper($_POST['abreviatura_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$obra)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de obra no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,25}",$abreviatura)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La abreviatura de la obra no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de obra ==*/
            if($obra!=$campos['obra']){
                $check_obra=mainModel::ejecutar_consulta_simple("SELECT obra FROM obras WHERE obra='$obra'");
                if($check_obra->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de la obra ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_obra->closeCursor();
				$check_obra=mainModel::desconectar($check_obra);
            }

            /*== Comprobando abreviatura de obra ==*/
            if($abreviatura !=  $campos['abreviatura']){
                $check_abreviatura=mainModel::ejecutar_consulta_simple("SELECT abreviatura FROM obras WHERE abreviatura='$abreviatura'");
                if($check_abreviatura->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Laabreviatura de la obra ingresado ya se encuentra registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
				}
				$check_abreviatura->closeCursor();
				$check_abreviatura=mainModel::desconectar($check_abreviatura);
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
			$datos_obra_up=[
				"obra"=>[
					"campo_marcador"=>":Obra",
					"campo_valor"=>$obra
                ],
                "abreviatura"=>[
					"campo_marcador"=>":Abreviatura",
					"campo_valor"=>$abreviatura
                ]
			];
 
			$condicion=[
				"condicion_campo"=>"obraId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("obras",$datos_obra_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"!obra actualizada!",
					"Texto"=>"La obra se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la obra, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar obra ----------*/
		public function eliminar_obra_controlador(){

            /*== Recuperando id de la obra ==*/
			$id=mainModel::decryption($_POST['obraId_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando obra en la DB ==*/
            $check_obra=mainModel::ejecutar_consulta_simple("SELECT obraId FROM obras WHERE obraId='$id'");
            if($check_obra->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La obra que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_obra->closeCursor();
			$check_obra=mainModel::desconectar($check_obra);

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

			$eliminar_obra  =   mainModel::eliminar_registro("obras","obraId",$id);

			if($eliminar_obra->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Obra eliminada!",
					"Texto"=>"La obra ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la obra del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_obra->closeCursor();
			$eliminar_obra  =   mainModel::desconectar($eliminar_obra);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }