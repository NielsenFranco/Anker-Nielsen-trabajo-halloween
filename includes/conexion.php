<?php

$servername = "localhost"; 
$username = "root";         
$password = "";             
$dbname = "halloween";      

// Crear conexión
$con = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($con->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 (opcional)
$con->set_charset("utf8");
?>