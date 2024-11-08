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

        // Lógica para cerrar sesión
        if (isset($_GET['salir']) && $_GET['salir'] == 'ok') {   
            session_destroy();
            echo "<script> alert('Sesión cerrada exitosamente.');</script>";
            echo "<script> window.location='index.php';</script>";
        }
    ?>
</div>