<?php 
//Importamos la clase Prestamo.php
require_once "../modelos/Prestamo.php";
// Instaciamos la clase Prestamo
$prestamo=new Prestamo();

// Recibir el encabezado enviado por AJAX
$encabezado=$_POST['encabezado'];
$cedula = $encabezado['cedula'];
$nombre = $encabezado['nombre'];
$fecha = $encabezado['fecha'];
$fechaformato = date('Y-m-d', strtotime($fecha));
$prestamo->insertarencabezado($cedula, $nombre, $fechaformato);
$idprestamo=$prestamo->Obtenerid();


// Recibir el detalle enviado por AJAX
$detalle = json_decode($_POST['detalle'], true);
foreach ($detalle as $dato) {
    $codigo = $dato[0];
    $nombre = $dato[1];
    $fecha = date('Y-m-d', strtotime($dato[2]));
    $rspta=$prestamo->insertardetalle($codigo,$nombre,$fecha, $idprestamo['idprestamo']);
			if (intval($rspta)==1){
				echo "Prestamo Insertado";
			}
			else
            {
				echo "Error al Insertar los datos";
			}
}
?>