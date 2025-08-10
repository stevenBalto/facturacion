<?php
$title = 'Reporte por Cliente';
ob_start();
?>
<div class="card p-3">
  <div class="row g-2 align-items-end">
    <div class="col-auto">
      <label class="mb-1">Cédula</label>
      <input type="text" id="cedula" class="form-control" placeholder="1-2345-6789">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary" onclick="abrirRptCliente()">Ver reporte</button>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
include './includes/layout.php';
?>
<script>
function abrirRptCliente(){
  const ced = document.getElementById('cedula').value.trim();
  if(!ced){ return Swal.fire('Digite la cédula'); }
  window.open(`rptcliente.php?cedula=${encodeURIComponent(ced)}`, '_blank');
}
</script>
</body>
</html>
