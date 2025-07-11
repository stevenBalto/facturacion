<?php

require_once "../modelos/Estudiante.php";

$estudiante = new Estudiante();

$cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : "";

switch ($_GET["op"]) {
    case 'listar':
        $rspta = $estudiante->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->cedula,
                "1" => $reg->nombre,
                "2" => $reg->telefono,
                "3" => '<button class="btn btn-primary" onclick="selectEstudiante(\'' . $reg->cedula . '\')"><i class="bx bx-search"></i>&nbsp;Seleccionar</button>'
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

    case 'buscar':
        $rspta = $estudiante->buscar($cedula);

        // Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'mostrar':
        $rspta = $estudiante->mostrar($cedula);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
}
