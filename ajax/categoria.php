<?php

require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$id = isset($_POST["id"]) ? limpiarCadena($_POST["id"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";

switch ($_GET["op"]) {
	case 'guardar':
		if(empty($nombre)) {
			echo "El nombre de la categoría es requerido";
		} else {
			$rspta = $categoria->insertar($nombre);
			if ($rspta) {
				echo "Categoría registrada correctamente";
			} else {
				echo "No se pudo registrar la categoría";
			}
		}
		break;

	case 'editar':
		if(empty($nombre)) {
			echo "El nombre de la categoría es requerido";
		} else {
			$rspta = $categoria->editar($id, $nombre);
			echo $rspta ? "Categoría actualizada correctamente" : "No se pudo actualizar la categoría";
		}
		break;

	case 'eliminar':
		$rspta = $categoria->eliminar($id);
		echo $rspta ? "Categoría eliminada correctamente" : "No se pudo eliminar la categoría";
		break;

	case 'mostrar':
    $id = $_POST["id"];
    $rspta = $categoria->mostrar($id);
    echo json_encode($rspta);
    break;

	case 'listar':
		$rspta = $categoria->listar();
		
		$data = array();

		if($rspta) {
			while ($reg = $rspta->fetch_object()) {
				$data[] = array(
					"0" => $reg->id,
					"1" => $reg->nombre,
					"2" => '<button class="btn btn-warning btn-sm" onclick="editar(\'' . $reg->id . '\')"><i class="bx bx-pencil"></i>&nbsp;Editar</button> <button class="btn btn-danger btn-sm ml-1" onclick="showModal(\'' . $reg->id . '\')"><i class="bx bx-trash"></i>&nbsp;Eliminar</button>'
				);
			}
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
