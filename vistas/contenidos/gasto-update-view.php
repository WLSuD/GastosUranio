<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Editar de gasto
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>gasto-new/">
                <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Nuevo gasto
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gasto-lista/">
                <i class="fas fa-sack-xmark fa-fw"></i> &nbsp; Lista de gastos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_gasto   =   $lc->datos_tabla("Unico","gastos","gastoId",$pagina[1]);

        if($datos_gasto->rowCount()==1){
            $campos=$datos_gasto->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="gastoId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_gasto" value="actualizar">
        <fieldset>
            <legend><i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Información del gasto</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion" class="bmd-label-floating">Detalle <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#º\- ]{10,200}" class="form-control" value="<?php echo $campos['detalle']; ?>" name="detalle_up" id="descripcion" maxlength="200">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Monto de Ingreso <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[0-9.,]{1,25}" class="form-control" value="<?php echo number_format($campos['monto'],2,'.',',') ?>" name="monto_up" id="monto" maxlength="25">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="fecha" >Fecha de Ingreso (día/mes/año)</label>
                        <input type="date" class="form-control" value="<?php echo $campos['fecha'] ?>" name="fecha_up" id="fecha" maxlength="30"  >
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Categoria <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="categoriaId_up">
                                <option value="" selected="" >Categoria</option>
                                <?php
                                    $datos_categoria=$lc->datos_tabla("Normal","categorias","categoriaId,categoria",0);
                                    $cp=1;
                                    while($campos_categoria = $datos_categoria->fetch()){

                                        if($campos_categoria['categoriaId']    ==  $campos['categoriaId']){
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
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Subcategoria <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="subcatId_up">
                                <option value="" selected="" >Subcategoria</option>
                                <?php
                                    $datos_subcategoria=$lc->datos_tabla("Normal","sub_categorias","subcatId,subcategoria",0);
                                    $cp=1;
                                    while($campos_subcategoria = $datos_subcategoria->fetch()){

                                        if($campos_subcategoria['subcatId']==$campos['subcatId']){
                                            echo '<option value="'.$campos_subcategoria['subcatId'].'" selected="" >'.$cp.' - '.$campos_subcategoria['subcategoria'].' (Actual)</option>';
                                            
                                        }else{
                                                echo '<option value="'.$campos_subcategoria['subcatId'].'">'.$cp.' - '.$campos_subcategoria['subcategoria'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">asignacion <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="asignacionId_up">
                                <option value="" selected="" >Asignacion</option>
                                <?php
                                    $datos_asignacion=$lc->datos_tabla("Normal","asignaciones","asignacionId,asignacion",0);
                                    $cp=1;
                                    while($campos_asignacion = $datos_asignacion->fetch()){

                                        if($campos_asignacion['asignacionId']    ==  $campos['asignacionId']){
                                            echo '<option value="'.$campos_asignacion['asignacionId'].'" selected="" >'.$cp.' - '.$campos_asignacion['asignacion'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_asignacion['asignacionId'].'">'.$cp.' - '.$campos_asignacion['asignacion'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Tipo de gasto <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="tipoGastoId_up">
                                <option value="" selected="" >Tipo de gasto</option>
                                <?php
                                    $datos_tipoGasto=$lc->datos_tabla("Normal","tipo_gastos","tipoGastoId,tipoGasto",0);
                                    $cp=1;
                                    while($campos_tipoGasto = $datos_tipoGasto->fetch()){

                                        if($campos_tipoGasto['tipoGastoId']    ==  $campos['tipoGastoId']){
                                            echo '<option value="'.$campos_tipoGasto['tipoGastoId'].'" selected="" >'.$cp.' - '.$campos_tipoGasto['tipoGasto'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_tipoGasto['tipoGastoId'].'">'.$cp.' - '.$campos_tipoGasto['tipoGasto'].'</option>';
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
        <br><br>
        <fieldset>
            <legend><i class="far fa-building fa-fw"></i> &nbsp; Información de la empresa</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Empresa <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="empresaId_up">
                                <option value="" selected="" >Empresa</option>
                                <?php
                                    $datos_empresa=$lc->datos_tabla("Normal","empresas","empresaId,empresa",0);
                                    $cp=1;
                                    while($campos_empresa = $datos_empresa->fetch()){

                                        if($campos_empresa['empresaId']    ==  $campos['asignacionId']){
                                            echo '<option value="'.$campos_empresa['empresaId'].'" selected="" >'.$cp.' - '.$campos_empresa['empresa'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_empresa['empresaId'].'">'.$cp.' - '.$campos_empresa['empresa'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Obra <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="obraId_up">
                                <option value="" selected="" >Obra</option>
                                <?php
                                    $datos_obra=$lc->datos_tabla("Normal","obras","obraId,obra",0);
                                    $cp=1;
                                    while($campos_obra = $datos_obra->fetch()){

                                        if($campos_obra['obraId']    ==  $campos['obraId']){
                                            echo '<option value="'.$campos_obra['obraId'].'" selected="" >'.$cp.' - '.$campos_obra['obra'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_obra['obraId'].'">'.$cp.' - '.$campos_obra['obra'].'</option>';
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
        <br><br>
        <fieldset>
        <legend><i class="fas
         fa-dolly fa-fw"></i> &nbsp; Información del proveedor</legend>
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Proveedor <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="proveedorId_up">
                                <option value="" selected="" >Proveedor</option>
                                <?php
                                    $datos_proveedor    =   $lc->datos_tabla("Normal","proveedores","proveedorId,proveedor",0);
                                    $cp=1;
                                    while($campos_proveedor = $datos_proveedor->fetch()){
                                        if($campos_proveedor['proveedorId']    ==  $campos['proveedorId']){
                                            echo '<option value="'.$campos_proveedor['proveedorId'].'" selected="" >'.$cp.' - '.$campos_proveedor['proveedor'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_proveedor['proveedorId'].'">'.$cp.' - '.$campos_proveedor['proveedor'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">comprobante <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="tipoComId_up">
                                <option value="" selected="" >Comprobante</option>
                                <?php
                                    $datos_tipoCom    =   $lc->datos_tabla("Normal","tipo_comprobantes","tipoComId,tipoCom",0);
                                    $cp=1;
                                    while($campos_tipoCom = $datos_tipoCom->fetch()){

                                        if($campos_tipoCom['tipoComId']    ==  $campos['tipoComId']){
                                            echo '<option value="'.$campos_tipoCom['tipoComId'].'" selected="" >'.$cp.' - '.$campos_tipoCom['tipoCom'].' (Actual)</option>';
                                        }else{
                                                echo '<option value="'.$campos_tipoCom['tipoComId'].'">'.$cp.' - '.$campos_tipoCom['tipoCom'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="numeroCom" class="bmd-label-floating">Numero de comprobante <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{3,25}" class="form-control" value="<?php echo $campos['numeroCom'] ?>" name="numeroCom_reg" id="numeroCom" maxlength="200">
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