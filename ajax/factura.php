<?php 
// Importamos la clase Factura.php
require_once "../modelos/Factura.php";
require_once "../config/Conexion.php";

// Instanciamos la clase Factura
$factura = new Factura();

$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$idfactura = isset($_POST["idfactura"]) ? limpiarCadena($_POST["idfactura"]) : "";

switch ($_GET["op"]) {

    case 'listar_por_cliente':
        $rspta = $factura->listarPorCliente($cedula);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->id,
                    "1" => date('d/m/Y', strtotime($reg->fecha)),
                    "2" => "₡" . number_format($reg->total, 2)
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

    case 'listar':
        // Listar todas las facturas
        $sql = "SELECT f.id, f.cedulaCliente, c.nombre as cliente, f.fecha, f.total, f.estado 
                FROM factura f 
                INNER JOIN cliente c ON f.cedulaCliente = c.cedula 
                WHERE f.estado != 'temporal' 
                ORDER BY f.fecha DESC";
        $rspta = ejecutarConsulta($sql);
        $data = array();

        if($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => $reg->id,
                    "1" => $reg->cedulaCliente,
                    "2" => $reg->cliente,
                    "3" => date('d/m/Y', strtotime($reg->fecha)),
                    "4" => "₡" . number_format($reg->total, 2),
                    "5" => ucfirst($reg->estado),
                    "6" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-eye"></i></button> ' .
                           '<button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->id . ')"><i class="fa fa-trash"></i></button>'
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

    case 'eliminar':
        $id = isset($_POST["id"]) ? limpiarCadena($_POST["id"]) : "";
        
        // Primero eliminar los detalles de la factura
        $sql_detalle = "DELETE FROM detalle_factura WHERE idFactura = '$id'";
        $rspta_detalle = ejecutarConsulta($sql_detalle);
        
        if ($rspta_detalle) {
            // Luego eliminar la factura
            $sql_factura = "DELETE FROM factura WHERE id = '$id'";
            $rspta_factura = ejecutarConsulta($sql_factura);
            
            if ($rspta_factura) {
                echo json_encode(array("status" => "success", "message" => "Factura eliminada correctamente"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error al eliminar la factura"));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al eliminar los detalles de la factura"));
        }
        break;

    case 'mostrar':
        $id = isset($_POST["id"]) ? limpiarCadena($_POST["id"]) : "";
        $sql = "SELECT * FROM factura WHERE id = '$id'";
        $rspta = ejecutarConsulta($sql);
        
        if ($rspta && $rspta->num_rows > 0) {
            $factura = $rspta->fetch_object();
            echo json_encode(array(
                'id' => $factura->id,
                'cedula' => $factura->cedulaCliente,
                'fecha' => date('Y-m-d', strtotime($factura->fecha)),
                'total' => $factura->total
            ));
        } else {
            echo json_encode(array('error' => 'Factura no encontrada'));
        }
        break;

    case 'finalizar_factura':
        // Finalizar la factura cambiando el estado de 'temporal' a 'finalizada'
        $cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $fechaformato = date('Y-m-d', strtotime($fecha));
        
        $sql = "UPDATE factura SET estado = 'finalizada' WHERE cedulaCliente = '$cedula' AND fecha = '$fechaformato' AND estado = 'temporal'";
        $rspta = ejecutarConsulta($sql);
        
        if ($rspta) {
            echo json_encode(array("status" => "success", "message" => "Factura guardada correctamente"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al guardar la factura"));
        }
        break;

    case 'insertar':
        // Recibir el encabezado enviado por AJAX
        $encabezado = $_POST['encabezado'];
        $cedula = $encabezado['cedula'];
        $fecha = $encabezado['fecha'];
        $total = $encabezado['total'];
        $fechaformato = date('Y-m-d', strtotime($fecha));
        
        $rspta_encabezado = $factura->insertarEncabezado($cedula, $fechaformato, $total);
        
        if ($rspta_encabezado) {
            $idfactura = $factura->obtenerId();
            
            // Recibir el detalle enviado por AJAX
            $detalle = json_decode($_POST['detalle'], true);
            $errores = 0;
            
            foreach ($detalle as $dato) {
                $idProducto = $dato['idProducto'];
                $descripcion = $dato['nombreProducto'];
                $cantidad = $dato['cantidad'];
                $precio = $dato['precioUnitario'];
                $subtotal = $dato['subtotal'];
                
                $rspta = $factura->insertarDetalle($idProducto, $descripcion, $precio, $cantidad, $subtotal, $idfactura['idfactura']);
                
                if (!$rspta) {
                    $errores++;
                }
            }
            
            if ($errores == 0) {
                echo json_encode(array("status" => "success", "message" => "Factura guardada correctamente"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error al insertar algunos detalles"));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al crear la factura"));
        }
        break;
}
?>
