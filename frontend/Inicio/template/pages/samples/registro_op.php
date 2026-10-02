<?php

// Datos de conexión a la base de datos (¡debes modificarlos con tus propios datos!)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_login_database";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$genero = $_POST['genero'];

// --- VALIDACIONES ---
$errores = [];

// 1. Validar el nombre (no permitir caracteres especiales)
if (!preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
    $errores[] = "El nombre solo puede contener letras y espacios.";
}
if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio.";
}

// 2. Validar el email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electrónico no es válido.";
}
if (empty($email)) {
    $errores[] = "El correo electrónico es obligatorio.";
}

// 3. Validar la contraseña (longitud mínima)
if (strlen($password) < 6) {
    $errores[] = "La contraseña debe tener al menos 6 caracteres.";
}
if (empty($password)) {
    $errores[] = "La contraseña es obligatoria.";
}

// 4. Verificar que las contraseñas coincidan
if ($password !== $confirm_password) {
    $errores[] = "Las contraseñas no coinciden.";
}

// 5. Verificar que se haya seleccionado un género
if (empty($genero)) {
    $errores[] = "Por favor, selecciona un género.";
}

// Si hay errores, redirigir con los mensajes de error
if (!empty($errores)) {
    $error_message = urlencode(implode("<br>", $errores));
    header("Location: registro.php?error=" . $error_message);
    $conn->close();
    exit();
}

// --- VERIFICAR SI EL EMAIL YA EXISTE EN LA BASE DE DATOS (¡recomendado!) ---
$sql_check_email = "SELECT email FROM usuario WHERE email = '$email'";
$result_check_email = $conn->query($sql_check_email);

if ($result_check_email->num_rows > 0) {
    header("Location: registro.php?error=" . urlencode("Este correo electrónico ya está registrado."));
    $conn->close();
    exit();
}

// --- HASHING DE LA CONTRASEÑA ---

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// --- INSERCIÓN DE DATOS EN LA BASE DE DATOS ---
$sql = "INSERT INTO usuario (email, password, nombrec, genero) VALUES ('$email', '$hashed_password', '$nombre', '$genero')";

if ($conn->query($sql) === TRUE) {
    header("Location: registro.php?success=" . urlencode("Registro exitoso."));
} else {
    header("Location: registro.php?error=" . urlencode("Error al registrar el usuario: " . $conn->error));
}

// Cerrar la conexión
$conn->close();

?>