<?php
    include "./vistas/inc/admin_security.php";

    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Nuevo gasto
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>gasto-new/">
                <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Nuevo gasto
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gasto-lista/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-sack-xmark fa-fw"></i> &nbsp; Lista de gastos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_gasto" value="registrar">
        <fieldset>
            <legend><i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Información del gasto</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion" class="bmd-label-floating">Detalle <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#º\- ]{10,200}" class="form-control" name="detalle_reg" id="descripcion" maxlength="200">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Monto de Ingreso <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[0-9.]{1,25}" class="form-control" value="0.00" name="monto_reg" id="monto" maxlength="25">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="fecha" >Fecha de Ingreso (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_reg" id="fecha" maxlength="30" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Categoria <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="categoriaId_reg">
                                <option value="" selected="" >Categoria</option>
                                <?php
                                    $datos_categoria=$lc->datos_tabla("Normal","categorias","*",0);

                                    while($campos_categoria=$datos_categoria->fetch()){
                                            echo '<option value="'.$campos_categoria['categoriaId'].'"> '.$campos_categoria['categoria'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Subcategoria <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="subcatId_reg">
                                <option value="" selected="" >Subcategoria</option>
                                <?php
                                    $datos_subcat   =   $lc->datos_tabla("Normal","sub_categorias","*",0);

                                    while($campos_subcat=$datos_subcat->fetch()){
                                            echo '<option value="'.$campos_subcat['subcatId'].'"> '.$campos_subcat['subcategoria'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">asignacion <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="asignacionId_reg">
                                <option value="" selected="" >Asignacion</option>
                                <?php
                                    $datos_asignacion   =   $lc->datos_tabla("Normal","asignaciones","*",0);

                                    while($campos_asignacion=$datos_asignacion->fetch()){
                                            echo '<option value="'.$campos_asignacion['asignacionId'].'"> '.$campos_asignacion['asignacion'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Tipo de gasto <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="tipoGastoId_reg">
                                <option value="" selected="" >Tipo de gasto</option>
                                <?php
                                    $datos_tipoGasto   =   $lc->datos_tabla("Normal","tipo_gastos","*",0);

                                    while($campos_tipoGasto =   $datos_tipoGasto->fetch()){
                                            echo '<option value="'.$campos_tipoGasto['tipoGastoId'].'"> '.$campos_tipoGasto['tipoGasto'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br>
        <fieldset>
            <legend><i class="far fa-building fa-fw"></i> &nbsp; Información de la empresa</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Empresa <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="empresaId_reg">
                                <option value="" selected="" >Empresa</option>
                                <?php
                                    $datos_empresas=$lc->datos_tabla("Normal","empresas","*",0);

                                    while($campos_empresas=$datos_empresas->fetch()){
                                            echo '<option value="'.$campos_empresas['empresaId'].'"> '.$campos_empresas['empresa'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="codigo" class="bmd-label-floating">Obra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <?php echo $campos['abreviatura'] ?>
                            <input type="hidden" name="obraId_reg" value="<?php echo $campos['obraId'] ?>">
                            <!--<select class="form-control" name="obraId_reg">
                                <option value="" selected="" >Obra</option>
                                <?php
                                    $datos_obras=$lc->datos_tabla("Normal","obras","*",0);

                                    while($campos_obras=$datos_obras->fetch()){
                                            echo '<option value="'.$campos_obras['obraId'].'"> '.$campos_obras['obra'].'</option>';
                                    }
                                ?>
                            </select>-->
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br>
        <fieldset>
        <legend><i class="fas
         fa-dolly fa-fw"></i> &nbsp; Información del proveedor</legend>
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Proveedor <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="proveedorId_reg">
                                <option value="" selected="" >Proveedor</option>
                                <?php
                                    $datos_proveedores=$lc->datos_tabla("Normal","proveedores","*",0);
                                    while($campos_proveedores=$datos_proveedores->fetch()){
                                            echo '<option value="'.$campos_proveedores['proveedorId'].'"> '.$campos_proveedores['proveedor'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">comprobante <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="tipoComId_reg">
                                <option value="" selected="" >Comprobante</option>
                                <?php
                                    $datos_tipoCom=$lc->datos_tabla("Normal","tipo_comprobantes","*",0);
                                    while($campos_tipoCom=$datos_tipoCom->fetch()){
                                        echo '<option value="'.$campos_tipoCom['tipoComId'].'"> '.$campos_tipoCom['tipoCom'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="numeroCom" class="bmd-label-floating">Numero de comprobante <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{3,25}" class="form-control" name="numeroCom_reg" id="numeroCom" maxlength="200">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <p class="text-center" style="margin-top: 40px;">
            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
            <br>
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
        </p>
        <p class="text-center">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
</div>

<?php  } ?>