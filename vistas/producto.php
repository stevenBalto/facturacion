<?php
$title = 'Producto';
ob_start();
?>

<style>
    #listadoregistros {
        display: none;
    }
</style>

<!-- Producto Modal -->
<div class="modal" id="producto-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2 id="buscar-categoria-title" class="d-none">Buscar Categoría</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="modal-body">
                <h4 id="delete-message" style="display:none"></h4>
                <div class="row table-responsive pl-3 d-none" id="listaCategorias">
                    <table id="tbllistadoCategorias" class="w-100 table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th>ID</th>
                            <th>Nombre</th>
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

            <input type="hidden" id="modal-id">
        </div>
    </div>
</div>
<!-- Producto Modal End -->

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3 d-none" id="top-form">
    <div class="row my-1">
        <div class="col-sm-3">
            <label for="id">Código Producto:</label>
            <input class="form-control" type="text" id="id" name="id">
        </div>
        <div class="col-sm-3">
            <label for="nombre">Nombre:</label>
            <input class="form-control" type="text" id="nombre" name="nombre">
        </div>
        <div class="col-sm-3">
            <label for="precio">Precio:</label>
            <input class="form-control" type="text" id="precio" name="precio">
        </div>
        <input type="hidden" id="nuevo">
    </div>
    <div class="row">
        <div class="col-sm-3 my-1">
            <label for="idCategoria">Código Categoría:</label>
            <div class="d-flex flex-row justify-content-between">
                <div class="pr-md-2">
                    <input class="form-control" type="text" id="idCategoria" name="idCategoria" onblur="buscarCategoria()">
                    <small class="text-danger" id="cod-categoria-feedback"></small>
                </div>

                <div class="">
                    <button class="float-right btn btn-primary" onclick="listarCategorias()" title="Buscar">Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3 my-1">
            <label for="categoria">Nombre Categoría:</label>
            <input readonly class="form-control" type="text" id="categoria" name="categoria" readonly>
        </div>
        <div class="col-sm-12 my-2">
            <button type="button" class="col-sm-2 mr-1 btn btn-success" id="Guardar" onclick="guardar()" disabled=""><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" onclick="cancelar()"><i class="fa fa-times"></i> Cancelar</button>
        </div>
    </div>
</div>

<div class="mb-3 card p-3" id="listadoregistros">
    <div class="row table-responsive pl-3" id="listaProductos">
        <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>
        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Opciones</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
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

    function showModal(id) {
        $("#listaCategorias").addClass("d-none");
        $("#btn-eliminar").removeClass('d-none');
        $('#buscar-categoria-title').addClass('d-none')
        $('#modal-id').val(id)
        $('#delete-message').html("<h5>¿Seguro deseas eliminar el producto?</h5>").show()
        $('#producto-modal').modal('show')
    }

    function agregar() {
        $("#top-form").removeClass('d-none');
        document.getElementById("listadoregistros").style.display = "none";
        habilitar_botones()
        $("#id").val("").prop("readonly", false)
        $("#nombre").val("")
        $("#precio").val("")
        $("#idCategoria").val("")
        $("#nuevo").val(1)
    }

    function eliminar() {
        var id = $("#modal-id").val();

        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=eliminar",
            data: {
                id
            },
            success: function(response) {
                Swal.fire(response);
            }
        })

        listar();
        $('#producto-modal').modal('hide')
    }

    function guardar() {
        var id = $("#id").val().trim();
        var nombre = $("#nombre").val().trim();
        var precio = $("#precio").val().trim();
        var idCategoria = $("#idCategoria").val().trim();
        var url = $('#nuevo').val() == 1 ? "../ajax/producto.php?op=guardar" : "../ajax/producto.php?op=editar";

        if (id == '' || nombre == '' || precio == '' || idCategoria == '') {
            Swal.fire('Faltan Datos');
        } else {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id,
                    nombre,
                    precio,
                    idCategoria
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
            url: "../ajax/producto.php?op=mostrar",
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

    function editar(id) {
        habilitar_botones();
        document.getElementById("listadoregistros").style.display = "none";
        $("#top-form").removeClass('d-none');
        $("#nuevo").val(0)
        $("#id").prop("readonly", true)

        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=seleccionar",
            data: {
                id: id
            },
            success: function(response) {
                var resultado = JSON.parse(response);

                document.getElementById("categoria").value = resultado['categoria'];
                document.getElementById("idCategoria").value = resultado['idCategoria'];
                document.getElementById("id").value = resultado['id'];
                document.getElementById("nombre").value = resultado['nombre'];
                document.getElementById("precio").value = resultado['precio'];
            }
        });
    }

    function cancelar() {
        limpiar()
        desabilitar_botones();
        listar();
    }

    function limpiar() {
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("precio").value = "";
        document.getElementById("idCategoria").value = "";
        document.getElementById("categoria").value = "";
    }

    function listar() {
        document.getElementById("listadoregistros").style.display = "block";
        document.getElementById("listaCategorias").classList.add("d-none");
        document.getElementById("listaProductos").classList.remove("d-none");
        $("#top-form").addClass('d-none');

        tabla = $('#tbllistado').dataTable({
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
                url: "../ajax/producto.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,
            "order": [
                [0, "asc"]
            ]
        }).DataTable();
    }

    function listarCategorias() {
        $("#btn-eliminar").addClass('d-none');
        $('#delete-message').hide()
        $('#buscar-categoria-title').removeClass('d-none')
        document.getElementById("listadoregistros").style.display = "none";
        document.getElementById("listaCategorias").classList.remove("d-none");
        document.getElementById("listaProductos").classList.add("d-none");

        $('#tbllistadoCategorias').dataTable({
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
                url: "../ajax/producto.php?op=listar_categorias",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,
            "order": [
                [0, "asc"]
            ]
        }).DataTable();

        $('#producto-modal').modal('show')
    }

    function selectCategoria(id) {
    $.ajax({
        type: "POST",
        url: "../ajax/producto.php?op=buscar_categoria", // 👈 CORREGIDO
        data: { idCategoria: id }, // 👈 el parámetro debe llamarse idCategoria
        success: function(response) {
            try {
                const resultado = JSON.parse(response);
                if (resultado && resultado.nombre) {
                    document.getElementById("idCategoria").value = resultado.id;
                    document.getElementById("categoria").value = resultado.nombre;
                    $('#producto-modal').modal('hide');
                } else {
                    document.getElementById("cod-categoria-feedback").innerText = "Nombre no encontrado";
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        }
    });
}




    function buscarCategoria() {
        var idCategoria = document.getElementById('idCategoria')
        var $categoria = document.getElementById('categoria')
        var $feedback = document.getElementById('cod-categoria-feedback')

        $categoria.value = ''

        $.ajax({
            type: "POST",
            url: "../ajax/producto.php?op=buscar_categoria",
            data: {
                idCategoria: idCategoria.value
            },
            success: function(response) {
                var resultado = JSON.parse(response);

                if (resultado == null) {
                    $feedback.innerText = 'Categoría no existe'
                } else {
                    $categoria.value = resultado.nombre;
                    idCategoria.value = resultado.id;
                    $feedback.innerText = ''
                }
            }
        });
    }
</script>
</body>
</html>
