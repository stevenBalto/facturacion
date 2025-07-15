<?php
$title = 'Categoría';
ob_start();
?>

<style>
    #listadoregistros {
        display: none;
    }
</style>

<div class="modal" id="cat-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="eliminar-body">
                ¿Seguro deseas eliminar la categoría?
            </div>

            <div class="modal-footer" id="eliminar-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                <button type="button" class="btn btn-danger" onclick="eliminar()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-categoria-id">
        </div>
    </div>
</div>

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
    listar();

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
    
    function agregar() {
        
        $("#top-form").removeClass('d-none');
    
        document.getElementById("listadoregistros").style.display = "none";
        habilitar_botones()
        $("#id").val("")
        $("#nombre").val("")
        $("#nombre").focus()
      
        $("#nuevo").val(1)
    }
    function showModal(id) {
           $('#modal-categoria-id').val(id)
            $('#cat-modal').modal('show')
    }

    function eliminar() {
       
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
        $('#cat-modal').modal('hide');
    }
    function guardar() {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var nuevo = $("#nuevo").val();
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

    function editar(idcategoria) {
        habilitar_botones();
        document.getElementById("listadoregistros").style.display = "none";
        $("#top-form").removeClass('d-none');
        $("#nuevo").val(0)

        $.ajax({
            type: "POST",
            url: "../ajax/categoria.php?op=mostrar",
            data: {
                id: idcategoria
            },
            success: function(response) {
                try {
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
    function cancelar() {
        document.getElementById("id").value = "";
        document.getElementById("nombre").value = "";
        desabilitar_botones();
        listar()
    }

    function listar() {
        document.getElementById("listadoregistros").style.display = "block";
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
                url: "../ajax/categoria.php?op=listar",
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
</script>
</body>

</html>