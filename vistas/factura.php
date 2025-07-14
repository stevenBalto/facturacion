<?php
$title = 'Factura';
ob_start();
?>

<h1 class="display-4">
    <?= $title ?>
</h1>

<!-- Factura Modal -->
<div class="modal" id="factura-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2 id="buscar-producto-title" class="d-none">Buscar Producto</h2>
                <h2 id="buscar-cliente-title" class="d-none">Buscar Cliente</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="modal-body">
                <h4 id="delete-message" style="display:none"></h4>
                <div class="row table-responsive pl-3 d-none" id="listaProductos">
                    <table id="tbllistadoProductos" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Opciones</th>
                        </tfoot>
                    </table>
                </div>

                <div class="row table-responsive pl-3 d-none" id="listaClientes">
                    <table id="tbllistadoClientes" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Opciones</th>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer" id="eliminar-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                <button id="btn-eliminar" class="btn btn-danger d-none" onclick="eliminar()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-id_producto">
        </div>
    </div>
</div>
<!-- Factura Modal End -->

<div class="mb-3 card p-3">
    <div class="row">
        <div class="col-sm-3">
            <label for="cedula">Cedula:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control" type="text" id="cedula" name="cedula" onblur="buscarCliente()">
                    <small class="text-danger" id="cedula-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-secondary" id="listar-clientes" onclick="listarClientes()">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <label for="nombrecliente">Nombre:</label>
            <input type="text" class="form-control" id="nombrecliente" />
            <small id="nombrecliente-feedback" class="text-danger"></small>
        </div>
        <div class="col-sm-3">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control" id="fecha" />
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-2">
            <label for="idProducto">ID Producto:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control input-producto" type="text" id="idProducto" name="idProducto" onblur="buscarProducto()">
                    <small class="text-danger" id="idProducto-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-secondary input-producto" id="listar-productos" onclick="listarProductos()">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <label for="nombre">Nombre:</label>
            <input class="form-control input-producto" type="text" id="nombre" name="nombre">
        </div>
        <div class="col-sm-2">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control input-producto" id="cantidad" />
            <small id="cantidad_feedback" class="text-danger"></small>
        </div>
        <div class="col-sm-2">
            <label for="precioUnitario">Precio Unitario:</label>
            <input type="number" step="0.01" class="form-control input-producto" id="precioUnitario" />
        </div>
        <div class="col-sm-2">
            <label for="subtotal">Subtotal:</label>
            <input type="number" step="0.01" class="form-control input-producto" id="subtotal" readonly />
        </div>
        <button onclick="agregarFila()" id="agregarFila" class="btn btn-success input-producto"><i class="fa fa-plus"></i> Agregar</button>
    </div>

    <br>

    <div class="row table-responsive p-3 border-top" id="listaDatos">
        <h2>Datos</h2>
        <br>
        <table id="tabla" class="table">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="m-0 px-0 py-2">
                        <hr>
                        <button onclick="guardarDatos()" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<?php
$content = ob_get_clean();
include './includes/layout.php';
?>

