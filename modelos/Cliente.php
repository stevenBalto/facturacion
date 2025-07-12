<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cliente
{
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar($cedula, $nombre, $telefono, $direccion)
    {
        try {
            $cedula = limpiarCadena($cedula);
            $nombre = limpiarCadena($nombre);
            $telefono = limpiarCadena($telefono);
            $direccion = limpiarCadena($direccion);
            $sql = "INSERT INTO cliente (cedula, nombre, telefono, direccion) VALUES ('$cedula', '$nombre', '$telefono', '$direccion')";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    //Implementamos un método para editar registros
    public function editar($cedula, $nombre, $telefono, $direccion)
    {
        try {
            $cedula = limpiarCadena($cedula);
            $nombre = limpiarCadena($nombre);
            $telefono = limpiarCadena($telefono);
            $direccion = limpiarCadena($direccion);
            $sql = "UPDATE cliente SET nombre='$nombre', telefono='$telefono', direccion='$direccion' WHERE cedula='$cedula'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    //Implementamos un método para eliminar registros
    public function eliminar($cedula)
    {
        try {
            $cedula = limpiarCadena($cedula);
            $sql = "DELETE FROM cliente WHERE cedula='$cedula'";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Busca un cliente por cédula
    public function buscar($cedula)
    {
        $sql = "SELECT * FROM cliente WHERE cedula='$cedula'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Lista todos los clientes
    public function listar()
    {
        try {
            $sql = "SELECT * FROM cliente ORDER BY cedula ASC";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    // Busca un cliente por Id
    public function mostrar($cedula)
    {
        try {
            $cedula = limpiarCadena($cedula);
            $sql = "SELECT * FROM cliente WHERE cedula='$cedula'";
            return ejecutarConsultaSimpleFila($sql);
        } catch (Exception $e) {
            return false;
        }
    }
}
