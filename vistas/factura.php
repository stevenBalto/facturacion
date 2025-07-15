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
                            <th>Dirección</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
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

<!-- Modal Eliminar Detalle -->
<div class="modal" id="eliminar-detalle-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Producto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                ¿Seguro deseas eliminar este producto de la factura?
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminarDetalle()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-detalle-id">
        </div>
    </div>
</div>
<!-- Modal Eliminar Detalle End -->

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
            <input type="date" class="form-control" id="fecha" value="<?php echo date('Y-m-d'); ?>" />
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
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>ID Producto</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right font-weight-bold">Total:</td>
                    <td class="font-weight-bold" id="total-factura">₡0.00</td>
                    <td class="m-0 px-0 py-2">
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
        // Crear el DataTable para mostrar todos los detalles existentes + nuevos locales
        tabla = $('#tabla').DataTable({
            "processing": false,
            "serverSide": false,
            "lengthChange": false,
            "searching": false,
            "ajax": {
                "url": "../ajax/detalle_factura.php?op=listar_todos_detalles", // Nuevo endpoint
                "type": "POST",
                "dataSrc": function(json) {
                    console.log("Todos los detalles:", json);
                    return json.aaData || [];
                },
                "error": function(xhr, error, thrown) {
                    console.log("Error en DataTable:", error, thrown);
                }
            },
            "columns": [
                { "title": "Cliente" },
                { "title": "Fecha" },
                { "title": "ID Producto" },
                { "title": "Nombre Producto" },
                { "title": "Cantidad" },
                { "title": "Precio Unitario" },
                { "title": "Subtotal" },
                { "title": "Acciones", "orderable": false }
            ],
            "destroy": true,
            "iDisplayLength": 10,
            "language": {
                "emptyTable": "No hay productos agregados a la factura",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron productos",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Calcular subtotal cuando cambie cantidad o precio
        $('#cantidad, #precioUnitario').on('input', function() {
            calcularSubtotal();
        });

        // La tabla se carga automáticamente con todos los datos al inicializar
        // Actualizar total después de cargar
        tabla.on('xhr.dt', function() {
            actualizarTotalLocal();
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
        var cedula = $('#cedula').val();
        var fecha = $('#fecha').val();

        // Validar que los campos de texto no estén vacíos
        if (idProducto === '' || cantidad === '' || precioUnitario === '' || cedula === '' || fecha === '') {
            Swal.fire('Todos los campos son obligatorios.');
            return;
        }

        // Calcular subtotal
        var subtotal = cantidad * precioUnitario;

        // Agregar fila directamente a la tabla (solo local, no a la BD)
        tabla.row.add([
            cedula,                                    // Cliente
            fecha,                                     // Fecha
            idProducto,                               // ID Producto
            nombre,                                   // Nombre Producto
            cantidad,                                 // Cantidad
            "₡" + Number(precioUnitario).toLocaleString('es-CR', {minimumFractionDigits: 2}), // Precio
            "₡" + Number(subtotal).toLocaleString('es-CR', {minimumFractionDigits: 2}),       // Subtotal
            '<button class="btn btn-danger btn-sm" onclick="eliminarFilaLocal(this)"><i class="fa fa-trash"></i></button>' // Acciones
        ]).draw();

        // Mostrar mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: 'Agregado',
            text: 'Producto agregado a la tabla (no guardado aún)',
            timer: 1500,
            showConfirmButton: false
        });

        // Limpiar los campos
        limpiar();
        
        // Actualizar total local
        actualizarTotalLocal();
    }

    function eliminarFilaLocal(btn) {
        // Obtener la fila que contiene el botón
        var fila = $(btn).closest('tr');
        
        // Eliminar la fila del DataTable
        tabla.row(fila).remove().draw();
        
        // Actualizar total
        actualizarTotalLocal();
        
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'Fila eliminada de la tabla local',
            timer: 1000,
            showConfirmButton: false
        });
    }

    function eliminarFila(btn) {
        // Obtener la fila que contiene el botón
        var fila = $(btn).closest('tr');

        // Eliminar la fila del DataTable
        tabla.row(fila).remove().draw();
    }

    // Función para mostrar el modal de confirmación (igual que en categoría)
    function eliminarDetalle(idDetalle) {
        console.log('Preparando eliminación de detalle con ID:', idDetalle); // Debug
        // Guardar el ID en el campo oculto
        $('#modal-detalle-id').val(idDetalle);
        // Mostrar el modal de confirmación
        $('#eliminar-detalle-modal').modal('show');
    }

    // Función que se ejecuta cuando se confirma la eliminación (igual que en categoría)
    function confirmarEliminarDetalle() {
        // Obtener el ID del campo oculto
        var idDetalle = $('#modal-detalle-id').val();
        console.log('Confirmando eliminación de detalle con ID:', idDetalle); // Debug
        
        $.ajax({
            url: '../ajax/detalle_factura.php?op=eliminar_detalle',
            type: 'POST',
            data: {
                idDetalle: idDetalle
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Debug
                
                try {
                    var resultado = JSON.parse(response);
                    
                    if (resultado.status === 'success') {
                        // Cerrar el modal
                        $('#eliminar-detalle-modal').modal('hide');
                        
                        // Mostrar mensaje de éxito (igual que en categoría)
                        Swal.fire({
                            title: 'Resultado',
                            text: resultado.message,
                            type: 'success', // Usar 'type' en lugar de 'icon'
                            confirmButtonText: 'OK'
                        });
                        
                        // Recargar la tabla
                        tabla.ajax.reload();
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            title: 'Error',
                            text: resultado.message,
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    // Si la respuesta no es JSON válido
                    if (response.includes('correctamente')) {
                        $('#eliminar-detalle-modal').modal('hide');
                        
                        Swal.fire({
                            title: 'Resultado',
                            text: response,
                            type: 'success',
                            confirmButtonText: 'OK'
                        });
                        
                        tabla.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al procesar la respuesta del servidor',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error AJAX:', textStatus, errorThrown); // Debug
                console.log('Respuesta completa:', jqXHR.responseText); // Debug
                
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar el producto: ' + textStatus,
                    type: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
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
        // Obtener los datos básicos
        var cedula = $("#cedula").val();
        var nombrecliente = $("#nombrecliente").val();
        var fecha = $("#fecha").val();

        // Validar campos básicos
        if (cedula == "" || nombrecliente == "" || fecha == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete todos los campos de cliente y fecha'
            });
            return;
        }

        // Obtener solo las filas que son nuevas (las que agregamos localmente)
        // Las filas nuevas tienen botón rojo, las de BD tienen botón amarillo
        var filasNuevas = [];
        tabla.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            if (data && data[7] && data[7].includes('btn-danger')) { // Botón rojo = fila nueva
                // Extraer valores limpios
                var precioText = data[5].toString().replace(/[₡,]/g, '');
                var subtotalText = data[6].toString().replace(/[₡,]/g, '');
                
                filasNuevas.push({
                    idProducto: data[2],
                    cantidad: data[4],
                    precioUnitario: parseFloat(precioText),
                    cedula: data[0],
                    fecha: data[1]
                });
            }
        });

        if (filasNuevas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin productos nuevos',
                text: 'No hay productos nuevos para guardar'
            });
            return;
        }

        // Guardar cada fila nueva
        var guardarPromesas = [];
        filasNuevas.forEach(function(fila) {
            var promesa = $.ajax({
                url: '../ajax/detalle_factura.php?op=agregar_detalle',
                type: 'POST',
                data: fila,
                dataType: 'json'
            });
            guardarPromesas.push(promesa);
        });

        // Ejecutar todas las promesas
        Promise.all(guardarPromesas)
            .then(function(resultados) {
                var exitosos = resultados.filter(r => r.status === 'success').length;
                
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: `Se guardaron ${exitosos} productos correctamente`,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Limpiar formulario
                $('#cedula').val('');
                $('#nombrecliente').val('');
                limpiar();
                
                // Recargar la tabla completa
                tabla.ajax.reload(function() {
                    actualizarTotalLocal();
                });
            })
            .catch(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar algunos productos'
                });
            });
    }

    function actualizarTotalLocal() {
        var total = 0;
        
        // Recorrer todas las filas de la tabla y sumar los subtotales
        tabla.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            if (data && data[6]) { // Columna 6 es el subtotal
                var subtotalText = data[6].toString();
                // Extraer el número del texto "₡1,500.00"
                var subtotal = parseFloat(subtotalText.replace(/[₡,]/g, ''));
                if (!isNaN(subtotal)) {
                    total += subtotal;
                }
            }
        });
        
        $('#total-factura').text('₡' + total.toLocaleString('es-CR', {minimumFractionDigits: 2}));
    }

    function actualizarTotal() {
        var cedula = $("#cedula").val();
        var fecha = $("#fecha").val();
        
        if (cedula === '' || fecha === '') {
            $('#total-factura').text('₡0.00');
            return;
        }
        
        $.ajax({
            url: '../ajax/factura.php?op=obtener_total_temporal',
            type: 'POST',
            data: {
                cedula: cedula,
                fecha: fecha
            },
            dataType: 'json',
            success: function(response) {
                $('#total-factura').text('₡' + response.total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Error silencioso - no mostrar en consola
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
                    // Error silencioso
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
                url: "../ajax/cliente.php?op=listar&select=true",
                type: "get",
                dataType: "json",
                error: function(e) {
                    // Error silencioso
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

    function selectCliente(cedula, nombre) {
        var $nombrecliente = document.getElementById('nombrecliente')
        var $cedula = document.getElementById('cedula')
        var $feedback = document.getElementById('cedula-feedback')

        // Usar directamente el nombre que viene del botón
        $nombrecliente.value = nombre;
        $cedula.value = cedula;
        if ($feedback) $feedback.innerText = '';
        $('#factura-modal').modal('hide');
        
        // Recargar la tabla con los datos del nuevo cliente
        tabla.ajax.reload();
        actualizarTotalLocal();
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