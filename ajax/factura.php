<?php 
// Importamos la clase Factura.php
require_once "../modelos/Factura.php";

// Instanciamos la clase Factura
$factura = new Factura();

// Recibir el encabezado enviado por AJAX
$encabezado = $_POST['encabezado'];
$cedula = $encabezado['cedula'];
$fecha = $encabezado['fecha'];
$total = $encabezado['total'];
$fechaformato = date('Y-m-d', strtotime($fecha));
$factura->insertarencabezado($cedula, $fechaformato, $total);
$idfactura = $factura->Obtenerid();

// Recibir el detalle enviado por AJAX
$detalle = json_decode($_POST['detalle'], true);
foreach ($detalle as $dato) {
    $idProducto = $dato[0];
    $descripcion = $dato[1]; // opcional
    $precio = $dato[2];
    $cantidad = $dato[3];
    $rspta = $factura->insertardetalle($idProducto, $precio, $cantidad, $idfactura['id']);
    
    if (intval($rspta) == 1) {
        echo "Factura Insertada";
    } else {
        echo "Error al Insertar los datos";
    }
}
?>
