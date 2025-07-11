<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Prestamo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
      //Implementamos un método para insertar registros del encabezado
    public function insertarencabezado($cedula, $nombre, $fecha)
    {
    try 
        {
        $sql="INSERT INTO encabezadoprestamo (cedula, nombre, fecha)
        VALUES ('$cedula','$nombre','$fecha')";
        return ejecutarConsulta($sql);
        } 
    catch (Exception $e)
         {
        return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

    //Implementamos un método para insertar registros del datatable
	public function insertardetalle($codigo, $nombre, $fecha, $idprestamo)
    {
    try 
        {
        $sql="INSERT INTO detalleprestamo (codigo, nombre, fecha, idprestamo)
        VALUES ('$codigo','$nombre','$fecha', '$idprestamo')";
        return ejecutarConsulta($sql);
        } 
    catch (Exception $e)
         {
        return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

   //Implementar un método para mostrar los datos de un registro a modificar
	public function Obtenerid()
	{
		$sql="SELECT max(idprestamo) as idprestamo FROM encabezadoprestamo";
		return ejecutarConsultaSimpleFila($sql);
	}

}   
?>