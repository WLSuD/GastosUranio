<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-landmark-dome fa-fw"></i> &nbsp; Nueva entidad
    </h3>
    <!--<?php include "./vistas/desc/desc_entidad.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>entidad-new/">
                <i class="fas fa-landmark-dome fa-fw"></i> &nbsp; Nueva entidad
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>entidad-lista/">
                <i class="fas fa-landmark-flag fa-fw"></i> &nbsp; Lista de entidades
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/entidadAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_entidad" value="registrar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la entidad</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="entidad" class="bmd-label-floating">Nombre de la entidad <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,100}" class="form-control" name="entidad_reg" id="entidad" maxlength="100">
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