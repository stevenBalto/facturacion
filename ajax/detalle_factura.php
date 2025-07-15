<?php 
require_once "../modelos/Factura.php";
require_once "../config/Conexion.php";

$factura = new Factura();

$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$idDetalle = isset($_POST["idDetalle"]) ? limpiarCadena($_POST["idDetalle"]) : "";

switch ($_GET["op"]) {
    case 'listar_todos_detalles':
        $sql = "SELECT d.*, f.fecha, f.cedulaCliente, c.nombre as cliente, p.nombre as nombreProducto 
                FROM detalle_factura d 
                INNER JOIN factura f ON d.idFactura = f.id 
                INNER JOIN cliente c ON f.cedulaCliente = c.cedula 
                INNER JOIN producto p ON d.idProducto = p.id
                ORDER BY f.fecha DESC, d.idFactura DESC";
        
        $rspta = ejecutarConsulta($sql);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->cedulaCliente,              
                    "1" => date('Y-m-d', strtotime($reg->fecha)), 
                    "2" => $reg->idProducto,                  
                    "3" => $reg->nombreProducto,              
                    "4" => $reg->cantidad,                  
                    "5" => "₡" . number_format($reg->precioUnitario, 2), 
                    "6" => "₡" . number_format($reg->subtotal, 2),      
                    "7" => '<button class="btn btn-warning btn-sm" onclick="eliminarDetalle(' . $reg->id . ')"><i class="fa fa-trash"></i></button>' // Acciones para BD
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

    case 'agregar_detalle':
        $idProducto = isset($_POST["idProducto"]) ? limpiarCadena($_POST["idProducto"]) : "";
        $cantidad = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";
        $precioUnitario = isset($_POST["precioUnitario"]) ? limpiarCadena($_POST["precioUnitario"]) : "";
        $cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        
        $subtotal = $cantidad * $precioUnitario;
        $fechaformato = date('Y-m-d', strtotime($fecha));
        
        $sql_verificar = "SELECT id FROM factura WHERE cedulaCliente = '$cedula' AND fecha = '$fechaformato' AND estado = 'temporal'";
        $resultado = ejecutarConsulta($sql_verificar);
        
        if ($resultado && $resultado->num_rows > 0) {
            $factura_temp = $resultado->fetch_object();
            $idfactura = $factura_temp->id;
        } else {
            $sql_factura = "INSERT INTO factura (cedulaCliente, fecha, total, estado) VALUES ('$cedula', '$fechaformato', 0, 'temporal')";
            ejecutarConsulta($sql_factura);
            $idfactura = ejecutarConsulta("SELECT LAST_INSERT_ID() as id")->fetch_object()->id;
        }
        
        $rspta = $factura->insertarDetalle($idProducto, '', $precioUnitario, $cantidad, $subtotal, $idfactura);
        
        $sql_total = "UPDATE factura SET total = (SELECT SUM(subtotal) FROM detalle_factura WHERE idFactura = $idfactura) WHERE id = $idfactura";
        ejecutarConsulta($sql_total);
        
        if ($rspta) {
            echo json_encode(array("status" => "success", "message" => "Producto agregado correctamente"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al agregar el producto"));
        }
        break;

    case 'listar_detalle_temporal':
        $cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $fechaformato = date('Y-m-d', strtotime($fecha));
        
        $sql = "SELECT d.*, p.nombre as nombreProducto 
                FROM detalle_factura d 
                INNER JOIN factura f ON d.idFactura = f.id 
                INNER JOIN producto p ON d.idProducto = p.id
                WHERE f.cedulaCliente = '$cedula' AND f.fecha = '$fechaformato' AND f.estado = 'temporal'
                ORDER BY d.id DESC";
        
        $rspta = ejecutarConsulta($sql);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->idProducto,
                    "1" => $reg->nombreProducto,
                    "2" => $reg->cantidad,
                    "3" => "₡" . number_format($reg->precioUnitario, 2),
                    "4" => "₡" . number_format($reg->subtotal, 2),
                    "5" => '<button class="btn btn-danger btn-sm" onclick="eliminarDetalle(' . $reg->id . ')"><i class="fa fa-trash"></i></button>'
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

    case 'eliminar_detalle':
        $idDetalle = isset($_POST["idDetalle"]) ? limpiarCadena($_POST["idDetalle"]) : "";
        
        if (empty($idDetalle)) {
            echo json_encode(array("status" => "error", "message" => "ID de detalle no proporcionado"));
            break;
        }
        
        $sql = "DELETE FROM detalle_factura WHERE id = $idDetalle";
        $rspta = ejecutarConsulta($sql);
        
        if ($rspta) {
            $verificar = ejecutarConsulta("SELECT COUNT(*) as existe FROM detalle_factura WHERE id = $idDetalle");
            $resultado = $verificar->fetch_object();
            
            if ($resultado->existe == 0) {
                echo json_encode(array("status" => "success", "message" => "Producto eliminado correctamente"));
            } else {
                echo json_encode(array("status" => "error", "message" => "El producto no pudo ser eliminado"));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error en la consulta SQL"));
        }
        break;

    case 'listar_detalles':
        $sql = "SELECT d.*, f.fecha, f.cedulaCliente, c.nombre as cliente, p.nombre as nombreProducto 
                FROM detalle_factura d 
                INNER JOIN factura f ON d.idFactura = f.id 
                INNER JOIN cliente c ON f.cedulaCliente = c.cedula 
                INNER JOIN producto p ON d.idProducto = p.id
                ORDER BY f.fecha DESC, d.idFactura DESC";
        
        $rspta = ejecutarConsulta($sql);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->idFactura,
                    "1" => date('d/m/Y', strtotime($reg->fecha)),
                    "2" => $reg->cliente,
                    "3" => $reg->idProducto,
                    "4" => $reg->nombreProducto,
                    "5" => $reg->cantidad,
                    "6" => "₡" . number_format($reg->precioUnitario, 2),
                    "7" => "₡" . number_format($reg->subtotal, 2)
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

    case 'mostrar_detalle':
        $idfactura = isset($_POST["idfactura"]) ? limpiarCadena($_POST["idfactura"]) : "";
        $rspta = $factura->obtenerDetalle($idfactura);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    $reg->idProducto,
                    $reg->nombreProducto,
                    $reg->cantidad,
                    $reg->precioUnitario,
                    $reg->subtotal
                );
            }
        }

        echo json_encode($data);
        break;
}
?>
