<?php
    include "./vistas/inc/admin_security.php";
?>
<div class="full-box page-header">
    <h3 class="text-left text-uppercase">
        <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo tipo de comprobante
    </h3>
    <!--<?php include "./vistas/desc/desc_tComprobante.php"; ?>-->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs text-uppercase">
        <li>
            <a  href="<?php echo SERVERURL; ?>tComprobante-new/">
                <i class="fas fa-tags fa-fw"></i> &nbsp; Nuevo tipo de comprobante
            </a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>tComprobante-lista/">
                <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de tipos de comprobantes
            </a>
        </li>
    </ul>	
</div>

<!--contenido de pagina-->
<div class="container-fluid">
    <?php
        include "./vistas/inc/btn_go_back.php";
        
        $datos_tComprobante=$lc->datos_tabla("Unico","tipo_comprobantes","tipoComId",$pagina[1]);

        if($datos_tComprobante->rowCount()==1){
            $campos =   $datos_tComprobante->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/tComprobanteAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="tipoComId_up" value="<?php echo $pagina[1]; ?>" >
        <input type="hidden" name="modulo_tComprobante" value="actualizar">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información del tipo de comprobante</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="tipocom" class="bmd-label-floating">Nombre del tipo de comprobante <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{5,50}" class="form-control" name="tipoCom_up" 
                                    value="<?php echo $campos['tipoCom']; ?>" id="tipoCom" maxlength="40">
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
        <br><br><br>
        <fieldset>
            <legend><i class="fas fa-shield-halved"></i> &nbsp; Codigo de comprobante</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="codigo" class="bmd-label-floating">codigo de comprobante</label>
                            <input type="text"  class="form-control" name="codigo_up" value="<?php echo $campos['codigo'] ?>" id="codigo" maxlength="40">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend><i class="fas fa-cash-register"></i> &nbsp; Codigo de sunat</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="idSunat" class="bmd-label-floating">Id de sunat</label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ ]{2,10}" class="form-control" name="idSunat_up" value="<?php echo $campos['idSunat']?>" id="idSunat" maxlength="40">
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