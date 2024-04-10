<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-people-carry-box fa-fw"></i> &nbsp; Nuevo proveedor
    </h3>
    <!--<?php include "./vistas/desc/desc_proveedor.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>proveedor-new/">
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
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/proveedorAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_proveedor" value="registrar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la proveedor</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="proveedor" class="bmd-label-floating">Nombre del proveedor <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ. ]{10,150}" class="form-control" name="proveedor_reg" id="proveedor" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="documentoId_reg">
                                <option value="" selected="" >Seleccione documento</option>
                                <?php
                                    $datos_documentos=$lc->datos_tabla("Normal","documentos","*",0);

                                    while($campos_documentos=$datos_documentos->fetch()){
                                            echo '<option value="'.$campos_documentos['documentoId'].'"> '.$campos_documentos['documento'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="numeroDoc" class="bmd-label-floating">Numero de documento <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[0-99 ]{8,11}" class="form-control" name="numeroDoc_reg" id="numeroDoc" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="direccion" class="bmd-label-floating">direccion <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ . ]{10,150}" class="form-control" name="direccion_reg" id="direccion" maxlength="40">
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