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

    case 'mostrar_detalle':
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

    case 'listar_detalles':
        // Listar todos los detalles de todas las facturas
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

    case 'insertar':
        // Recibir el encabezado enviado por AJAX
        $encabezado = $_POST['encabezado'];
        $cedula = $encabezado['cedula'];
        $fecha = $encabezado['fecha'];
        $total = $encabezado['total'];
        $fechaformato = date('Y-m-d', strtotime($fecha));
        $factura->insertarEncabezado($cedula, $fechaformato, $total);
        $idfactura = $factura->obtenerId();

        // Recibir el detalle enviado por AJAX
        $detalle = json_decode($_POST['detalle'], true);
        foreach ($detalle as $dato) {
            $idProducto = $dato[0];
            $descripcion = $dato[1]; // opcional
            $precio = $dato[2];
            $cantidad = $dato[3];
            $subtotal = $precio * $cantidad;
            $rspta = $factura->insertarDetalle($idProducto, $descripcion, $precio, $cantidad, $subtotal, $idfactura['idfactura']);
            
            if (intval($rspta) == 1) {
                echo "Factura Insertada";
            } else {
                echo "Error al Insertar los datos";
            }
        }
        break;
}
?>
