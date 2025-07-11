<?php
$title = 'Libro';
ob_start();
?>

<style>
    #listadoregistros {
        display: none;
    }
</style>

<!-- Libro Modal -->
<div class="modal" id="libro-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2 id="buscar-autor-title" class="d-none">Buscar Autor</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="modal-body">
                <h4 id="delete-message" style="display:none"></h4>
                <div class="row table-responsive pl-3 d-none" id="listaAutores">
                    <table id="tbllistadoAutores" class="w-100 table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
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
<!-- Libro Modal End -->

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3 d-none" id="top-form">
    <div class="row my-1">
        <div class="col-sm-3">
            <label for="cod_libro">Código Libro:</label>
            <input class="form-control" type="text" id="cod_libro" name="cod_libro">
        </div>
        <div class="col-sm-3">
            <label for="titulo">Título:</label>
            <input class="form-control" type="text" id="titulo" name="titulo">
        </div>
        <div class="col-sm-3">
            <label for="genero">Genero:</label>
            <input class="form-control" type="text" id="genero" name="genero">
        </div>
        <input type="hidden" id="nuevo">
    </div>
    <div class="row">
        <div class="col-sm-3 my-1">
            <label for="cod_autor">Código Autor:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control" type="text" id="cod_autor" name="cod_autor" onblur="buscarAutor()">
                    <small class="text-danger" id="cod-autor-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-primary" onclick="listarAutores()" title="Buscar">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3 my-1">
            <label for="autor">Nombre Autor:</label>
            <input readonly class="form-control" type="text" id="autor" name="autor" readonly>
        </div>
        <div class="col-sm-12 my-2">
            <button type="button" class="col-sm-2 mr-1 btn btn-success" id="Guardar" onclick="guardar()" disabled=""><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" onclick="cancelar()"><i class="fa fa-times"></i> Cancelar</button>
        </div>
    </div>
</div>

<div class="mb-3 card p-3" id="listadoregistros">
    <div class="row table-responsive pl-3" id="listaLibros">
        <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>
        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
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
</div>

<?php
$content = ob_get_clean();
include './includes/layout.php';
?>

<script>
    listar()

    function habilitar_botones() {
        document.getElementById("Cancelar").disabled = false;
        document.getElementById("Guardar").disabled = false;
    }

    function desabilitar_botones() {
        document.getElementById("Cancelar").disabled = true;
        document.getElementById("Guardar").disabled = true;
    }

    function showModal(codigo) {
        $("#listaAutores").addClass("d-none");
        $("#btn-eliminar").removeClass('d-none');
        $('#buscar-autor-title').addClass('d-none')
        $('#modal-cod_libro').val(codigo)
        $('#delete-message').html("<h5>¿Seguro deseas eliminar el libro?</h5>").show()
        $('#libro-modal').modal('show')
    }

    function agregar() {
        $("#top-form").removeClass('d-none');
        document.getElementById("listadoregistros").style.display = "none";
        habilitar_botones()
        $("#cod_libro").val("").prop("readonly", false)
        $("#titulo").val("")
        $("#genero").val("")
        $("#cod_autor").val("")
        $("#nuevo").val(1)
    }

    function eliminar() {
        var cod_libro = $("#modal-cod_libro").val();

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=eliminar",
            data: {
                cod_libro
            },
            success: function(response) {
                Swal.fire(response);
            }
        })

        listar();
        $('#libro-modal').modal('hide')
    }

    function guardar() {
        var cod_libro = $("#cod_libro").val().trim();
        var titulo = $("#titulo").val().trim();
        var genero = $("#genero").val().trim();
        var cod_autor = $("#cod_autor").val().trim();
        var url = $('#nuevo').val() == 1 ? "../ajax/libro.php?op=guardar" : "../ajax/libro.php?op=editar";

        if (cod_libro == '' || titulo == '' || genero == '' || cod_autor == '') {
            Swal.fire('Faltan Datos');
        } else {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    cod_libro,
                    titulo,
                    genero,
                    cod_autor
                },
                success: function(response) {
                    Swal.fire(response);
                }
            });

            cancelar()
        }
    }

    function buscar() {
        var id = $("#id").val();

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=mostrar",
            data: {
                id: id
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                document.getElementById("id").value = resultado['id'];
                document.getElementById("nombre").value = resultado['nombre'];

            }
        });
    }

    function editar(codigo) {
        habilitar_botones();
        document.getElementById("listadoregistros").style.display = "none";
        $("#top-form").removeClass('d-none');
        $("#nuevo").val(0)
        $("#cod_libro").prop("readonly", true)

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=seleccionar",
            data: {
                cod_libro: codigo
            },
            success: function(response) {
                var resultado = JSON.parse(response);

                document.getElementById("autor").value = resultado['autor'];
                document.getElementById("cod_autor").value = resultado['cod_autor'];
                document.getElementById("cod_libro").value = resultado['codigo'];
                document.getElementById("titulo").value = resultado['titulo'];
                document.getElementById("genero").value = resultado['genero'];
            }
        });
    }

    function cancelar() {
        limpiar()
        desabilitar_botones();
        listar();
    }

    function limpiar() {
        document.getElementById("cod_libro").value = "";
        document.getElementById("titulo").value = "";
        document.getElementById("genero").value = "";
        document.getElementById("cod_autor").value = "";
        document.getElementById("autor").value = "";
    }

    //Función Listar
    function listar() {
        document.getElementById("listadoregistros").style.display = "block";
        document.getElementById("listaAutores").classList.add("d-none");
        document.getElementById("listaLibros").classList.remove("d-none");
        $("#top-form").addClass('d-none');

        tabla = $('#tbllistado').dataTable({
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
                url: "../ajax/libro.php?op=listar",
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
    }

    //Listar Autores
    function listarAutores() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-autor-title').removeClass('d-none')
        document.getElementById("listadoregistros").style.display = "none";
        document.getElementById("listaAutores").classList.remove("d-none");
        document.getElementById("listaLibros").classList.add("d-none");

        $('#tbllistadoAutores').dataTable({
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
                url: "../ajax/libro.php?op=listar_autores",
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

        $('#libro-modal').modal('show')
    }

    function selectAutor(codigo) {
        var $cod_autor = document.getElementById('cod_autor')
        var $autor = document.getElementById('autor')
        var $feedback = document.getElementById('cod-autor-feedback').innerText = ''

        //desabilitar_botones();
        $.ajax({
            type: "POST",
            url: "../ajax/autor.php?op=mostrar",
            data: {
                codigo
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                $autor.value = resultado.nombre;
                $cod_autor.value = resultado.codigo;
                document.getElementById('autor').value = resultado.nombre;
                document.getElementById('cod_autor').value = resultado.codigo;
                $('#libro-modal').modal('hide')
            }
        });
    }

    function buscarAutor() {
        var cod_autor = document.getElementById('cod_autor')
        var $autor = document.getElementById('autor')
        var $feedback = document.getElementById('cod-autor-feedback')

        $autor.value = ''

        $.ajax({
            type: "POST",
            url: "../ajax/libro.php?op=buscar_autor",
            data: {
                cod_autor: cod_autor.value
            },
            success: function(response) {
                var resultado = JSON.parse(response);

                if (resultado == null) {
                    $feedback.innerText = 'Autor no existe'
                } else {
                    $autor.value = resultado.nombre;
                    cod_autor.value = resultado.codigo;
                    $feedback.innerText = ''
                }
            }
        });
    }
</script>
</body>

</html>