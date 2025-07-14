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
            $sql = "INSERT INTO encabezadofactura (cedula, fecha, total)
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
            $sql = "INSERT INTO detallefactura (idproducto, descripcion, precio, cantidad, subtotal, idfactura)
                    VALUES ('$idproducto', '$descripcion', '$precio', '$cantidad', '$subtotal', '$idfactura')";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

    // Implementamos un método para obtener el último ID insertado
    public function obtenerId()
    {
        $sql = "SELECT max(id) as idfactura FROM encabezadofactura";
        return ejecutarConsultaSimpleFila($sql);
    }
}
?>
