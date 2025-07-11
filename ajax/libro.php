<?php

require_once "../modelos/Libro.php";

$libro = new Libro();

$cod_libro = isset($_POST["cod_libro"]) ? $_POST["cod_libro"] : "";
$titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
$genero = isset($_POST["genero"]) ? $_POST["genero"] : "";
$cod_autor = isset($_POST["cod_autor"]) ? $_POST["cod_autor"] : "";

switch ($_GET["op"]) {
    case 'guardar':
        $rspta = $libro->insertar($cod_libro, $titulo, $genero, $cod_autor);
        if (intval($rspta) == 1) {
            echo "Libro Agregado";
        }
        if (intval($rspta) == 1062) {
            echo "Libro ya existe";
        }
        break;

    case 'editar':
        $rspta = $libro->editar($cod_libro, $titulo, $genero, $cod_autor);

        if (intval($rspta) == 1062) {
            echo "Libro ya existe";
        } else {
            echo "Libro editado";
        }

        break;

    case 'eliminar':
        $rspta = $libro->eliminar($cod_libro);
        echo $rspta ? "Libro eliminado" : "Libro no se pudo eliminar";

        break;

    case 'mostrar':
        $rspta = $libro->mostrar($cod_libro);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'seleccionar':
        $rspta = $libro->seleccionar($cod_libro);

        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $libro->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->codigo,
                "1" => $reg->titulo,
                "2" => $reg->genero,
                "3" => $reg->autor,
                "4" => isset($_GET["select"]) ? '<button class="btn btn-primary" onclick="selectLibro(\'' . $reg->codigo . '\')"><i class="bx bx-search"></i>&nbsp;Seleccionar</button>' : '<button class="btn btn-warning" onclick="editar(\'' . $reg->codigo . '\')"><i class="bx bx-pencil"></i>&nbsp;Editar</button><button class="btn btn-danger ml-2" onclick="showModal(\'' . $reg->codigo . '\')"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);

        break;

    case 'buscar_autor':
        $rspta = $libro->buscar_autor($cod_autor);
        //Codificar el resultado utilizando json
        echo json_encode($rspta->fetch_assoc());
        break;

    case 'listar_autores':
        $rspta = $libro->listar_autores();

        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->codigo,
                "1" => $reg->nombre,
                "2" => $reg->nacionalidad,
                "3" => '<button class="btn btn-primary" onclick="selectAutor(\'' . $reg->codigo . '\')"><i class="bx bx-search"></i>&nbsp;Seleccionar</button>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
