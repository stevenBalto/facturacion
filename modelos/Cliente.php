<?php
require "../config/Conexion.php";

class Cliente
{
    public function __construct()
    {
    }

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

    public function buscar($cedula)
    {
        $sql = "SELECT * FROM cliente WHERE cedula='$cedula'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        try {
            $sql = "SELECT * FROM cliente ORDER BY cedula ASC";
            return ejecutarConsulta($sql);
        } catch (Exception $e) {
            return false;
        }
    }

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


    public function consultar($campo, $dato)
    {
        // En cliente, claves válidas
        $permitidos = ['cedula', 'nombre'];
        if (!in_array($campo, $permitidos, true)) {
            $sql = "SELECT cedula, nombre, telefono, direccion FROM cliente WHERE 1=0";
            return ejecutarConsulta($sql);
        }

        if ($campo === 'nombre') {
            $sql = "SELECT cedula, nombre, telefono, direccion
                    FROM cliente
                    WHERE nombre LIKE '%$dato%'";
        } else { // cedula
            $sql = "SELECT cedula, nombre, telefono, direccion
                    FROM cliente
                    WHERE cedula = '$dato'";
        }
        return ejecutarConsulta($sql);
    }
    public function contarClientes()
    {
        $sql = "SELECT COUNT(*) AS total FROM cliente";
        return ejecutarConsultaSimpleFila($sql);
    }

}
