<?php 
	class vistasModelo{

		/*---------- Modelo obtener vistas ----------*/
		protected static function obtener_vistas_modelo($vistas){
			$listaBlanca=[	"dashboard","user-new","user-list","user-search","user-update",
							"category-new","category-list","category-search","category-update","product-category",
							"subcategoria-new","subcategoria-lista","subcategoria-update",
							"empresa-new","empresa-lista","empresa-update",
							"asignacion-new","asignacion-lista","asignacion-update",
							"tComprobante-new","tComprobante-lista","tComprobante-update",
							"documento-new","documento-lista","documento-update",
							"proveedor-new","proveedor-lista","proveedor-update",
							"obra-new","obra-lista","obra-update",
							"entidad-new","entidad-lista","entidad-update",
							"ingreso-new","ingreso-lista","ingreso-update",
							"gasto-new","gasto-lista","gasto-update",
							"programa-new","programa-lista","programa","gasto-programa","programa-detalle","programa-menu"
						];
			if(in_array($vistas, $listaBlanca)){
				if(is_file("./vistas/contenidos/".$vistas."-view.php")){
					$contenido="./vistas/contenidos/".$vistas."-view.php";
				}else{
					$contenido="404";
				}
			}elseif($vistas=="login" || $vistas=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}
			return $contenido;
		}

	}
	