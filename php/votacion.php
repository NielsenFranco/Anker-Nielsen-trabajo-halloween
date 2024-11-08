<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['idusuario'])) {
        $disfraz_id = $_POST['iddisfraz'];
        $usuario_id = $_SESSION['idusuario'];

        // Verificar si el usuario ya ha votado por este disfraz
        $checkQuery = "SELECT * FROM votaciones WHERE idusuario = ? AND iddisfraz = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ii", $usuario_id, $disfraz_id);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            $_SESSION['message'] = "¡Ya has votado por este disfraz!";
        } else {
            // Registrar el voto
            $insertQuery = "INSERT INTO votaciones (idusuario, iddisfraz) VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ii", $usuario_id, $disfraz_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "¡Gracias por tu voto!";
            } else {
                $_SESSION['message'] = "Hubo un error al registrar tu voto. Inténtalo de nuevo.";
            }
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Debes iniciar sesión para votar.";
    }

    $conn->close();
    header("Location: index.php");
    exit();
}
?>
