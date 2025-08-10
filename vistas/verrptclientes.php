<?php
$title = 'Reporte de Clientes';
ob_start();
?>
<button type="button" class="btn btn-success" onclick="abrirReporte()">
  <i class="fa fa-file"></i> Ver Reporte de Clientes
</button>
<?php
$content = ob_get_clean();
include './includes/layout.php';
?>
<script>
function abrirReporte(){ window.open('rptclientes.php', '_blank'); }
</script>
</body>
</html>
