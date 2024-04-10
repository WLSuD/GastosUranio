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
            <a href="<?php echo SERVERURL; ?>entidad-new/">
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


<!--contenido de pagina-->
<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_entidad=$lc->datos_tabla("Unico","entidades","entidadId",$pagina[1]);

        if($datos_entidad->rowCount()==1){
            $campos=$datos_entidad->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/entidadAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="entidadId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_entidad" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la entidad</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="entidad_nombre" class="bmd-label-floating">Nombre de la entidad <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}" class="form-control" name="entidad_up" 
                                    value="<?php echo $campos['entidad']; ?>" id="entidad" maxlength="100">
                        </div>
                    </div>
                    <!--
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="categoria_estado" class="bmd-label-floating">Estado de la categoría</label>
                            <select class="form-control" name="categoria_estado_up" id="categoria_estado">
                                <?php
                                    $array_estado=["Habilitada","Deshabilitada"];
                                    echo $lc->generar_select($array_estado,$campos['categoria_estado']);
                                ?>
                            </select>
                        </div>
                    </div>-->
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