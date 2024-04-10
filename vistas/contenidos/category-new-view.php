<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva categoría
    </h3>
    <!--<?php include "./vistas/desc/desc_categoria.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>category-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva categoría
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>category-list/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de categorías
            </a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/categoriaAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_categoria" value="registrar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la categoría</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="categoria_nombre" class="bmd-label-floating">Nombre de la categoría <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,40}" class="form-control" name="categoria_nombre_reg" id="categoria_nombre" maxlength="40">
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