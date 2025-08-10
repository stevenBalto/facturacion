<?php
$title = 'Reporte de Factura';
ob_start();
?>
<div class="card p-3">
  <div class="row g-2 align-items-end">
    <div class="col-auto">
      <label class="mb-1">Número de factura</label>
      <input type="number" id="numFactura" class="form-control" min="1" placeholder="Ej. 1001">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary" onclick="abrirReporteFactura()">Ver reporte</button>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
include './includes/layout.php';
?>
<script>
function abrirReporteFactura(){
  const id = document.getElementById('numFactura').value.trim();
  if(!id){ return Swal.fire('Digite el número de la factura'); }
  window.open(`rptfactura.php?id=${encodeURIComponent(id)}`, '_blank');
}
</script>
</body>
</html>
