<?php

$mensaje = ""; // Variable para almacenar mensajes de notificación

// Procesar la eliminación de un disfraz
if (isset($_POST['eliminar'])) {
    $id_disfraz = $_POST['id_disfraz'];
    $deleteQuery = "DELETE FROM disfraces WHERE iddisfraz = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $id_disfraz);
    $stmt->execute();
    $mensaje = "Disfraz eliminado correctamente."; // Mensaje de éxito
}

// Procesar la adición de un nuevo disfraz
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['disfraz-nombre'];
    $descripcion = $_POST['disfraz-descripcion'];
    $foto = $_FILES['disfraz-foto'];

    // Mover la imagen a la carpeta de imágenes
    $targetDir = "../imagenes/";
    $targetFile = $targetDir . basename($foto["name"]);
    move_uploaded_file($foto["tmp_name"], $targetFile);

    $insertQuery = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("sss", $nombre, $descripcion, $targetFile);
    $stmt->execute();
    $mensaje = "Disfraz agregado correctamente."; // Mensaje de éxito
}

// Consulta para obtener los disfraces de la base de datos
$query = "SELECT iddisfraz, nombre, descripcion, foto FROM disfraces";
$result = $con->query($query);

?>


<?php
    // Verifica si el usuario tiene rol de Administrador (idrol = 1)
    if (isset($_SESSION['idrol']) && $_SESSION['idrol'] == 1) {
?> 
<link rel="stylesheet" href="../css/estilos.css">

<?php
    // Agregar un nuevo disfraz
    if (isset($_POST['disfraz-nombre']) && isset($_POST['disfraz-descripcion']) && isset($_FILES['disfraz-foto'])) {
        $nombre = $_POST['disfraz-nombre'];
        $descripcion = $_POST['disfraz-descripcion'];
        $foto = $_FILES['disfraz-foto'];

        // Subir la foto
        $directorio = "../imagenes/";
        $rutaFoto = $directorio . basename($foto['name']);
        move_uploaded_file($foto['tmp_name'], $rutaFoto);

        // Insertar en la base de datos
        $consulta = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
        $stmt = $con->prepare($consulta);
        $stmt->bind_param("sss", $nombre, $descripcion, $rutaFoto);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Se agregó el disfraz.";
        } else {
            $_SESSION['mensaje'] = "Error al cargar el disfraz.";
        }
    }
?>

<section id="admin" class="section">
    <h2>Panel de Administración</h2>
    <form action="seccion_admin.php" method="POST" enctype="multipart/form-data">
        <label for="disfraz-nombre">Nombre del Disfraz:</label>
        <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
        
        <label for="disfraz-descripcion">Descripción del Disfraz:</label>
        <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
        
        <label for="disfraz-foto">Foto:</label>
        <input type="file" id="disfraz-foto" name="disfraz-foto" required>

        <button type="submit">Agregar Disfraz</button>
    </form>
</section>
<br>
<br>
<h2>Lista de Disfraces</h2>
<?php
    // Consulta para obtener los disfraces de la base de datos
    $query = "SELECT iddisfraz, nombre, descripcion, foto FROM disfraces";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="disfraz">';
            echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
            echo '<img src="' . htmlspecialchars($row['foto']) . '" width="100%">';

            // Formulario para eliminar disfraz
            echo '<form action="seccion_admin.php" method="POST">';
            echo '<input type="hidden" name="id_disfraz" value="' . htmlspecialchars($row['iddisfraz']) . '">';
            echo '<button type="submit" name="eliminar">Eliminar Disfraz</button>';
            echo '</form>';
            
            // Formulario para editar disfraz
            echo '<form action="seccion_admin.php" method="POST" enctype="multipart/form-data">';
            echo '<input type="hidden" name="id_disfraz" value="' . htmlspecialchars($row['iddisfraz']) . '">';
            echo '<label for="disfraz-nombre">Nombre:</label>';
            echo '<input type="text" name="disfraz-nombre" value="' . htmlspecialchars($row['nombre']) . '" required>';
            echo '<label for="disfraz-descripcion">Descripción:</label>';
            echo '<textarea name="disfraz-descripcion" required>' . htmlspecialchars($row['descripcion']) . '</textarea>';
            echo '<label for="disfraz-foto">Nueva Foto (opcional):</label>';
            echo '<input type="file" name="disfraz-foto">'; // Permitir subir una nueva foto

            echo '<button type="submit" name="editar">Editar Disfraz</button>';
            echo '</form>';

            echo '</div>';
            echo '<hr>';
        }
    } else {
        echo '<p>No hay disfraces disponibles.</p>';
    }
?>
<?php
} else {
    echo "<p>Acceso denegado. Solo los administradores pueden acceder a esta sección.</p>";
}
?>
    