<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);

$id = intval($_POST['id'] ?? 0);
$nombre = $_POST['nombre'] ?? '';
$usuario = $_POST['usuario'] ?? '';

if ($id > 0 && $nombre && $usuario) {
    $sql = "UPDATE usuario SET nombrec='$nombre', email='$usuario' WHERE id = $id";
    if (mysqli_query($conexion, $sql)) {
        echo "Administrador actualizado correctamente.";
    } else {
        echo "Error al actualizar.";
    }
} else {
    echo "Datos inválidos.";
}
?>