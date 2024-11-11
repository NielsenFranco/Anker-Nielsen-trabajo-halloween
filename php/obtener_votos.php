<?php
include("../includes/conexion.php");

header("Content-Type: application/json");

$response = ["success" => false, "votos" => 0];

if (isset($_GET['iddisfraz'])) {
    $disfraz_id = $_GET['iddisfraz'];

    $query = "SELECT COUNT(*) AS votos FROM votaciones WHERE iddisfraz = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $disfraz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $response["success"] = true;
        $response["votos"] = $row["votos"];
    }

    $stmt->close();
}

$con->close();
echo json_encode($response);
exit();
?>
