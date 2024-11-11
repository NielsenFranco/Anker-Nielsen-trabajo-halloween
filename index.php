<?php
session_start();
include("includes/conexion.php");

// Suponiendo que tienes el idrol o el nombre del rol en la sesión de usuario
// Por ejemplo: $_SESSION['idrol'] y $_SESSION['nombre_rol']
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

<body>
<script src="js/script.js"></script>
    <nav class="seccion-superior">  
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            
            <?php
            // Mostrar el enlace al panel de administración solo si el usuario tiene el rol adecuado
            if (isset($_SESSION['idrol']) && ($_SESSION['idrol'] == 1)) {
                echo '<li><a href="#admin">Panel de Administración</a></li>';
            }
            ?>
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
    if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 1) {
    include('php/seccion_admin.php');
    }
    ?>
    
    
    </main>
</body>
</html>
