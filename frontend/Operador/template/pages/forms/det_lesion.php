<?php
// pred_clasi.php
if (isset($_POST['dcm_path1'])) {
    $dcm_path = $_POST['dcm_path1'];

    $python_script = '../../../../../backend/detectar.py';
    $command = "python " . escapeshellarg($python_script) . " " . escapeshellarg($dcm_path);
    $output = shell_exec($command);
    preg_match('/\{(?:[^{}]|(?R))*\}/s', $output, $matches);
    $json_output = $matches[0] ?? $output;

    // Decodificar JSON
    $result = json_decode($json_output, true);
    
      // Enviar respuesta limpia
      echo json_encode([
        'success' => true,
        'image_path' => $result['image_path'],
        'bounding_boxes' => $result['bounding_boxes']
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo "No se recibió la ruta del archivo DCM.";
}
?>