<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "SELECT * FROM administrador WHERE id_administrador = $id";
    $result = mysqli_query($conexion, $sql);
    if ($mostrar = mysqli_fetch_assoc($result)) {
        echo json_encode($mostrar);
    } else {
        echo json_encode(["error" => "No encontrado"]);
    }
} else {
    echo json_encode(["error" => "ID inválido"]);
}
?>
