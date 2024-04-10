<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class subcategoriaControlador extends mainModel{

        /*---------- Controlador agregar categoria ----------*/
        public function agregar_subcategoria_controlador(){
            
            $subcategoria               =   mainModel::limpiar_cadena(strtoupper($_POST['subcategoria_nombre_reg']));
            $subcategoria_categoria     =   mainModel::limpiar_cadena($_POST['subcategoria_categoria_reg']);

            /*== comprobar campos vacios ==*/
            if($subcategoria_categoria=="" || $subcategoria_categoria==""){
                $alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "No has llenado todos los campos que son obligatorios",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,100}",$subcategoria)){
				$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "El nombre de la subcategoría no coincide con el formato solicitado",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
            }

            if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{1,10}",$subcategoria_categoria)){
				$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "El nombre de categoría no coincide con el formato solicitado",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de la subcategoria ==*/
			$check_nombre=mainModel::ejecutar_consulta_simple("SELECT subcategoria FROM sub_categorias WHERE subcategoria='$subcategoria'");
			if($check_nombre->rowCount()>0){
				$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "El nombre de la subcategoría ingresado ya se encuentra registrado en el sistema",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_nombre->closeCursor();
			$check_nombre=mainModel::desconectar($check_nombre);
            
            /*== Comprobando privilegios ==*/
			if($_SESSION['cargo_svi']!="Administrador"){
				$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "No tienes los permisos necesarios para realizar esta operación en el sistema.",
					"Tipo"      =>  "error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Preparando datos para enviarlos al modelo ==*/
			$datos_subcategoria_reg=[
				"subcategoria"=>[
					"campo_marcador"    =>  ":Nombre",
					"campo_valor"       =>  $subcategoria
                ],
                "categoriaId"=>[
					"campo_marcador"    =>  ":Categoria",
					"campo_valor"       =>  $subcategoria_categoria
				]
			];
            
            $agregar_subcategoria   =   mainModel::guardar_datos("sub_categorias",$datos_subcategoria_reg);

			if($agregar_subcategoria->rowCount()==1){
				$alerta=[
					"Alerta"    =>  "limpiar",
					"Titulo"    =>  "¡Categoría registrada!",
					"Texto"     =>  "La categoría se registró con éxito en el sistema",
					"Tipo"      =>  "success"
				];
			}else{
				$alerta=[
					"Alerta"    =>  "simple",
					"Titulo"    =>  "Ocurrió un error inesperado",
					"Texto"     =>  "No hemos podido registrar la categoría, por favor intente nuevamente",
					"Tipo"      =>  "error"
				];
			}

			$agregar_subcategoria->closeCursor();
			$agregar_subcategoria   =   mainModel::desconectar($agregar_subcategoria);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador categoria ----------*/
		public function paginador_subcategoria_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			/*--if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM sub_categorias WHERE subcategoria LIKE '%$busqueda%'  ORDER BY subcategoria ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM sub_categorias ORDER BY subcategoria ASC LIMIT $inicio,$registros";
			}--*/

			$campos_tablas="sub_categorias.subcatId,sub_categorias.subcategoria,sub_categorias.categoriaId,categorias.categoriaId,categorias.categoria";

			$consulta="SELECT SQL_CALC_FOUND_ROWS $campos_tablas 
                            FROM sub_categorias INNER JOIN categorias ON sub_categorias.categoriaId=categorias.categoriaId
                            ORDER BY sub_categorias.subcatId DESC LIMIT $inicio,$registros";

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
                            <th>CATEGORIA</th>
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
                            <td>'.$rows['subcategoria'].'</td>
							<td>'.$rows['categoria'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'subcategoria-update/'.mainModel::encryption($rows['subcatId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/subcategoriaAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="subcategoria_id_del" value="'.mainModel::encryption($rows['subcatId']).'">
									<input type="hidden" name="modulo_subcategoria" value="eliminar">
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
				$tabla.='<p class="text-right">Mostrando subcategorías <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar categoria ----------*/
		public function actualizar_subcategoria_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['subcategoria_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_subcategoria=mainModel::ejecutar_consulta_simple("SELECT * FROM sub_categorias WHERE subcatId='$id'");
            if($check_subcategoria->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la categoría en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_subcategoria->fetch();
			}

			$check_subcategoria->closeCursor();
			$check_subcategoria	=	mainModel::desconectar($check_subcategoria);
            
            $subcategoria	=	mainModel::limpiar_cadena(strtoupper($_POST['subcategoria_up']));
			$categoriaId	=	mainModel::limpiar_cadena($_POST['subcategoria_categoria_up']);
			

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,100}",$subcategoria)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de la subcategoría no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{1,10}",$categoriaId)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La categoría no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de categoria ==*/
            if($subcategoria!=$campos['subcategoria']){
                $check_nombre=mainModel::ejecutar_consulta_simple("SELECT subcategoria FROM sub_categorias WHERE subcategoria='$subcategoria'");
                if($check_nombre->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de la subcategoría ingresado ya se encuentra registrado en el sistema",
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
			$datos_subcategoria_up=[
				"subcategoria"=>[
					"campo_marcador"=>":subcatregoria",
					"campo_valor"=>$subcategoria
				],
				"categoriaId"=>[
					"campo_marcador"=>":categoriaId",
					"campo_valor"=>$categoriaId
				]
			];

			$condicion=[
				"condicion_campo"=>"subcatId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("sub_categorias",$datos_subcategoria_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Subategoría actualizada!",
					"Texto"=>"La subcategoría se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la subcategoría, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar categoria ----------*/
		public function eliminar_subcategoria_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['subcategoria_id_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_subcategoria=mainModel::ejecutar_consulta_simple("SELECT subcatId FROM sub_categorias WHERE subcatId='$id'");
            if($check_subcategoria->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La subcategoría que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_subcategoria->closeCursor();
			$check_subcategoria=mainModel::desconectar($check_subcategoria);

            /*== Comprobando productos en categoria ==
			$check_productos=mainModel::ejecutar_consulta_simple("SELECT categoriaId FROM sub_categorias WHERE categoriaId='$id' LIMIT 1");
			if($check_productos->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No podemos eliminar la categoría debido a que tiene productos asociados, le recomendamos deshabilitar esta categoría si ya no será usada en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_productos->closeCursor();
			$check_productos=mainModel::desconectar($check_productos);*/
            
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

			$eliminar_subcategoria=mainModel::eliminar_registro("sub_categorias","subcatId",$id);

			if($eliminar_subcategoria->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Subategoría eliminada!",
					"Texto"=>"La subcategoría ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la subcategoría del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_subcategoria->closeCursor();
			$eliminar_subcategoria=mainModel::desconectar($eliminar_subcategoria);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }