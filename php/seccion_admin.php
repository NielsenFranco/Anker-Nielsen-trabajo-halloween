<?php
// Verificar si la conexión ya existe, si no, incluirla
if (!isset($con)) {
    include('../includes/conexion.php');
}

$mensaje = ""; // Variable para mostrar mensajes

// Verifica si el usuario tiene rol de Administrador (idrol = 1)
if (isset($_SESSION['idrol']) && $_SESSION['idrol'] == 1) {

    // Verificar si el usuario está autenticado y si tiene el rol de "Administración"
    if (!isset($_SESSION['idrol']) || $_SESSION['idrol'] != 1) {
        echo "<script>
                alert('Acceso denegado. Solo los administradores pueden acceder a esta sección.');
                window.location.href = '../index.php';
            </script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
        $nombre = $_POST['disfraz-nombre'];
        $descripcion = $_POST['disfraz-descripcion'];
        $foto = $_FILES['disfraz-foto'];

        // Leer el contenido binario del archivo de imagen
        $fotoData = file_get_contents($foto['tmp_name']);

        // Insertar en la base de datos
        $insertQuery = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("sss", $nombre, $descripcion, $fotoData);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Disfraz agregado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al agregar el disfraz.";
        }
    }

    // Verificar si se está editando un disfraz
    if (isset($_POST['editar']) && isset($_POST['iddisfraz'])) {
        $iddisfraz = $_POST['iddisfraz'];
        $nombre = $_POST['disfraz-nombre'];
        $descripcion = $_POST['disfraz-descripcion'];
        $foto = $_FILES['disfraz-foto'];

        // Obtener la foto anterior antes de hacer cualquier cambio
        $getFotoQuery = "SELECT foto FROM disfraces WHERE iddisfraz = ?";
        $stmt = $con->prepare($getFotoQuery);
        $stmt->bind_param("i", $iddisfraz);
        $stmt->execute();
        $result = $stmt->get_result();
        $fotoDataAnterior = null;
        if ($row = $result->fetch_assoc()) {
            $fotoDataAnterior = $row['foto']; // Foto anterior
        }

        // Si se seleccionó una nueva foto, reemplazarla, sino, mantener la anterior
        $fotoData = $foto['tmp_name'] ? file_get_contents($foto['tmp_name']) : $fotoDataAnterior;

        // Actualizar la base de datos
        $updateQuery = "UPDATE disfraces SET nombre = ?, descripcion = ?, foto = ? WHERE iddisfraz = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("sssi", $nombre, $descripcion, $fotoData, $iddisfraz);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Disfraz actualizado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el disfraz.";
        }
    }

    // Verificar si se está eliminando un disfraz
    if (isset($_GET['eliminar']) && isset($_GET['iddisfraz'])) {
        $iddisfraz = $_GET['iddisfraz'];

        $deleteQuery = "DELETE FROM disfraces WHERE iddisfraz = ?";
        $stmt = $con->prepare($deleteQuery);
        $stmt->bind_param("i", $iddisfraz);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Disfraz eliminado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el disfraz.";
        }
    }
?>

    <link rel="stylesheet" href="../css/estilos.css">
    <!-- Sección de administración para agregar disfraces -->
    <section id="admin" class="section">
        <h2>Panel de Administración</h2>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <label for="disfraz-nombre">Nombre del Disfraz:</label>
            <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
            
            <label for="disfraz-descripcion">Descripción del Disfraz:</label>
            <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
            
            <label for="disfraz-foto">Foto:</label>
            <input type="file" id="disfraz-foto" name="disfraz-foto" required>

            <button type="submit" name="agregar">Agregar Disfraz</button>
        </form>

        <?php if ($mensaje): ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
    </section>

    <!-- Lista de disfraces -->
    <h2>Edición de trajes</h2>
    <section id="disfraces-list" class="seccion-disfraces">
        <?php
        $query = "SELECT iddisfraz, nombre, descripcion, foto FROM disfraces";
        $result = $con->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="disfraz" data-iddisfraz="' . $row['iddisfraz'] . '">';
                echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';

                $fotoBase64 = base64_encode($row['foto']);
                echo '<p><img src="data:image/jpeg;base64,' . $fotoBase64 . '" width="100%"></p>';

                // Formulario de edición
                echo '<form action="index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="iddisfraz" value="' . $row['iddisfraz'] . '">
                        <input type="text" name="disfraz-nombre" value="' . htmlspecialchars($row['nombre']) . '" required>
                        <textarea name="disfraz-descripcion" required>' . htmlspecialchars($row['descripcion']) . '</textarea>
                        <input type="file" name="disfraz-foto">
                        <button type="submit" name="editar">Guardar Cambios</button>
                    </form>';

                // Botón de borrar
                echo '<a href="index.php?eliminar=true&iddisfraz=' . $row['iddisfraz'] . '" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este disfraz?\')">
                        <button>Eliminar</button>
                    </a>';

                echo '</div>';
            }
        } else {
            echo '<p>No hay disfraces disponibles.</p>';
        }
        ?>
    </section>

<?php
} else {
    echo "<script><p>Acceso denegado. Solo los administradores pueden acceder a esta sección.</p></script>";
}
?>
