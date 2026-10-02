<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM administrador WHERE id_administrador = $id";
    if (mysqli_query($conexion, $sql)) {
        echo "Administrador eliminado exitosamente.";
    } else {
        echo "Error al eliminar administrador.";
    }
} else {
    echo "ID inválido.";
}
?>