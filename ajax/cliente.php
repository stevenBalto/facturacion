<?php

require_once "../modelos/Cliente.php";

$cliente = new Cliente();

$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

switch ($_GET["op"]) {
    case 'guardar':
        if(empty($cedula) || empty($nombre) || empty($telefono) || empty($direccion)) {
            echo "Todos los campos son requeridos (cédula, nombre, teléfono y dirección)";
        } else {
            $rspta = $cliente->insertar($cedula, $nombre, $telefono, $direccion);
            if ($rspta) {
                echo "Cliente registrado correctamente";
            } else {
                echo "No se pudo registrar el cliente";
            }
        }
        break;

    case 'editar':
        if(empty($cedula) || empty($nombre) || empty($telefono) || empty($direccion)) {
            echo "Todos los campos son requeridos (cédula, nombre, teléfono y dirección)";
        } else {
            $rspta = $cliente->editar($cedula, $nombre, $telefono, $direccion);
            echo $rspta ? "Cliente actualizado correctamente" : "No se pudo actualizar el cliente";
        }
        break;

    case 'eliminar':
        $rspta = $cliente->eliminar($cedula);
        echo $rspta ? "Cliente eliminado correctamente" : "No se pudo eliminar el cliente";
        break;

    case 'mostrar':
        $rspta = $cliente->mostrar($cedula);
        if($rspta) {
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
        } else {
            echo json_encode(array("error" => "No se encontró el cliente"));
        }
        break;

    case 'listar':
        $rspta = $cliente->listar();
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->cedula,
                    "1" => $reg->nombre,
                    "2" => $reg->telefono,
                    "3" => $reg->direccion,
                    "4" => '<button class="btn btn-warning btn-sm" onclick="editar(\'' . $reg->cedula . '\')"><i class="bx bx-pencil"></i>&nbsp;Editar</button> <button class="btn btn-danger btn-sm ml-1" onclick="showModal(\'' . $reg->cedula . '\')"><i class="bx bx-trash"></i>&nbsp;Eliminar</button> <button class="btn btn-info btn-sm ml-1" onclick="verFacturas(\'' . $reg->cedula . '\', \'' . $reg->nombre . '\')"><i class="bx bx-receipt"></i>&nbsp;Facturas</button>'
                );
            }
        }
        
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'buscar':
        $rspta = $cliente->buscar($cedula);

        // Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
}