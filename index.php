<?php
session_start();
include("includes/conexion.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concurso de disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="imagenes/calabaza.png" type="image/png">
</head>
<script src="js/script.js"></script>
<body>

    <nav class="seccion-superior">  
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <li><a href="#admin">Panel de Administración</a></li>
        </ul>
        
        <?php
        include('php/menu_usuario.php');
        ?>
        
    </nav>

    <header>
        <h1>Concurso de disfraces de Halloween</h1>
    </header>

    <main>

    <?php
    include('php/seccion_disfraces.php');
    include('php/inicio_sesion.php');
    ?>
    <?php
    // Verifica si el usuario tiene rol de Administrador (idrol = 1)
    if (isset($_SESSION['idrol']) && $_SESSION['idrol'] == 1) {
        include('php/seccion_admin.php');
    } else {
        echo "<p>Acceso denegado. Solo los administradores pueden acceder a esta sección.</p>";
    }
    ?>
    
    </main>
</body>
</html>