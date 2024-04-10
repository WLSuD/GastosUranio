<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-sync fa-fw"></i> &nbsp; Actualizar subcategoría
    </h3>
    <?php include "./vistas/desc/desc_categoria.php"; ?>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>subcategory-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva subcategoría
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>subcategoria-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de subcategorías
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_subcategoria=$lc->datos_tabla("Unico","sub_categorias","subcatId",$pagina[1]);

        if($datos_subcategoria->rowCount()==1){
            $campos=$datos_subcategoria->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/subcategoriaAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="subcategoria_id_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_subcategoria" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la categoría</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="subcategoria_nombre" class="bmd-label-floating">Nombre de la subcategoría <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,40}" class="form-control" name="subcategoria_up" value="<?php echo $campos['subcategoria']; ?>" id="subcategoria" maxlength="40">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>            
            <legend><i class="fas fa-cash-register"></i> &nbsp; Categorias</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="categoria" class="bmd-label-floating">Categoria <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="subcategoria_categoria_up">
                                <?php
                                    $datos_categoria=$lc->datos_tabla("Normal","categorias","categoriaId,categoria",0);
                                    $cp=1;
                                    while($campos_categoria = $datos_categoria->fetch()){

                                        if($campos_categoria['categoriaId']==$campos['categoriaId']){
                                            echo '<option value="'.$campos_categoria['categoriaId'].'" selected="" >'.$cp.' - '.$campos_categoria['categoria'].' (Actual)</option>';
                                            
                                        }else{
                                                echo '<option value="'.$campos_categoria['categoriaId'].'">'.$cp.' - '.$campos_categoria['categoria'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <p class="text-center" style="margin-top: 40px;">
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync"></i> &nbsp; ACTUALIZAR</button>
        </p>
        <p class="text-center">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
    <?php 
        }else{
            include "./vistas/inc/error_alert.php";
        } 
    ?>
</div>