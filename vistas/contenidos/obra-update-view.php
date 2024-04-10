<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-trowel-bricks fa-fw"></i> &nbsp; Lista de obras
    </h3>
    <!--<?php include "./vistas/desc/desc_obra.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>obra-new/">
                <i class="fas fa-trowel fa-fw"></i> &nbsp; Nueva obra
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>obra-lista/">
                <i class="fas fa-trowel-bricks fa-fw"></i> &nbsp; Lista de obras
            </a>
        </li>
    </ul>	
</div>

<!--contenido de pagina-->
<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_obra=$lc->datos_tabla("Unico","obras","obraId",$pagina[1]);

        if($datos_obra->rowCount()==1){
            $campos=$datos_obra->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/obraAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="obraId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_obra" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la empresa</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="obra_nombre" class="bmd-label-floating">Nombre de la obra <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{10,100}" class="form-control" name="obra_up" 
                                    value="<?php echo $campos['obra']; ?>" id="obra" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="obra_abreviatura" class="bmd-label-floating">Abreviatura de la obra <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,25}" class="form-control" name="abreviatura_up" 
                                    value="<?php echo $campos['abreviatura']; ?>" id="abreviatura" maxlength="40">
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