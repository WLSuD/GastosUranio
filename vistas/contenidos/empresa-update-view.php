<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-sync fa-fw"></i> &nbsp; Actualizar empresa
    </h3>
    <!--<?php include "./vistas/desc/desc_categoria.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a href="<?php echo SERVERURL; ?>empresa-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nueva empresa
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>empresa-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de empresa
            </a>
        </li>
    </ul>	
</div>

<!--contenido de pagina-->
<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_empresa=$lc->datos_tabla("Unico","empresas","empresaId",$pagina[1]);

        if($datos_empresa->rowCount()==1){
            $campos=$datos_empresa->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/empresaAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="empresa_id_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_empresa" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información de la empresa</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="empresa_nombre" class="bmd-label-floating">Nombre de la empresa <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{3,40}" class="form-control" name="empresa_up" 
                                    value="<?php echo $campos['empresa']; ?>" id="empresa" maxlength="40">
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