<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);

$busqueda = $_GET['q'] ?? '';
$page = intval($_GET['page'] ?? 1);
$registros_por_pagina = 10;
$offset = ($page - 1) * $registros_por_pagina;

// Consulta de datos
$sql = "SELECT * FROM usuario 
        WHERE nombrec LIKE '%$busqueda%' 
           OR email LIKE '%$busqueda%'
        LIMIT $offset, $registros_por_pagina";
$result = mysqli_query($conexion, $sql);

// Conteo total
$sql_total = "SELECT COUNT(*) as total FROM usuario 
              WHERE nombrec LIKE '%$busqueda%' 
                 OR email LIKE '%$busqueda%'";
$total_result = mysqli_query($conexion, $sql_total);
$total_row = mysqli_fetch_assoc($total_result);
$total_registros = $total_row['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Construir HTML
$html = "";
$contador = $offset + 1;
while($mostrar = mysqli_fetch_array($result)){
    $html .= "<tr>";
    $html .= "<td>{$contador}</td>";
    $html .= "<td>{$mostrar['nombrec']}</td>";
    $html .= "<td>{$mostrar['email']}</td>";
    $html .= "<td>{$mostrar['genero']}</td>";
    $html .= "<td>
                <div class='d-flex align-items-center'>
                    <button class='btn btn-success btn-sm m-1' onclick='abrirEditar(" . $mostrar['id'] . ")'>Editar</button>
                    <button class='btn btn-danger btn-sm m-1' onclick='confirmarEliminar(" . $mostrar['id'] . ")'>Eliminar</button>
                </div>
              </td>";
    $html .= "</tr>";
    $contador++;
}

// Devolver JSON
echo json_encode([
    'tabla' => $html,
    'pagina_actual' => $page,
    'total_paginas' => $total_paginas
]);
?>