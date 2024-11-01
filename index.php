<?php
session_start();
include("includes/conexion.php");
conectar();
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

    <nav class="seccion-superior">  
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <li><a href="#admin">Panel de Administración</a></li>
        </ul>
        <div class="dropdown">
            <?php
            if (isset($_SESSION['idusuario'])) {
                $nombre_usuario = $_SESSION['nombre_usuario']; // Guardamos el nombre en la sesión
                $idrol = $_SESSION['idrol']; // Obtenemos el rol desde la sesión
                
                // Muestra el menú desplegable con el nombre de usuario y rol
                echo '
                <input type="checkbox" id="dropdown-toggle" hidden>
                <label for="dropdown-toggle" class="perfil-icon" title="Perfil del usuario">
                    <i class="fas fa-user-cog"></i>
                </label>
                <div class="dropdown-content">
                    <p><b>Usuario:</b> ' . htmlspecialchars($nombre_usuario) . '</p>
                    <a href="index.php?salir=ok" class="logout-btn">Cerrar sesión</a>
                </div>';
            }
            ?>
        </div>

        <?php
        // Lógica para cerrar sesión
        if (isset($_GET['salir']) && $_GET['salir'] == 'ok') {   
            session_destroy();
            echo "<script> alert('Sesión cerrada exitosamente.');</script>";
            echo "<script> window.location='index.php';</script>";
        }
        ?>
    </nav>

    <header>
        <h1>Concurso de disfraces de Halloween</h1>
    </header>

    <main>

    <section id="disfraces-list" class="seccion-disfraces">

    <div class="disfraz">
            <h2>Krampus</h2>
            <h3>Disfraz de Krampus, demonio de la navidad</h3>
            <p><img src="imagenes/krampus.jpg" width="100%"></p>
            <?php
            // Verificar si se ha iniciado sesión y si el idusuario es 2
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Chucky</h2>
            <h3>Disfraz de Chucky</h3>
            <p><img src="imagenes/chucky.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Demon Angel</h2>
            <h3>Disfraz de Angel demoníaco</h3>
            <p><img src="imagenes/angel-demonio.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Art el Payaso</h2>
            <h3>Disfraz de Art el payaso de la película Terrifier</h3>
            <p><img src="imagenes/payaso.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

    </section>

    <section id="registro" class="section">
        <h2>Registro</h2>
        <form action="index.php" method="POST">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            
            <label for="contrasenia">Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
            
            <input type="hidden" name="idrol" value="2">
            
            <button type="submit" name="register">Registrarse</button>
        </form>

        <?php
        if (isset($_POST['register'])) { 
            $nombre_usuario = $_POST['nombre_usuario'];
            $contrasenia = $_POST['contrasenia'];
            $idrol = 2;

            $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 's', $nombre_usuario);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) == 0) {
                $sql = "INSERT INTO usuarios (nombre_usuario, contrasenia, idrol) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, 'ssi', $nombre_usuario, $contrasenia, $idrol);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script> alert('Usuario creado exitosamente.');</script>";
                    echo "<script> window.location='index.php';</script>";
                } else {
                    echo "<script> alert('Error al crear el usuario: ".mysqli_error($con)."');</script>";  
                }
            } else {
                echo "<script> alert('El nombre de usuario ya existe. Por favor, elija otro.');</script>";
            }
        }
        ?>
    </section>

    <section id="login" class="section">
        <h2>Iniciar Sesión</h2>
        <form action="index.php" method="POST">
            <label for="login-username">Nombre de Usuario:</label>
            <input type="text" id="login-username" name="nombre_usuario" required>
            
            <label for="login-password">Contraseña:</label>
            <input type="password" id="login-password" name="contrasenia" required>
            
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>

        <?php
        if (isset($_POST['login'])) {
            $username = $_POST['nombre_usuario'];
            $password = $_POST['contrasenia'];

            $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasenia = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) { 
                $r = mysqli_fetch_array($resultado);
                $_SESSION['idusuario'] = $r['idusuario'];
                $_SESSION['nombre_usuario'] = $r['nombre_usuario'];
                $_SESSION['idrol'] = $r['idrol'];
                echo "<script> window.location='index.php';</script>";
            } else {
                echo "<script> alert('Nombre de usuario o contraseña incorrectos.');</script>";
            }
        }
        ?>
    </section>

    <section id="admin" class="section">
        <?php
        // Verifica si el usuario tiene rol de Administrador (idrol = 1)
        if (isset($_SESSION['idrol']) && $_SESSION['idrol'] == 1) {
        ?>
            <h2>Panel de Administración</h2>
            <form action="procesar_disfraz.php" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
                
                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
                
                <label for="disfraz-foto">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
        <?php
        } else {
            echo "<p>Acceso denegado. Solo los administradores pueden acceder a esta sección.</p>";
        }
        ?>
    </section>

    </main>

    <script src="js/script.js"></script>

</body>

</html>