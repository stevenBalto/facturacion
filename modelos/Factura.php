<?php 
// Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Factura
{
    // Implementamos nuestro constructor
    public function __construct()
    {

    }

    // Implementamos un método para insertar registros del encabezado
    public function insertarEncabezado($cedula, $fecha, $total)
    {
        try {
            $sql = "INSERT INTO factura (cedulaCliente, fecha, total)
                    VALUES ('$cedula', '$fecha', '$total')";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

    // Implementamos un método para insertar registros del datatable
    public function insertarDetalle($idproducto, $descripcion, $precio, $cantidad, $subtotal, $idfactura)
    {
        try {
            $sql = "INSERT INTO detalle_factura (idProducto, cantidad, precioUnitario, subtotal, idFactura)
                    VALUES ('$idproducto', '$cantidad', '$precio', '$subtotal', '$idfactura')";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

    // Implementamos un método para obtener el último ID insertado
    public function obtenerId()
    {
        $sql = "SELECT max(id) as idfactura FROM factura";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Implementamos un método para listar facturas por cliente
    public function listarPorCliente($cedula)
    {
        try {
            $cedula = limpiarCadena($cedula);
            $sql = "SELECT f.id, f.cedulaCliente, c.nombre as cliente, f.fecha, f.total 
                    FROM factura f 
                    INNER JOIN cliente c ON f.cedulaCliente = c.cedula 
                    WHERE f.cedulaCliente = '$cedula' 
                    ORDER BY f.fecha DESC";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Implementamos un método para obtener detalles de una factura
    public function obtenerDetalle($idfactura)
    {
        try {
            $idfactura = limpiarCadena($idfactura);
            $sql = "SELECT d.*, p.nombre as nombreProducto, f.fecha, f.total, c.nombre as cliente 
                    FROM detalle_factura d 
                    INNER JOIN factura f ON d.idFactura = f.id 
                    INNER JOIN cliente c ON f.cedulaCliente = c.cedula 
                    INNER JOIN producto p ON d.idProducto = p.id
                    WHERE d.idFactura = '$idfactura'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