<script>
    // Crear una variable global para el DataTable
    var tabla;

    $(document).ready(function() {
        // Crear el DataTable con columnas adicionales para los botones de eliminar y editar
        tabla = $('#tabla').DataTable({
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<button class='btn btn-sm btn-warning' onclick='editarFila(this)'>Editar</button> <button onclick='eliminarFila(this)' class='btn btn-sm btn-danger'>Eliminar</button>"
            }]
        });

        // Calcular subtotal cuando cambie cantidad o precio
        $('#cantidad, #precioUnitario').on('input', function() {
            calcularSubtotal();
        });
    });

    function calcularSubtotal() {
        var cantidad = parseFloat($('#cantidad').val()) || 0;
        var precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
        var subtotal = cantidad * precioUnitario;
        $('#subtotal').val(subtotal.toFixed(2));
    }

    function limpiar() {
        $('#idProducto').val('');
        $('#nombre').val('');
        $('#cantidad').val('');
        $('#precioUnitario').val('');
        $('#subtotal').val('');
    }

    function agregarFila() {
        // Obtener los valores de los cuadros de texto
        var idProducto = $('#idProducto').val();
        var nombre = $('#nombre').val();
        var cantidad = $('#cantidad').val();
        var precioUnitario = $('#precioUnitario').val();
        var subtotal = $('#subtotal').val();

        // Validar que los campos de texto no estén vacíos
        if (idProducto === '' || nombre === '' || cantidad === '' || precioUnitario === '') {
            Swal.fire('Todos los campos son obligatorios.');
            return;
        }

        // Agregar la fila al DataTable
        tabla.row.add([idProducto, nombre, cantidad, precioUnitario, subtotal]).draw();

        // Limpiar los valores de los cuadros de texto
        limpiar()
    }

    function eliminarFila(btn) {
        // Obtener la fila que contiene el botón
        var fila = $(btn).closest('tr');

        // Eliminar la fila del DataTable
        tabla.row(fila).remove().draw();
    }

    function editarFila(btn) {

        // Obtener la fila que contiene el botón
        var fila = $(btn).closest('tr');

        // Obtener los valores de la fila
        var idProducto = tabla.cell(fila, 0).data();
        var nombre = tabla.cell(fila, 1).data();
        var cantidad = tabla.cell(fila, 2).data();
        var precioUnitario = tabla.cell(fila, 3).data();
        var subtotal = tabla.cell(fila, 4).data();

        //Mostramos los valores en las cajas de texto
        $('#idProducto').val(idProducto);
        $('#nombre').val(nombre);
        $('#cantidad').val(cantidad);
        $('#precioUnitario').val(precioUnitario);
        $('#subtotal').val(subtotal);

        // Eliminar la fila del DataTable
        tabla.row(fila).remove().draw();

    }

    function guardarDatos() {
        // Obtener los datos del DataTable y convertirlos en un objeto JSON
        var cedula = $("#cedula").val();
        var nombrecliente = $("#nombrecliente").val();
        var fecha = $("#fecha").val();
        var detalle = tabla.rows().data().toArray();

        if (cedula == "" || nombrecliente == "" || fecha == "" || detalle.length == 0) {
            Swal.fire('Faltan Datos');
            return
        }

        var encabezado = {
            "cedula": cedula,
            "nombre": nombrecliente,
            "fecha": fecha
        }

        // Enviar los datos a un archivo PHP utilizando AJAX
        $.ajax({
            url: '../ajax/factura.php',
            type: 'POST',
            data: {
                encabezado: encabezado,
                detalle: JSON.stringify(detalle)
            },
            dataType: 'json',
            success: function(response) {},
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Datos Insertados');
                tabla.clear().draw();
                //Mostramos los valores en las cajas de texto
                $('#cedula').val('');
                $('#nombrecliente').val('');
                $('#fecha').val('');
            }
        });
    }

    function buscarProducto() {
        var idProducto = $("#idProducto").val();
        var $feedback = document.getElementById('idProducto-feedback')

        if (idProducto == "") {
            $feedback.innerText = ''
            $('#nombre').val('')
            $('#precioUnitario').val('')
            return
        }

        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=mostrar",
            data: {
                id: idProducto
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                if (resultado == null) {
                    $feedback.innerText = 'Producto no existe'
                } else {
                    document.getElementById("nombre").value = resultado.nombre;
                    document.getElementById("precioUnitario").value = resultado.precio;
                    document.getElementById("productoId").value = resultado.id;
                    $feedback.innerText = ''
                    calcularSubtotal();
                }

            }
        });
    }

    function mostrar(id) {
        habilitar_botones();
        $.ajax({
            type: "POST",
            url: "../ajax/factura.php?op=mostrar",
            data: {
                id
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                document.getElementById("fecha").value = resultado['fecha'];
                document.getElementById("cedula").value = resultado['cedula'];
                document.getElementById("idProducto").value = resultado['idProducto'];
                document.getElementById("facturaId").value = resultado['idfactura'];
                document.getElementById("detalleId").value = resultado['iddetalle'];
                $("#idProducto").blur()
                $("#cedula").blur()
            }
        });
    }

    // Listar productos
    function listarProductos() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-cliente-title').addClass('d-none')
        $('#buscar-producto-title').removeClass('d-none')
        $('#listaProductos').removeClass('d-none')
        $('#listaClientes').addClass('d-none')


        $('#tbllistadoProductos').dataTable({
            "aProcessing": true, //Activamos el procesamiento del datatables
            "aServerSide": true, //Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip', //Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax": {
                url: "../ajax/producto.php?op=listar&select=true",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, //Paginación
            "order": [
                [0, "asc"]
            ] //Ordenar (columna,orden)
        }).DataTable();

        $('#factura-modal').modal('show')
    }

    function editar(id) {
        var $idProducto = document.getElementById('idProducto')
        var $nombre = document.getElementById('nombre')
        var $precioUnitario = document.getElementById('precioUnitario')
        var $feedback = document.getElementById('idProducto-feedback').innerText = ''


        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=mostrar",
            data: {
                id: id
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $idProducto.value = resultado.id;
                $nombre.value = resultado.nombre;
                $precioUnitario.value = resultado.precio;
                calcularSubtotal();
            }
        });
    }

    function buscarCliente() {
        var cedula = document.getElementById('cedula').value
        var $nombrecliente = document.getElementById('nombrecliente')
        var $feedback = document.getElementById('cedula-feedback')

        $nombrecliente.value = ''

        if (cedula == "") {
            $feedback.innerText = ''
            return
        }

        $.ajax({
            type: "POST",
            url: "../ajax/cliente.php?op=buscar",
            data: {
                cedula
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                if (resultado == null) {
                    $feedback.innerText = 'Cliente no existe'
                } else {
                    $nombrecliente.value = resultado.nombre;
                    $feedback.innerText = ''
                }
            }
        });
    }

    // Listar Clientes
    function listarClientes() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-cliente-title').removeClass('d-none')
        $('#buscar-producto-title').addClass('d-none')
        document.getElementById("listaProductos").classList.add("d-none");

        $('#tbllistadoClientes').dataTable({
            "aProcessing": true, //Activamos el procesamiento del datatables
            "aServerSide": true, //Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip', //Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax": {
                url: "../ajax/cliente.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, //Paginación
            "order": [
                [0, "asc"]
            ] //Ordenar (columna,orden)
        }).DataTable();

        $('#listaClientes').removeClass('d-none')
        $('#factura-modal').modal('show')
    }

    function selectCliente(cedula) {
        var $nombrecliente = document.getElementById('nombrecliente')
        var $cedula = document.getElementById('cedula')
        var $feedback = document.getElementById('cedula-feedback').innerText = ''

        $.ajax({
            type: "POST",
            url: "../ajax/cliente.php?op=mostrar",
            data: {
                cedula
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $nombrecliente.value = resultado.nombre;
                $cedula.value = resultado.cedula;
                $('#factura-modal').modal('hide')
            }
        });
    }

    function selectProducto(id) {
        var $idProducto = document.getElementById('idProducto')
        var $nombre = document.getElementById('nombre')
        var $precioUnitario = document.getElementById('precioUnitario')
        var $feedback = document.getElementById('idProducto-feedback').innerText = ''

        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=mostrar",
            data: {
                id
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $("#idProducto").val(resultado.id);
                $("#nombre").val(resultado.nombre);
                $("#precioUnitario").val(resultado.precio);
                $('#factura-modal').modal('hide')
                calcularSubtotal();
            }
        });
    }
</script>

</body>

</html>