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
            <a class="active" href="<?php echo SERVERURL; ?>programa-new/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-calendar-plus fa-fw"></i> &nbsp; Nuevo programa
            </a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>programa-lista/<?php echo $lc->encryption($campos['obraId'])?>/">
                <i class="fas fa-calendar-minus fa-fw"></i> &nbsp; Lista de programas
            </a>
        </li>
    </ul>	
</div>


<div class="container-fluid" >

    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/programaAjax.php" method="POST" data-form="save" autocomplete="off">
        <input type="hidden" name="modulo_programa" value="registrar">
        
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información del programa </legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="codigo" class="bmd-label-floating">Codigo <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input type="text" pattern="[a-zA-Z0-99áéíóúÁÉÍÓÚñÑ().,#\- ]{1,11}" class="form-control" value="propag-" name="codigo_reg" id="codigo" maxlength="11">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <!--<select id="listaObra" class="form-control" name="obraId_reg">
                                <option value="" selected="" >Seleccione la Obra</option>
                                <?php
                                    //$datos_ingresos=$lc->datos_tabla("Normal","ingresos","*",0);
                                    $datos_obras=$lc->ejecutar_consulta_simple_publica("SELECT * FROM obras");

                                    while($campos_obras =   $datos_obras->fetch()){
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
                            <label for="ingreso" class="bmd-label-floating">Ingreso <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <select class="form-control" name="ingresoId_reg">
                                <option value="" selected="" >Seleccione el ingreso</option>
                                <?php
                                    //$datos_ingresos=$lc->datos_tabla("Normal","ingresos","*",0);
                                    $datos_ingresos=$lc->datos_condicion2("ingresos","estado","obraId","libre",$campos['obraId']);

                                    while($campos_ingresos =   $datos_ingresos->fetch()){
                                            echo '<option value="'.$campos_ingresos['ingresoId'].'"> '.$campos_ingresos['descripcion'].
                                                        ' --- S/. '.number_format($campos_ingresos['monto'],2,'.',',').'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="fecha" >Fecha de Ingreso (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_reg" id="fecha" maxlength="30" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="saldo" class="bmd-label-floating">Saldo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        
                        <?php 
                            $obraId = $campos['obraId'];
                            $datos_programas = $lc->ejecutar_consulta_simple_publica("SELECT * FROM programas WHERE saldo > 0 AND obraId = $obraId AND estado = 'cerrado'"); 
                            if($datos_programas -> rowCount()>0){
                            $campos_programa = $datos_programas->fetch();
                                echo 'S/.'.$campos_programa['saldo'].' del  programa '.$campos_programa['codigo'];  
                        ?>
                        <input type="hidden" name="saldo_reg" value="<?php echo $campos_programa['saldo'] ?>">
                        <input type="hidden" name="programaAnt_reg" value="<?php echo $campos_programa['programaId'] ?>">

                        <?php }else{
                             echo 'Sin saldo a Sumar';
                            ?>
                                <input type="hidden" name="saldo_reg" value="0">
                                <input type="hidden" name="programaAnt_reg" value="0">
                            <?php  }?>
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
<?php 
        }else{
            include "./vistas/inc/error_alert.php";
        } 
    ?>


