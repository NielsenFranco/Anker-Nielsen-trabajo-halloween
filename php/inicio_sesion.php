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