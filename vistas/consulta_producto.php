<?php
$title = 'Consulta de Productos';
ob_start();
?>

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3">
  <div class="row table-responsive pl-3">
    <div class="col-md-12 py-3 d-flex align-items-baseline">
      <div class="d-flex">
        <select id="campo" class="form-control mr-1" onchange="validarCampo()">
          <option value="id">Código (ID)</option>
          <option value="nombre">Nombre</option>
        </select>
        <input id="dato" class="form-control mx-1" type="number" min="0">
      </div>
      <button type="button" class="btn btn-primary ml-1" onclick="consultarProducto()">Buscar</button>
    </div>
    <div class="col-sm-12">
      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover d-none">
        <thead>
          <th>ID</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Categoría</th>
        </thead>
        <tbody></tbody>
        <tfoot>
          <th>ID</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Categoría</th>
        </tfoot>
      </table>
      <div class="mb-4">
        <h4>Producto más caro</h4>
        <button class="btn btn-outline-dark" onclick="consultarMasCaro()">Consultar</button>
        <div id="productoMasCaro" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/includes/layout.php';

?>

<script>
  function consultarProducto() {
    var dato = document.getElementById('dato').value;
    if (dato === '') { Swal.fire('Debes ingresar un criterio de búsqueda'); return; }
    var campo = document.getElementById('campo').value;

    const $table = document.getElementById('tbllistado');
    const $tbody = $table.getElementsByTagName('tbody')[0];

    $.ajax({
      type: "POST",
      url: "../ajax/producto.php?op=consultar",
      data: { campo, dato },
      success: function (response) {
        $tbody.innerHTML = '';
        var resultado = JSON.parse(response);

        if (resultado.length == 0) {
          const tr = document.createElement('tr');
          const td = document.createElement('td');
          td.colSpan = 4; td.innerText = 'Producto no encontrado'; td.classList.add('text-center');
          tr.append(td); $tbody.append(tr);
        } else {
          for (let i = 0; i < resultado.length; i++) {
            const r = resultado[i], tr = document.createElement('tr');

            const td_id = document.createElement('td'); td_id.innerText = r.id;
            const td_nom = document.createElement('td'); td_nom.innerText = r.nombre;
            const td_prec = document.createElement('td'); td_prec.innerText = r.precio;

            // Si el endpoint devuelve nombre de categoría como 'categoria', úsalo; si no, muestra idCategoria.
            const td_cat = document.createElement('td'); td_cat.innerText = (r.categoria ?? r.idCategoria);

            tr.append(td_id, td_nom, td_prec, td_cat);
            $tbody.append(tr);
          }
        }
        $table.classList.remove('d-none');
      }
    });
  }

  function validarCampo() {
    var campo = document.getElementById('campo').value;
    const inputDato = document.getElementById('dato');
    inputDato.value = '';
    if (campo == "id") { inputDato.type = 'number'; inputDato.min = 0; }
    else { inputDato.type = 'text'; inputDato.removeAttribute('min'); }
  }
  function consultarMasCaro() {
    $.get("../ajax/producto.php?op=mas_caro", function (resp) {
      const d = JSON.parse(resp || "{}");
      const cont = document.getElementById('productoMasCaro');
      if (d && d.id) {
        cont.innerHTML =
          `<div class="alert alert-info mb-0">
           <strong>ID:</strong> ${d.id} &nbsp;|&nbsp;
           <strong>Nombre:</strong> ${d.nombre} &nbsp;|&nbsp;
           <strong>Precio:</strong> ${Number(d.precio).toFixed(2)} &nbsp;|&nbsp;
           <strong>Categoría:</strong> ${d.categoria ?? d.idCategoria}
         </div>`;
      } else {
        cont.innerHTML = `<div class="alert alert-warning mb-0">No hay productos.</div>`;
      }
    });
  }
</script>