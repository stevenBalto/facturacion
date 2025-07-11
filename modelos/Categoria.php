<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre)
	{
		try {
			$nombre = limpiarCadena($nombre);
			$sql="INSERT INTO categoria (nombre) VALUES ('$nombre')";
			return ejecutarConsulta($sql);
		} catch (Exception $e) {
			return false;
		}
	}

	//Implementamos un método para editar registros
	public function editar($id,$nombre)
	{
		try {
			$id = limpiarCadena($id);
			$nombre = limpiarCadena($nombre);
			$sql="UPDATE categoria SET nombre='$nombre' WHERE id='$id'";
			return ejecutarConsulta($sql);
		} catch (Exception $e) {
			return false;
		}
	}

	//Implementamos un método para eliminar registros
	public function eliminar($id)
	{
		try {
			$id = limpiarCadena($id);
			$sql="DELETE FROM categoria WHERE id='$id'";
			return ejecutarConsulta($sql);
		} catch (Exception $e) {
			return false;
		}
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id)
	{
		try {
			$id = limpiarCadena($id);
			$sql="SELECT * FROM categoria WHERE id='$id'";
			return ejecutarConsultaSimpleFila($sql);
		} catch (Exception $e) {
			return false;
		}
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		try {
			$sql="SELECT * FROM categoria ORDER BY id ASC";
			return ejecutarConsulta($sql);
		} catch (Exception $e) {
			return false;
		}
	}
}

?>