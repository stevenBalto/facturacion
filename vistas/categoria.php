<?php
$title = 'Categoría';
ob_start();
?>

<style>
    #listadoregistros {
        display: none;
    }
</style>

<!-- Categoría Modal -->
<div class="modal" id="cat-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="eliminar-body">
                ¿Seguro deseas eliminar la categoría?
            </div>

            <!-- Modal footer -->
            <div class="modal-footer" id="eliminar-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                <button type="button" class="btn btn-danger" onclick="eliminar()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-categoria-id">
        </div>
    </div>
</div>
<!-- Categoría Modal End -->

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3 d-none" id="top-form">
    <div class="row">
        <div class="col-sm-1">
            <label for="id">ID:</label>
            <input readonly class="form-control" type="text" id="id" name="id">
        </div>
        <div class="col-sm-4">
            <label for="nombre">Nombre <span class="text-danger">*</span>:</label>
            <input class="form-control" type="text" id="nombre" name="nombre" maxlength="100" required>
            <small class="form-text text-muted">Ingrese el nombre de la categoría</small>
        </div>
        <input type="hidden" id="nuevo">
    </div>
    <div class="row p-3">
        <button type="button" class="col-sm-2 mr-1 btn btn-success" id="Guardar" onclick="guardar()" disabled><i class="fa fa-save"></i> Guardar</button>
        <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" onclick="cancelar()"><i class="fa fa-times"></i> Cancelar</button>
    </div>
</div>

<div class="mb-3 card p-3" id="listadoregistros">
    <div class="row table-responsive pl-3">
        <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>

        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Id</th>
                <th>Nombre</th>
                <th>Opciones</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th>Id</th>
                <th>Nombre</th>
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
    //Muestra el listao de registros oculta las cajas de texto
    listar();

    // Validación en tiempo real
    $("#nombre").on('input', function() {
        var nombre = $(this).val().trim();
        if(nombre.length > 0) {
            $("#Guardar").prop('disabled', false);
        } else {
            $("#Guardar").prop('disabled', true);
        }
    });

    function habilitar_botones() {
        document.getElementById("Cancelar").disabled = false;
        document.getElementById("Guardar").disabled = false;
    }

    function desabilitar_botones() {
        document.getElementById("Cancelar").disabled = true;
        document.getElementById("Guardar").disabled = true;
    }
    
//Se invoca cuando se preciona el boton de Agregar
    function agregar() {
        //#top-form: Es el id del div que contiene la ventana del formulario con las cajas de texto
        //Muestra las cajas de texto
        $("#top-form").removeClass('d-none');
        //#listadoregistros: Es el id del div que contiene el datatable con el listado 
        //Oculta el listado de registros
        document.getElementById("listadoregistros").style.display = "none";
        //Habilita los botones Cancelar y Guardar
        habilitar_botones()
        $("#id").val("")
        $("#nombre").val("")
        //Enfocar el campo nombre
        $("#nombre").focus()
        //Nuevo es un control oculto el valor 1 que se le asigna (hidden)
        //es para indicar que se esta agregando un registro nuevo
        $("#nuevo").val(1)
    }
//Se invoca cuando se preciona el boton eliminar del listado
    function showModal(id) {
        //Guarda en un campo oculto el id del registro a Eliminar
           $('#modal-categoria-id').val(id)
        //Muestra la ventana de Desea Eliminar el Registro?
            $('#cat-modal').modal('show')
    }
/*Se invoca cuando se preciona el boton eliminar
    function showModal(id) {
        if (id == 0) {
            $('#modal-title').hide()
            $('#eliminar-body').hide()
            $('#eliminar-footer').hide()
            $('#modal-title-eliminar').hide()
        } else {
            $('#modal-categoria-id').val(id)
            $('#modal-title').show()
            $('#eliminar-body').show()
            $('#eliminar-footer').show()
            $('#modal-title-eliminar').show()
        }

        $('#cat-modal').modal('show')
    }*/
//Se invoca cuando se preciona el boton Eliminar del mensaje Desea Eliminar el Registro?
    function eliminar() {
        //Obtenos el id de la categoria
        //El #modal-categoria-id es un control oculto con el id de la categoria (hidden)
        id = $('#modal-categoria-id').val()

        $.ajax({
            type: "POST",
            url: "../ajax/categoria.php?op=eliminar",
            data: {
                id: id
            },
            success: function(response) {
                if(response.trim()) {
                    Swal.fire({
                        title: 'Resultado',
                        text: response,
                        icon: response.includes('correctamente') ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    });
                }
                //Actualiza el listado
                listar();
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar la categoría',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        //Oculta el mensaje de Desea Eliminar el Registro?
        $('#cat-modal').modal('hide');
    }
//Se invoca cuando se preciona el boton de Guardar en las cajas de texto
    function guardar() {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        //Obtenemos el valor del control oculto nuevo
        var nuevo = $("#nuevo").val();
        //Si nuevo es 1 Guarda si es 0 Edita
        var url = nuevo == 1 ? "../ajax/categoria.php?op=guardar" : "../ajax/categoria.php?op=editar";

        if (nombre.trim() == '') {
            Swal.fire({
                title: 'Error',
                text: 'El nombre de la categoría es requerido',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        } else {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id,
                    nombre
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Resultado',
                        text: response,
                        icon: response.includes('correctamente') ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    });
                    
                    if(response.includes('correctamente')) {
                        //Limpiar el formulario y mostrar el listado
                        cancelar();
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al procesar la solicitud',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    }
/*
    function buscar() {
        var id = $("#id").val();

        $.ajax({
            type: "POST",
            url: "../ajax/categoria.php?op=mostrar",
            data: {
                id: id
            },
            success: function(response) {
                alert(response);
                var resultado = JSON.parse(response);
                document.getElementById("id").value = resultado['id'];
                document.getElementById("nombre").value = resultado['nombre'];

            }
        });
    }
*/
//Se invoca cuando se preciona el boton Editar del listado
    function editar(idcategoria) {
        habilitar_botones();
        //Oculta el listado de registros
        document.getElementById("listadoregistros").style.display = "none";
        //Muestra las cajas de texto
        $("#top-form").removeClass('d-none');
        //La caja de texto oculta le asigna un valor de 0
        $("#nuevo").val(0)

        $.ajax({
            type: "POST",
            url: "../ajax/categoria.php?op=mostrar",
            data: {
                id: idcategoria
            },
            success: function(response) {
                try {
                    //Muestra los valores en la caja de texto
                    var resultado = JSON.parse(response);
                    if(resultado.error) {
                        Swal.fire({
                            title: 'Error',
                            text: resultado.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        cancelar();
                    } else {
                        document.getElementById("id").value = resultado['id'];
                        document.getElementById("nombre").value = resultado['nombre'];
                    }
                } catch(e) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al cargar los datos de la categoría',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    cancelar();
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al conectar con el servidor',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                cancelar();
            }
        });
    }
//Se invoca cuando se preciona el boton de cancelar en las cajas de texto
    function cancelar() {
        //Limpia las cajas de texto
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        //Desabilita los botones de Guardar y Cancelar
        desabilitar_botones();
        //Oculta las cajas de texto y muestra el listado
        listar()
    }

    //Se invoca cada vez que se quiere mostrar el listado o actualizar los registros que se muestran
    function listar() {
        //Muestra el listado de registros
        document.getElementById("listadoregistros").style.display = "block";
        //Oculta las cajas de texto
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
                url: "../ajax/categoria.php?op=listar",
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
</script>
</body>

</html>