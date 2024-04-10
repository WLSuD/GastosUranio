<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-sync fa-fw"></i> &nbsp; Actualizar Proveedor
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>proveedor-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo proveedor
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>proveedor-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de proveedores
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_proveedor    =   $lc->datos_tabla("Unico","proveedores","proveedorId",$pagina[1]);

        if($datos_proveedor->rowCount()==1){
            $campos=$datos_proveedor->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/proveedorAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="proveedorId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_proveedor" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información del proveedor</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="proveedor" class="bmd-label-floating">Nombre del proveedor <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,40}" class="form-control" name="proveedor_up" 
                                    value="<?php echo $campos['proveedor']; ?>" id="proveedor" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="documentoId_up">
                                <?php
                                    $datos_documento=$lc->datos_tabla("Normal","documentos","documentoId,documento",0);
                                    $cp=1;
                                    while($campos_documento = $datos_documento->fetch()){

                                        if($campos_documento['documentoId']==$campos['documentoId']){
                                            echo '<option value="'.$campos_documento['documentoId'].'" selected="" >'.$cp.' - '.$campos_documento['documento'].' (Actual)</option>';
                                            
                                        }else{
                                                echo '<option value="'.$campos_documento['documentoId'].'">'.$cp.' - '.$campos_documento['documento'].'</option>';
                                        }
                                        $cp++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="numeroDoc" class="bmd-label-floating">Numero de documento <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[0-99 ]{8,11}" class="form-control" name="numeroDoc_up" value="<?php echo $campos['numeroDoc']; ?>" id="numeroDoc" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="direccion" class="bmd-label-floating">direccion <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ . ]{10,150}" class="form-control" name="direccion_up" value="<?php echo $campos['direccion']; ?>" id="direccion" maxlength="40">
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