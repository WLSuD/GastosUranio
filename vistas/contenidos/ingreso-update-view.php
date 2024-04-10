<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-hand-holding-dollar fa-fw"></i> &nbsp; Nuevo ingreso
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>ingreso-new/">
                <i class="fas fa-hand-holding-dollar fa-fw"></i> &nbsp; Nuevo ingreso
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>ingreso-lista/">
                <i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Lista de ingresos
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_ingreso   =   $lc->datos_tabla("Unico","ingresos","ingresoId",$pagina[1]);

        if($datos_ingreso->rowCount()==1){
            $campos=$datos_ingreso->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ingresoAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="ingresoId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_ingreso" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información del ingreso</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="descripcion" class="bmd-label-floating">Descripcion del ingreso <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{10,200}" class="form-control" name="descripcion_up" 
                                    value="<?php echo $campos['descripcion']; ?>" id="descripcion" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="entidad" class="bmd-label-floating">entidad <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="entidadId_up">
                                <?php
                                    $datos_entidad=$lc->datos_tabla("Normal","entidades","entidadId,entidad",0);
                                    $cp=1;
                                    while($campos_entidad = $datos_entidad->fetch()){

                                        if($campos_entidad['entidadId']==$campos['entidadId']){
                                            echo '<option value="'.$campos_entidad['entidadId'].'" selected="" >'.$cp.' - '.$campos_entidad['entidad'].' (Actual)</option>';
                                            
                                        }else{
                                                echo '<option value="'.$campos_entidad['entidadId'].'">'.$cp.' - '.$campos_entidad['entidad'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="obra" class="bmd-label-floating">Obra <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="obraId_up">
                                <?php
                                    $datos_obra=$lc->datos_tabla("Normal","obras","obraId,obra",0);
                                    $cp=1;
                                    while($campos_obra = $datos_obra->fetch()){

                                        if($campos_obra['obraId']==$campos['obraId']){
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
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="monto" class="bmd-label-floating">Monto de Ingreso <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[0-9.]{1,25}" class="form-control"  name="monto_up" value="<?php echo $campos['monto']; ?>" id="monto" maxlength="25">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="fecha" >Fecha de Ingreso (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_up" id="fecha" maxlength="30" value="<?php echo $campos['fecha']; ?>" >
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