<?php
// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) session_start();

//Ip de la pc servidor de base de datos
define("DB_HOST", "localhost");

//Nombre de la base de datos
define("DB_NAME", "bd_facturacion");

//Usuario de la base de datos
define("DB_USERNAME", "root");

//Contraseña del usuario de la base de datos
define("DB_PASSWORD", "");

//definimos la codificación de los caracteres
define("DB_ENCODE", "utf8");

//Definimos una constante como nombre del proyecto
define("PRO_NOMBRE", "ITVentas");
?>