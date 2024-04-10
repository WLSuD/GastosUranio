<!--=============================================
=            Include JavaScript files           =
==============================================-->
<!-- popper -->
<script src="<?php echo SERVERURL; ?>vistas/js/popper.min.js" ></script>

<!-- Bootstrap V4.3 -->
<script src="<?php echo SERVERURL; ?>vistas/js/bootstrap.min.js" ></script>

<!-- SnackbarJS plugin -->
<script src="<?php echo SERVERURL; ?>vistas/js/snackbar.min.js" ></script>

<!-- Bootstrap Material Design V4.0 -->
<script src="<?php echo SERVERURL; ?>vistas/js/bootstrap-material-design.min.js" ></script>
<script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>

<!-- printThis  -->
<script src="<?php echo SERVERURL; ?>vistas/js/printThis.js" ></script>

<script src="<?php echo SERVERURL; ?>vistas/js/main.js" ></script>
<script src="<?php echo SERVERURL; ?>vistas/js/ajax.js" ></script>
<script src="https://kit.fontawesome.com/7e7a9f60f6.js" crossorigin="anonymous"></script>

<!-- SCRIPT DEL DATABLE  EN LISTAS --->
<script>
  $(function () {
    $('#example1').DataTable({
      responsive: true,
      language    : {
        url: 'https:////cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json'
      }
    })
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      
      
    })
  })
</script>


