<?php 
require "../config/Conexion.php";

Class Categoria
{
	public function __construct()
	{

	}

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

	public function listar()
	{
		try {
			$sql="SELECT * FROM categoria ORDER BY id ASC";
			return ejecutarConsulta($sql);
		} catch (Exception $e) {
			return false;
		}
	}
	 public function consultar($campo, $dato) {
        // Whitelist de columnas válidas
        $permitidos = ['id','nombre'];
        if (!in_array($campo, $permitidos, true)) {
            // campo inválido → sin resultados
            $sql = "SELECT id, nombre FROM categoria WHERE 1=0";
            return ejecutarConsulta($sql);
        }

        if ($campo === 'nombre') {
            $sql = "SELECT id, nombre FROM categoria
                    WHERE nombre LIKE '%$dato%'";
        } else { // id
            $sql = "SELECT id, nombre FROM categoria
                    WHERE id = '$dato'";
        }
        return ejecutarConsulta($sql);
    }
}

?>