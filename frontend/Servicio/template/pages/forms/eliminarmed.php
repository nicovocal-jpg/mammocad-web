<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM usuario WHERE id = $id";
    if (mysqli_query($conexion, $sql)) {
        echo "Operador eliminado exitosamente.";
    } else {
        echo "Error al eliminar operador.";
    }
} else {
    echo "ID inválido.";
}
?>