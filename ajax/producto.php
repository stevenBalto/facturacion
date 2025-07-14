<?php

require_once "../modelos/Producto.php";

$producto = new Producto();

$id = isset($_POST["id"]) ? $_POST["id"] : "";
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
$precio = isset($_POST["precio"]) ? $_POST["precio"] : "";
$idCategoria = isset($_POST["idCategoria"]) ? $_POST["idCategoria"] : "";

switch ($_GET["op"]) {
    case 'guardar':
        $rspta = $producto->insertar($id, $nombre, $precio, $idCategoria);
        if (intval($rspta) == 1) {
            echo "Producto Agregado";
        }
        if (intval($rspta) == 1062) {
            echo "Producto ya existe";
        }
        break;

    case 'editar':
        $rspta = $producto->editar($id, $nombre, $precio, $idCategoria);

        if (intval($rspta) == 1062) {
            echo "Producto ya existe";
        } else {
            echo "Producto editado";
        }
        break;

    case 'eliminar':
        $rspta = $producto->eliminar($id);
        echo $rspta ? "Producto eliminado" : "Producto no se pudo eliminar";
        break;

    case 'mostrar':
        $rspta = $producto->mostrar($id);
        echo json_encode($rspta);
        break;

    case 'seleccionar':
        $rspta = $producto->seleccionar($id);
        echo json_encode($rspta);
        break;

    case 'buscar_categoria':
        $rspta = $producto->buscar_categoria($_POST["idCategoria"]);
        if ($rspta && $rspta->num_rows > 0) {
            echo json_encode($rspta->fetch_assoc());
        } else {
            echo json_encode(null);
        }
        break;



    case 'listar':
        $rspta = $producto->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->id,
                "1" => $reg->nombre,
                "2" => $reg->precio,
                "3" => $reg->categoria,
                "4" => isset($_GET["select"]) 
                    ? '<button class="btn btn-primary" onclick="selectProducto(\'' . $reg->id . '\')"><i class="bx bx-search"></i>&nbsp;Seleccionar</button>' 
                    : '<button class="btn btn-warning" onclick="editar(\'' . $reg->id . '\')"><i class="bx bx-pencil"></i>&nbsp;Editar</button>
                       <button class="btn btn-danger ml-2" onclick="showModal(\'' . $reg->id . '\')"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

   case 'listar_categorias':
    $rspta = $producto->listar_categorias();
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        $data[] = array(
            "0" => $reg->id,
            "1" => $reg->nombre,
            "2" => '<button class="btn btn-primary" onclick="selectCategoria(\'' . $reg->id . '\')"><i class="bx bx-search"></i>&nbsp;Seleccionar</button>'
        );
    }

    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    );
    echo json_encode($results);
    break;

}
