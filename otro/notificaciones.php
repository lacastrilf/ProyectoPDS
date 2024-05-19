<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location: /ProyectoPDS/inicio/login.php");
    exit;
}

$idUsuario = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "base_proyecto");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$sql = "SELECT icono, color, titulo, mensaje, fecha FROM notificaciones WHERE id_usuario = $idUsuario";

$resultado = $conexion->query($sql);

$notifications = array();

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $notifications[] = array(
            'icon' => $fila['icono'],
            'color' => $fila['color'],
            'title' => $fila['titulo'],
            'message' => $fila['mensaje'],
            'time' => $fila['fecha']
        );
    }
}

header('Content-Type: application/json');
echo json_encode($notifications);

$conexion->close();
?>




