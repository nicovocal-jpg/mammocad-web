<?php
// pred_clasi.php

$acr = "N/A"; // Valor por defecto

if (isset($_POST['dcm_path2'])) {
    $dcm_path = $_POST['dcm_path2'];

    // Ruta al script de Python
    $python_script = '../../../../../backend/densidad_mama.py';

    // Ejecutar script de Python con la ruta de la imagen
    $command = "python " . escapeshellarg($python_script) . " " . escapeshellarg($dcm_path);
    $output = shell_exec($command);

    if ($output) {
        $resultado = json_decode($output, true);

        if ($resultado && isset($resultado['clasificacion'])) {
            $acr = $resultado['clasificacion'];
        }
    }
}

// Devolver siempre JSON
echo json_encode(['clasificacion' => $acr]);

?>