<?php
$title = 'Prestamo';
ob_start();
?>

<h1 class="display-4">
    <?= $title ?>
</h1>

<!-- Prestamo Modal -->
<div class="modal" id="prestamo-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2 id="buscar-libro-title" class="d-none">Buscar Libro</h2>
                <h2 id="buscar-estudiante-title" class="d-none">Buscar Estudiante</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="modal-body">
                <h4 id="delete-message" style="display:none"></h4>
                <div class="row table-responsive pl-3 d-none" id="listaLibros">
                    <table id="tbllistadoLibros" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Código</th>
                            <th>Título</th>
                            <th>Género</th>
                            <th>Autor</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>Código</th>
                            <th>Título</th>
                            <th>Género</th>
                            <th>Autor</th>
                            <th>Opciones</th>
                        </tfoot>
                    </table>
                </div>

                <div class="row table-responsive pl-3 d-none" id="listaEstudiantes">
                    <table id="tbllistadoEstudiantes" class="table table-striped table-bordered table-condensed table-hover">
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

            <input type="hidden" id="modal-cod_libro">
        </div>
    </div>
</div>
<!-- Prestamo Modal End -->

<div class="mb-3 card p-3">
    <div class="row">
        <div class="col-sm-3">
            <label for="cedula">Cedula:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control" type="text" id="cedula" name="cedula" onblur="buscarEstudiante()">
                    <small class="text-danger" id="cedula-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-secondary" id="listar-estudiantes" onclick="listarEstudiantes()">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <label for="nombreestudiante">Nombre:</label>
            <input type="text" class="form-control" id="nombreestudiante" />
            <small id="nombreestudiante-feedback" class="text-danger"></small>
        </div>
        <div class="col-sm-3">
            <label for="fechaboleta">Fecha:</label>
            <input type="date" class="form-control" id="fechaboleta" />
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="codigo">Código Libro:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control input-libro" type="text" id="codigo" name="codigo" onblur="buscarLibro()">
                    <small class="text-danger" id="codigo-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-secondary input-libro" id="listar-libros" onclick="listarLibros()">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <label for="nombre">Nombre:</label>
            <input class="form-control input-libro" type="text" id="nombre" name="nombre">
        </div>
        <div class="col-sm-3">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control input-libro" id="fecha" />
            <small id="fecha_feedback" class="text-danger"></small>
        </div>
        <button onclick="agregarFila()" id="agregarFila" class="btn btn-success input-libro"><i class="fa fa-plus"></i> Agregar</button>
    </div>

    <br>

    <div class="row table-responsive p-3 border-top" id="listaDatos">
        <h2>Datos</h2>
        <br>
        <table id="tabla" class="table">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="m-0 px-0 py-2">
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
    });

    function limpiar() {
        $('#codigo').val('');
        $('#nombre').val('');
        $('#fecha').val('');
    }

    function agregarFila() {
        // Obtener los valores de los cuadros de texto
        var codigo = $('#codigo').val();
        var nombre = $('#nombre').val();
        var fecha = $('#fecha').val();

        // Validar que los campos de texto no estén vacíos
        if (codigo === '' || nombre === '' || fecha === '') {
            Swal.fire('Todos los campos son obligatorios.');
            return;
        }

        // Agregar la fila al DataTable
        tabla.row.add([codigo, nombre, fecha]).draw();

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
        var codigo = tabla.cell(fila, 0).data();
        var nombre = tabla.cell(fila, 1).data();
        var fecha = tabla.cell(fila, 2).data();

        //Mostramos los valores en las cajas de texto
        $('#codigo').val(codigo);
        $('#nombre').val(nombre);
        $('#fecha').val(fecha);

        // Eliminar la fila del DataTable
        tabla.row(fila).remove().draw();

    }

    function guardarDatos() {
        // Obtener los datos del DataTable y convertirlos en un objeto JSON
        var cedula = $("#cedula").val();
        var nombreestudiante = $("#nombreestudiante").val();
        var fecha = $("#fechaboleta").val();
        var detalle = tabla.rows().data().toArray();

        if (cedula == "" || nombreestudiante == "" || fecha == "" || detalle.length == 0) {
            Swal.fire('Faltan Datos');
            return
        }

        var encabezado = {
            "cedula": cedula,
            "nombre": nombreestudiante,
            "fecha": fecha
        }

        // Enviar los datos a un archivo PHP utilizando AJAX
        $.ajax({
            url: '../ajax/prestamo.php',
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
                $('#nombreestudiante').val('');
                $('#fechaboleta').val('');
            }
        });
    }

    function buscarLibro() {
        var codigo = $("#codigo").val();
        var $feedback = document.getElementById('codigo-feedback')

        if (codigo == "") {
            $feedback.innerText = ''
            $('#nombre').val('')
            return
        }

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=mostrar",
            data: {
                cod_libro: codigo
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                if (resultado == null) {
                    $feedback.innerText = 'Libro no existe'
                } else {
                    document.getElementById("nombre").value = resultado.titulo;
                    document.getElementById("libroId").value = resultado.id;
                    $feedback.innerText = ''
                }

            }
        });
    }

    function mostrar(id) {
        habilitar_botones();
        $.ajax({
            type: "POST",
            url: "../ajax/prestamo.php?op=mostrar",
            data: {
                id
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                document.getElementById("fechaLibro").value = resultado['fechaLibro'];
                document.getElementById("fechaEstudiante").value = resultado['fechaEstudiante'];
                document.getElementById("codigo").value = resultado['codigo'];
                document.getElementById("cedula").value = resultado['cedula'];
                document.getElementById("prestamoId").value = resultado['idprestamo'];
                document.getElementById("detalleId").value = resultado['iddetalle'];
                $("#codigo").blur()
                $("#cedula").blur()
            }
        });
    }

    // Listar libros
    function listarLibros() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-estudiante-title').addClass('d-none')
        $('#buscar-libro-title').removeClass('d-none')
        $('#listaLibros').removeClass('d-none')
        $('#listaEstudiantes').addClass('d-none')


        $('#tbllistadoLibros').dataTable({
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
                url: "../ajax/libro.php?op=listar&select=true",
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

        $('#prestamo-modal').modal('show')
    }

    function editar(codigo) {
        var $codigo = document.getElementById('codigo')
        var $nombre = document.getElementById('nombre')
        var $feedback = document.getElementById('codigo-feedback').innerText = ''


        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=mostrar",
            data: {
                cod_libro: codigo
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $codigo.value = resultado.codigo;
                $nombre.value = resultado.titulo;
            }
        });
    }

    function buscarEstudiante() {
        var cedula = document.getElementById('cedula').value
        var $nombreestudiante = document.getElementById('nombreestudiante')
        var $feedback = document.getElementById('cedula-feedback')

        $nombreestudiante.value = ''

        if (cedula == "") {
            $feedback.innerText = ''
            return
        }

        $.ajax({
            type: "POST",
            url: "../ajax/estudiante.php?op=buscar",
            data: {
                cedula
            },
            success: function(response) {
                var resultado = JSON.parse(response);

                if (resultado == null) {
                    $feedback.innerText = 'Estudiante no existe'
                } else {
                    $nombreestudiante.value = resultado.nombre;
                    $feedback.innerText = ''
                }
            }
        });
    }

    // Listar Estudiantes
    function listarEstudiantes() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-estudiante-title').removeClass('d-none')
        $('#buscar-libro-title').addClass('d-none')
        document.getElementById("listaLibros").classList.add("d-none");

        $('#tbllistadoEstudiantes').dataTable({
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
                url: "../ajax/estudiante.php?op=listar",
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

        $('#listaEstudiantes').removeClass('d-none')
        $('#prestamo-modal').modal('show')
    }

    function selectEstudiante(cedula) {
        var $nombreestudiante = document.getElementById('nombreestudiante')
        var $cedula = document.getElementById('cedula')
        var $feedback = document.getElementById('cedula-feedback').innerText = ''

        $.ajax({
            type: "POST",
            url: "../ajax/estudiante.php?op=mostrar",
            data: {
                cedula
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $nombreestudiante.value = resultado.nombre;
                $cedula.value = resultado.cedula;
                $('#prestamo-modal').modal('hide')
            }
        });
    }

    function selectLibro(cod_libro) {
        var $codigo = document.getElementById('codigo')
        var $nombre = document.getElementById('nombre')
        var $feedback = document.getElementById('codigo-feedback').innerText = ''

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=mostrar",
            data: {
                cod_libro
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $("#codigo").val(resultado.codigo);
                $("#nombre").val(resultado.titulo);
                $('#prestamo-modal').modal('hide')
            }
        });
    }
</script>

</body>

</html>