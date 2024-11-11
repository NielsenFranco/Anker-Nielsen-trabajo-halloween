<?php

session_start();
include("../includes/conexion.php");

header("Content-Type: application/json");

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$con) {
        $response["message"] = "Error de conexión a la base de datos.";
        echo json_encode($response);
        exit();
    }

    if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
        $disfraz_id = $_POST['iddisfraz'];
        $usuario_id = $_SESSION['idusuario'];

        $checkQuery = "SELECT * FROM votaciones WHERE idusuario = ? AND iddisfraz = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("ii", $usuario_id, $disfraz_id);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            $response["message"] = "¡Ya has votado por este disfraz!";
        } else {
            $insertQuery = "INSERT INTO votaciones (idusuario, iddisfraz) VALUES (?, ?)";
            $stmt = $con->prepare($insertQuery);
            $stmt->bind_param("ii", $usuario_id, $disfraz_id);

            if ($stmt->execute()) {
                $response["success"] = true;
                $response["message"] = "¡Gracias por tu voto!";
            } else {
                $response["message"] = "Hubo un error al registrar tu voto. Inténtalo de nuevo.";
            }
        }

        $stmt->close();
    } else {
        $response["message"] = "Debes iniciar sesión para votar.";
    }

    $con->close();
} else {
    $response["message"] = "Método de solicitud no permitido.";
}

echo json_encode($response);
exit();
?>
