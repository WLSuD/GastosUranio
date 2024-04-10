<?php

	if($peticion_ajax){
		require_once "../modelos/mainModel.php";
	}else{
		require_once "./modelos/mainModel.php";
	}

	class categoriaControlador extends mainModel{

        /*---------- Controlador agregar categoria ----------*/
        public function agregar_categoria_categoria(){
            
            $categoria=mainModel::limpiar_cadena(strtoupper($_POST['categoria_nombre_reg']));

            /*== comprobar campos vacios ==*/
            if($categoria==""){
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
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,40}",$categoria)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de categoría no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            
            /*== Comprobando nombre de categoria ==*/
			$check_nombre=mainModel::ejecutar_consulta_simple("SELECT categoria FROM categorias WHERE categoria='$categoria'");
			if($check_nombre->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de categoría ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_nombre->closeCursor();
			$check_nombre=mainModel::desconectar($check_nombre);
            
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
			$datos_categoria_reg=[
				"categoria"=>[
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$categoria
				]
			];
            
            $agregar_categoria=mainModel::guardar_datos("categorias",$datos_categoria_reg);

			if($agregar_categoria->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Categoría registrada!",
					"Texto"=>"La categoría se registró con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar la categoría, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			$agregar_categoria->closeCursor();
			$agregar_categoria=mainModel::desconectar($agregar_categoria);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador paginador categoria ----------*/
		public function paginador_categoria_controlador($pagina,$registros,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM categorias WHERE categoria LIKE '%$busqueda%'  ORDER BY categoria ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM categorias ORDER BY categoria ASC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
				<div  class="table-responsive">
				<table id="example1" class="table table-dark table-sm" style="width:100%">
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
                            <td>'.$rows['categoria'].'</td>
							<td>
								<a class="btn btn-success" href="'.SERVERURL.'category-update/'.mainModel::encryption($rows['categoriaId']).'/" >
									<i class="fas fa-sync fa-fw"></i>
								</a>
							</td>
                            <td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/categoriaAjax.php" method="POST" data-form="delete" autocomplete="off" >
									<input type="hidden" name="categoria_id_del" value="'.mainModel::encryption($rows['categoriaId']).'">
									<input type="hidden" name="modulo_categoria" value="eliminar">
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

			/*-- 
			if($total>0 && $pagina<=$Npaginas){
				$tabla.='<p class="text-right">Mostrando categorías <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
			}

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
			}*/

			return $tabla;
        } /*-- Fin controlador --*/
        

        /*---------- Controlador actualizar categoria ----------*/
		public function actualizar_categoria_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['categoria_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_categoria=mainModel::ejecutar_consulta_simple("SELECT * FROM categorias WHERE categoriaId='$id'");
            if($check_categoria->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado la categoría en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }else{
            	$campos=$check_categoria->fetch();
			}
			$check_categoria->closeCursor();
			$check_categoria=mainModel::desconectar($check_categoria);
            
            $nombre	=mainModel::limpiar_cadena(strtoupper($_POST['categoria_up']));

            /*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de categoría no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }

            /*== Comprobando nombre de categoria ==*/
            if($nombre != $campos['categoria']){
                $check_nombre=mainModel::ejecutar_consulta_simple("SELECT categoria FROM categorias WHERE categoria='$nombre'");
                if($check_nombre->rowCount()>0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre de categoría ingresado ya se encuentra registrado en el sistema",
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
			$datos_categoria_up=[
				"categoria"=>[
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				]
			];

			$condicion=[
				"condicion_campo"=>"categoriaId",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];


			if(mainModel::actualizar_datos("categorias",$datos_categoria_up,$condicion)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Categoría actualizada!",
					"Texto"=>"La categoría se actualizo con éxito en el sistema",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la categoría, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
        } /*-- Fin controlador --*/


        /*---------- Controlador eliminar categoria ----------*/
		public function eliminar_categoria_controlador(){

            /*== Recuperando id de la categoria ==*/
			$id=mainModel::decryption($_POST['categoria_id_del']);
			$id=mainModel::limpiar_cadena($id);

			/*== Comprobando categoria en la DB ==*/
            $check_categoria=mainModel::ejecutar_consulta_simple("SELECT categoriaId FROM categorias WHERE categoriaId='$id'");
            if($check_categoria->rowCount()<=0){
            	$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La categoría que intenta eliminar no existe en el sistema.",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			$check_categoria->closeCursor();
			$check_categoria=mainModel::desconectar($check_categoria);

            /*== Comprobando productos en categoria ==*/
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
			$check_productos=mainModel::desconectar($check_productos);
            
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

			$eliminar_categoria=mainModel::eliminar_registro("categorias","categoriaId",$id);

			if($eliminar_categoria->rowCount()==1){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Categoría eliminada!",
					"Texto"=>"La categoría ha sido eliminada del sistema exitosamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido eliminar la categoría del sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}

			$eliminar_categoria->closeCursor();
			$eliminar_categoria=mainModel::desconectar($eliminar_categoria);

			echo json_encode($alerta);
        } /*-- Fin controlador --*/
    }