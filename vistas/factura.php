<?php 
require __DIR__ . '/includes/auth_guard.php';
$title = 'Factura';
ob_start();
?>

<h1 class="display-4">
    <?= $title ?>
</h1>

<div class="modal" id="factura-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h2 id="buscar-producto-title" class="d-none">Buscar Producto</h2>
                <h2 id="buscar-cliente-title" class="d-none">Buscar Cliente</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

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

            <div class="modal-footer" id="eliminar-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                <button id="btn-eliminar" class="btn btn-danger d-none" onclick="eliminar()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-id_producto">
        </div>
    </div>
</div>

<div class="modal" id="eliminar-detalle-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Eliminar Producto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                ¿Seguro deseas eliminar este producto de la factura?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminarDetalle()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-detalle-id">
        </div>
    </div>
</div>

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
    var tabla;
    // Índice de la fila que se está editando actualmente (null si no hay edición)
    var filaEditandoIndex = null;

    // Utilidad: parsear moneda formateada en es-CR (p. ej. "₡2 750,00" o "₡2.750,00") a número JS
    function parseCRCurrency(value) {
        if (value === null || value === undefined) return 0;
        let str = value.toString();
        // quitar símbolo y espacios (incluyendo NBSP y espacios finos)
        str = str.replace(/₡/g, '').replace(/[\s\u00A0\u202F]/g, '');
        // quitar separadores de miles y normalizar coma decimal a punto
        str = str.replace(/\./g, '').replace(/,/g, '.');
        const n = parseFloat(str);
        return isNaN(n) ? 0 : n;
    }

    function setEditMode(index) {
        filaEditandoIndex = index;
        const $btn = $('#agregarFila');
        $btn.removeClass('btn-success').addClass('btn-warning');
        $btn.html('<i class="fa fa-save"></i> Actualizar');
    }

    function resetEditMode() {
        filaEditandoIndex = null;
        const $btn = $('#agregarFila');
        $btn.removeClass('btn-warning').addClass('btn-success');
        $btn.html('<i class="fa fa-plus"></i> Agregar');
    }

    $(document).ready(function() {
        tabla = $('#tabla').DataTable({
            "processing": false,
            "serverSide": false,
            "lengthChange": false,
            "searching": false,
            "data": [], // Inicializar con datos vacíos
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

        $('#cantidad, #precioUnitario').on('input', function() {
            calcularSubtotal();
        });

        // Inicializar el total
        actualizarTotalLocal();
    });

    function calcularSubtotal() {
        var cantidad = parseFloat($('#cantidad').val()) || 0;
        var precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
        var subtotal = cantidad * precioUnitario;
        $('#subtotal').val(subtotal.toFixed(2));
    }

    function limpiar() {
        // Limpiar campos de producto
        $('#idProducto').val('');
        $('#nombre').val('');
        $('#cantidad').val('');
        $('#precioUnitario').val('');
        $('#subtotal').val('');
        
        // Limpiar mensajes de error
        $('#idProducto-feedback').text('');
        $('#cantidad_feedback').text('');
        
        // Enfocar el cursor en el primer campo
        $('#idProducto').focus();
    }

    function agregarFila() {
        var idProducto = $('#idProducto').val();
        var nombre = $('#nombre').val();
        var cantidad = $('#cantidad').val();
        var precioUnitario = $('#precioUnitario').val();
        var cedula = $('#cedula').val();
        var fecha = $('#fecha').val();

        if (idProducto === '' || cantidad === '' || precioUnitario === '' || cedula === '' || fecha === '') {
            Swal.fire('Todos los campos son obligatorios.');
            return;
        }

        var subtotal = Number(cantidad) * Number(precioUnitario);

        var accionesHtml = '' +
            '<button class="btn btn-warning btn-sm mr-2" onclick="editarFilaLocal(this)" title="Editar"><i class="fa fa-edit"></i></button>' +
            '<button class="btn btn-danger btn-sm" onclick="eliminarFilaLocal(this)" data-nuevo="true" title="Eliminar"><i class="fa fa-trash"></i></button>';

        if (filaEditandoIndex !== null) {
            // Actualizar la fila existente
            tabla.row(filaEditandoIndex).data([
                cedula,
                fecha,
                idProducto,
                nombre,
                cantidad,
                '₡' + Number(precioUnitario).toLocaleString('es-CR', { minimumFractionDigits: 2 }),
                '₡' + Number(subtotal).toLocaleString('es-CR', { minimumFractionDigits: 2 }),
                accionesHtml
            ]).draw(false);

            Swal.fire({
                icon: 'success',
                title: 'Actualizado',
                text: 'La fila fue actualizada',
                timer: 1200,
                showConfirmButton: false
            });

            resetEditMode();
        } else {
            // Agregar nueva fila
            tabla.row.add([
                cedula,
                fecha,
                idProducto,
                nombre,
                cantidad,
                '₡' + Number(precioUnitario).toLocaleString('es-CR', { minimumFractionDigits: 2 }),
                '₡' + Number(subtotal).toLocaleString('es-CR', { minimumFractionDigits: 2 }),
                accionesHtml
            ]).draw(false);

            Swal.fire({
                icon: 'success',
                title: 'Agregado',
                text: 'Producto agregado a la tabla (no guardado aún)',
                timer: 1500,
                showConfirmButton: false
            });
        }

        limpiar();
        actualizarTotalLocal();
    }

    function eliminarFilaLocal(btn) {
        var fila = $(btn).closest('tr');
        var idx = tabla.row(fila).index();
        // Si estamos eliminando la fila que está en edición, salir del modo edición
        if (filaEditandoIndex === idx) {
            resetEditMode();
            limpiar();
        }
        
        tabla.row(fila).remove().draw();
        
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
        var fila = $(btn).closest('tr');

        tabla.row(fila).remove().draw();
    }

    function eliminarDetalle(idDetalle) {
        console.log('Preparando eliminación de detalle con ID:', idDetalle); 
        $('#modal-detalle-id').val(idDetalle);
        $('#eliminar-detalle-modal').modal('show');
    }

    function confirmarEliminarDetalle() {
        var idDetalle = $('#modal-detalle-id').val();
        console.log('Confirmando eliminación de detalle con ID:', idDetalle); 
        
        $.ajax({
            url: '../ajax/detalle_factura.php?op=eliminar_detalle',
            type: 'POST',
            data: {
                idDetalle: idDetalle
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response); 
                
                try {
                    var resultado = JSON.parse(response);
                    
                    if (resultado.status === 'success') {
                        $('#eliminar-detalle-modal').modal('hide');
                        
                        Swal.fire({
                            title: 'Resultado',
                            text: resultado.message,
                            icon: 'success', 
                            confirmButtonText: 'OK'
                        });
                        
                    
                        actualizarTotalLocal();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: resultado.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    if (response.includes('correctamente')) {
                        $('#eliminar-detalle-modal').modal('hide');
                        
                        Swal.fire({
                            title: 'Resultado',
                            text: response,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        
                        
                        actualizarTotalLocal();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al procesar la respuesta del servidor',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error AJAX:', textStatus, errorThrown); 
                console.log('Respuesta completa:', jqXHR.responseText); 
                
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar el producto: ' + textStatus,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Cargar datos de una fila en los campos superiores y activar modo edición
    function editarFilaLocal(btn) {
        var fila = $(btn).closest('tr');
        var idx = tabla.row(fila).index();
        var data = tabla.row(fila).data();

        // data: [cedula, fecha, idProducto, nombre, cantidad, precioUnitarioFmt, subtotalFmt, acciones]
        $('#cedula').val(data[0]);
        $('#fecha').val(data[1]);
        $('#idProducto').val(data[2]);
        $('#nombre').val(data[3]);
        $('#cantidad').val(data[4]);
        $('#precioUnitario').val(parseCRCurrency(data[5]));
        calcularSubtotal();

        setEditMode(idx);
        // Enfocar primer campo de edición
        $('#idProducto').focus();
    }

    function guardarDatos() {
        var cedula = $("#cedula").val();
        var nombrecliente = $("#nombrecliente").val();
        var fecha = $("#fecha").val();

        if (cedula == "" || nombrecliente == "" || fecha == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete todos los campos de cliente y fecha'
            });
            return;
        }

      
        var filasNuevas = [];
        tabla.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            
            if (data && data[7] && data[7].includes('data-nuevo="true"')) {
                var precioText = data[5].toString().replace(/[^\d.-]/g, '');
                var subtotalText = data[6].toString().replace(/[^\d.-]/g, '');
                
                var filaData = {
                    idProducto: data[2],
                    cantidad: data[4],
                    precioUnitario: parseFloat(precioText),
                    cedula: data[0],
                    fecha: data[1]
                };
                
                console.log('Fila a guardar:', filaData); 
                filasNuevas.push(filaData);
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

        var guardarPromesas = [];
        filasNuevas.forEach(function(fila) {
            console.log('Enviando datos:', fila); 
            var promesa = $.ajax({
                url: '../ajax/detalle_factura.php?op=agregar_detalle',
                type: 'POST',
                data: fila,
                dataType: 'json'
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error en AJAX:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText);
            });
            guardarPromesas.push(promesa);
        });

        Promise.all(guardarPromesas)
            .then(function(resultados) {
                console.log('Resultados recibidos:', resultados); 
                var exitosos = resultados.filter(r => r && r.status === 'success').length;
                
                if (exitosos > 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: `Se guardaron ${exitosos} productos correctamente`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#cedula').val('');
                    $('#nombrecliente').val('');
                    limpiar();
                    
                    
                    tabla.clear().draw();
                    actualizarTotalLocal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron guardar los productos. Revisa la consola para más detalles.'
                    });
                }
            })
            .catch(function(error) {
                console.error('Error en Promise.all:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar los productos. Revisa la consola del navegador para más detalles.'
                });
            });
    }

    function actualizarTotalLocal() {
        var total = 0;
        tabla.rows().every(function() {
            var data = this.data();
            if (data && data.length > 6) {
                total += parseCRCurrency(data[6]);
            }
        });
        $('#total-factura').text('₡' + total.toLocaleString('es-CR', { minimumFractionDigits: 2 }));
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

    function listarProductos() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-cliente-title').addClass('d-none')
        $('#buscar-producto-title').removeClass('d-none')
        $('#listaProductos').removeClass('d-none')
        $('#listaClientes').addClass('d-none')


        $('#tbllistadoProductos').dataTable({
            "aProcessing": true, 
            "aServerSide": true, 
            dom: 'Bfrtip',
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
                  
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, 
            "order": [
                [0, "asc"]
            ] 
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

    function listarClientes() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-cliente-title').removeClass('d-none')
        $('#buscar-producto-title').addClass('d-none')
        document.getElementById("listaProductos").classList.add("d-none");

        $('#tbllistadoClientes').dataTable({
            "aProcessing": true, 
            "aServerSide": true, 
            dom: 'Bfrtip', 
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
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, 
            "order": [
                [0, "asc"]
            ] 
        }).DataTable();

        $('#listaClientes').removeClass('d-none')
        $('#factura-modal').modal('show')
    }

    function selectCliente(cedula, nombre) {
        var $nombrecliente = document.getElementById('nombrecliente')
        var $cedula = document.getElementById('cedula')
        var $feedback = document.getElementById('cedula-feedback')

        $nombrecliente.value = nombre;
        $cedula.value = cedula;
        if ($feedback) $feedback.innerText = '';
        $('#factura-modal').modal('hide');
        
        // Solo actualizar el total local
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