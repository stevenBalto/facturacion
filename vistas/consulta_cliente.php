<?php
$title = 'Consulta de Clientes';
ob_start();
?>

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3">
  <div class="row table-responsive pl-3">
    <div class="col-md-12 py-3 d-flex align-items-baseline">
      <div class="d-flex">
        <select id="campo" class="form-control mr-1" onchange="validarCampo()">
          <option value="cedula">Cédula</option>
          <option value="nombre">Nombre</option>
        </select>
        <input id="dato" class="form-control mx-1" type="text">
      </div>
      <button type="button" class="btn btn-primary ml-1" onclick="consultarCliente()">Buscar</button>
    </div>
    <div class="col-sm-12">
      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover d-none">
        <thead>
          <th>Cédula</th>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Dirección</th>
        </thead>
        <tbody></tbody>
        <tfoot>
          <th>Cédula</th>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Dirección</th>
        </tfoot>
      </table>
      <div class="mb-4">
        <h4>Total de clientes</h4>
        <button class="btn btn-outline-secondary" onclick="contarClientes()">Contar</button>
        <span id="totalClientes" class="ms-3 fw-bold"></span>
      </div>
    </div>

  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/includes/layout.php';

?>

<script>
  function consultarCliente() {
    var dato = document.getElementById('dato').value;
    if (dato === '') { Swal.fire('Debes ingresar un criterio de búsqueda'); return; }
    var campo = document.getElementById('campo').value;

    const $table = document.getElementById('tbllistado');
    const $tbody = $table.getElementsByTagName('tbody')[0];

    $.ajax({
      type: "POST",
      url: "../ajax/cliente.php?op=consultar",
      data: { campo, dato },
      success: function (response) {
        $tbody.innerHTML = '';
        var resultado = JSON.parse(response);

        if (resultado.length == 0) {
          const tr = document.createElement('tr');
          const td = document.createElement('td');
          td.colSpan = 4; td.innerText = 'Cliente no encontrado'; td.classList.add('text-center');
          tr.append(td); $tbody.append(tr);
        } else {
          for (let i = 0; i < resultado.length; i++) {
            const r = resultado[i], tr = document.createElement('tr');

            const td_ced = document.createElement('td'); td_ced.innerText = r.cedula;
            const td_nom = document.createElement('td'); td_nom.innerText = r.nombre;
            const td_tel = document.createElement('td'); td_tel.innerText = r.telefono;
            const td_dir = document.createElement('td'); td_dir.innerText = r.direccion;

            tr.append(td_ced, td_nom, td_tel, td_dir);
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
    // Cédula puede llevar guiones/letras en algunos sistemas, lo dejamos como texto
    if (campo == "cedula") { inputDato.type = 'text'; }
    else { inputDato.type = 'text'; }
  }

  function contarClientes() {
    $.get("../ajax/cliente.php?op=contar", function (resp) {
      const data = JSON.parse(resp || "{}");
      document.getElementById('totalClientes').textContent = 'Total: ' + (data.total ?? 0);
    });
  }
</script>