<?php
    include "./vistas/inc/admin_security.php";

    $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
?>
<div class="row">
    <div class="col-12 col-md-8">
        <h3 class="text-left "><!-- text-uppercase-->
            <i class="fas fa-calendar-plus fa-fw"></i> &nbsp; Obra <?php echo $campos['abreviatura'] ?>
        </h3>
    </div>
    <?php include "./vistas/desc/desc_regresos.php"; ?>
</div>

<div class="full-box page-header">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>ingreso-new/">
                <i class="fas fa-hand-holding-dollar fa-fw"></i> &nbsp; Nuevo ingreso
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>ingreso-lista/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Lista de ingresos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ingresoAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_ingreso" value="registrar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información del ingreso</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion" class="bmd-label-floating">Descripcions <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{10,200}" class="form-control" name="descripcion_reg" id="descripcion" maxlength="200">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="entidadId_reg">
                                <option value="" selected="" >Seleccione la Entidad</option>
                                <?php
                                    $datos_entidades=$lc->datos_tabla("Normal","entidades","*",0);

                                    while($campos_entidades=$datos_entidades->fetch()){
                                            echo '<option value="'.$campos_entidades['entidadId'].'"> '.$campos_entidades['entidad'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <!--<select class="form-control" name="obraId_reg">
                                <option value="" selected="" >Seleccione la Obra</option>
                                <?php
                                    $datos_obras=$lc->datos_tabla("Normal","obras","*",0);

                                    while($campos_obras=$datos_obras->fetch()){
                                            echo '<option value="'.$campos_obras['obraId'].'"> '.$campos_obras['obra'].'</option>';
                                    }
                                ?>
                            </select>-->
                            <label for="codigo" class="bmd-label-floating">Obra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <?php echo $campos['abreviatura'] ?>
                            <input type="hidden" name="obraId_reg" value="<?php echo $campos['obraId'] ?>">
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

<?php } ?>