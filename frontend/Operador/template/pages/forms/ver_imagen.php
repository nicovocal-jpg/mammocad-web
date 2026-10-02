<?php
$image_path = isset($_GET['image_path']) ? urldecode($_GET['image_path']) : null;

if ($image_path) {

    $ruta_series_dcm = dirname($image_path);
    $dicomPath = $ruta_series_dcm;

    // Obtener archivos DICOM
    $dicomFiles = [];
    if (is_dir($dicomPath)) {
        $files = scandir($dicomPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'dcm') {
                $dicomFiles[] = $file;
            }
        }
    }
    sort($dicomFiles);

    // Aquí puedes continuar utilizando las variables $image_path, $ruta_series_dcm y $dicomFiles
} else {
    // Define valores por defecto si no hay image_path en la URL inicial
    $dicomPath = '';
    $dicomFiles = [];
}

$acr = 'N/A';

?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>CALCIVIEW</title>
<link rel="shortcut icon" href="../../../../Inicio/template/images/LOGO.png" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <meta charset="UTF-8">
  <title>Visor DICOM Avanzado</title>
  <style>
  body {
   margin: 0;
   background: #1d1e1f  ; /* Fondo oscuro general */
   font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
   color: #d4d4d4; /* Texto claro */
   display: flex;
   flex-direction: column;
   height: 100vh;
  }
  #header {
   background-color: #252526;
   color: #d4d4d4;
   padding: 10px 20px;
   display: flex;
   justify-content: space-between;
   align-items: center;
   border-bottom: 1px solid #333;
  }
  #main-container {
   display: flex;
   flex-grow: 1;
  }
  .sidebar-left,
  .sidebar-right {
   background-color: #252526;
   width: 300px; /* Ancho ajustado para el ejemplo */
   padding: 10px;
   border-right: 1px solid #333;
   border-left: 1px solid #333;
   overflow-y: auto;
   display: flex;
   flex-direction: column;
   gap: 10px; /* Espacio entre elementos */
  }
  .sidebar-left {
   border-left: none;
  }
  .sidebar-right {
   border-right: none;
  }
 #image-viewer-container {
  flex-grow: 1;
  background-color: #1d1e1f; /* Fondo negro del visor */
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #ccc; /* Añade un borde gris claro */
  border-radius: 10px; /* Bordes redondeados, opcional */
  padding: 4px; /* Espacio dentro del borde */
}

#dicomImage {
  position: relative;  /* Necesario para el posicionamiento absoluto de la lupa */
  background: #1d1e1f;
  cursor: grab;
  width: 100%; /* Ahora ocupa todo el ancho del contenedor */
  height: 100%; /* Ahora ocupa todo el alto del contenedor */
  overflow: hidden;    /* Evita que la lupa se salga del contenedor */
}


  .sidebar-left h2 {
   color: #d4d4d4;
   margin-top: 0;
   margin-bottom: 10px;
   border-bottom: 1px solid #333;
   padding-bottom: 5px;
   font-size: 1.1em;
   text-align: left; /* Alineación a la izquierda */
  }
  .sidebar-left div {
   display: flex;
   flex-direction: column;
   gap: 8px;
  }
  .sidebar-left label {
   font-size: 0.9em;
   color: #ccc;
  }
  .sidebar-left input[type="number"] {
   padding: 6px;
   border: 1px solid #555;
   border-radius: 3px;
   font-size: 0.9em;
   background-color: #333;
   color: #d4d4d4;
  }
  .sidebar-left input[type="number"]:focus {
   outline: none;
   border-color: #888;
  }
  .sidebar-left button {
   display: block; /* Para ocupar todo el ancho */
   width: 100%;
   background-color: #333;
   color: #d4d4d4;
   padding: 8px 12px;
   border: 1px solid #555;
   border-radius: 3px;
   cursor: pointer;
   font-size: 0.9em;
   transition: background-color 0.2s ease;
   text-align: left; /* Alinear texto a la izquierda */
  }
  .sidebar-left button:hover {
   background-color: #444;
  }
  #footer {
   background-color: #252526;
   color: #d4d4d4;
   padding: 10px 20px;
   text-align: right;
   border-top: 1px solid #333;
   font-size: 12px;
  }
  .dicom-thumbnail {
  cursor: default;
  width: 120px;
  height: 120px;  /* Corregido */
  margin-bottom: 10px;
}
.galeria-scroll {
  height: 500px;
  overflow-y: scroll;
  border: 1px solid #1d1e1f; /* Mismo color que el fondo */
  padding: 10px;
  background-color: #1d1e1f; /* Fondo */
  
  /* Ocultar scrollbar */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE 10+ */
}

.galeria-scroll::-webkit-scrollbar {
  width: 0px; /* Chrome, Safari, Opera */
  background: transparent;
}
.galeria-scroll1 {
  height: 400px;
  overflow-y: scroll;
  border: 1px solid #1d1e1f; /* Mismo color que el fondo */
  padding: 10px;
  background-color: #1d1e1f; /* Fondo */
  
  /* Ocultar scrollbar */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE 10+ */
}

.galeria-scroll1::-webkit-scrollbar {
  width: 0px; /* Chrome, Safari, Opera */
  background: transparent;
}

.tools {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(255, 255, 255, 0.95);
  border-bottom: 1px solid #ccc;
  border-radius: 0 0 8px 8px;
  padding: 8px 10px;
  display: flex;
  gap: 8px;
  z-index: 1000;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.control-button {
  background: none;
  border: none;
  padding: 5px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.control-button:hover {
  background-color: #e0e0e0;
  border-radius: 4px;
}

.control-button img {
  width: 1.5em;
  height: 1.5em;
  vertical-align: middle;
}

#predecir {
  display: inline-flex;
  align-items: center;
  padding: 8px 12px;
  border: none;
  background-color: #f0f0f0;
  color: #333;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.2s ease-in-out; /* Transición suave para todos los cambios */
  padding: 10px ;
  box-sizing: border-box; /* Para que padding no expanda más allá del slide */
  width: 100%; /* Hace que el botón ocupe todo el ancho del slide */
}

#predecir:hover {
  background-color: #e0e0e0; /* Cambio de color al pasar el mouse */
  transform: translateY(-2px); /* Levanta ligeramente el botón */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra sutil */
}

#predecir:active {
  transform: translateY(1px); /* Efecto de ser presionado */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Reduce la sombra al hacer clic */
}

  #predecir img {
    margin-right: 10px; /* Espacio entre la imagen y el texto */
  }

.resultado-prediccion-simple {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 12px;
  padding: 25px;
  margin: 20px 0;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);

  font-family: 'Poppins', 'Segoe UI', sans-serif;
  font-size: 1rem;
  color: #343a40;
  line-height: 1.6;
  text-align: center;
  white-space: pre-wrap;
  word-wrap: break-word;
  min-height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}



#botonExtra {
  display: inline-flex;
  align-items: center;
  padding: 8px 12px;
  border: none;
  background-color: #f0f0f0;
  color: #333;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.2s ease-in-out; /* Transición suave para todos los cambios */
  padding: 10px ;
  box-sizing: border-box; /* Para que padding no expanda más allá del slide */
  width: 100%; /* Hace que el botón ocupe todo el ancho del slide */
}

#botonExtra:hover {
  background-color: #e0e0e0; /* Cambio de color al pasar el mouse */
  transform: translateY(-2px); /* Levanta ligeramente el botón */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra sutil */
}

#botonExtra:active {
  transform: translateY(1px); /* Efecto de ser presionado */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Reduce la sombra al hacer clic */
}

  #botonExtra img {
    margin-right: 8px; /* Espacio entre la imagen y el texto */
  }

#btn_ver {
  display: inline-flex;
  align-items: center;
  padding: 8px 12px;
  border: none;
  background-color: #f0f0f0;
  color: #333;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.2s ease-in-out; /* Transición suave para todos los cambios */
  padding: 10px ;
  box-sizing: border-box; /* Para que padding no expanda más allá del slide */
  width: 100%; /* Hace que el botón ocupe todo el ancho del slide */
}

#btn_ver:hover {
  background-color: #e0e0e0; /* Cambio de color al pasar el mouse */
  transform: translateY(-2px); /* Levanta ligeramente el botón */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra sutil */
}

#btn_ver:active {
  transform: translateY(1px); /* Efecto de ser presionado */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Reduce la sombra al hacer clic */
}

  #btn_ver img {
    margin-right: 8px; /* Espacio entre la imagen y el texto */
  }

  .modal {
            display: none;
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 1000px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .modal.minimized {
            height: 40px;
            width: 300px;
            overflow: hidden;
        }
        
        .modal-header {
            padding: 10px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
            cursor: move;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px 5px 0 0;
        }
        
        .modal-title {
            font-weight: bold;
        }
        
        .modal-controls {
            display: flex;
            gap: 5px;
        }
        
        .modal-controls button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            padding: 2px 5px;
        }
        
              .modal-content img {
          max-width: 100%;
          height: auto;
          display: block;
          margin: 0 auto;
      }

        .modal-content {
          padding: 15px;
          max-height: 800px;
          overflow-y: auto;
          text-align: center; /* Centrar la imagen */
      }
          

 </style>


</head>
<body>

  <div id="header">
    <a class="logo" href="listapaciente.php" style="color: white; font-weight: bold;">CALCIVIEW</a>
    <div class="tools">
    <button onclick="capturarImagen()" class="control-button" title="Capturar"><img src="icono/camara.png"></button>
    <button id="elipse" class="control-button" title="Elipse ROI"><img src="icono/elipse.png"></button>      
    <button id="lapiz" class="control-button" title="Lazo ROI"><img src="icono/lapiz.png"></button>      
  <button id="puntero" class="control-button" title="Puntero"><img src="icono/puntero.png"></button>
  <button id="magnify" class="control-button" title="Aumento"><img src="icono/lupa.png"></button>
  <button id="zoomIn" class="control-button" title="Acercar"><img src="icono/suma.png"></button>
  <button id="zoomOut" class="control-button" title="Alejar"><img src="icono/menos.png"></button>
  <button id="invert" class="control-button" title="Invertir colores"><img src="icono/invertir_color.png"></button>
  <button id="pan_move" class="control-button" title="Mover"><img src="icono/cruz.png"></button>
  <button id="rotate" class="control-button" title="Rotación"><img src="icono/rotate.png"></button>
  <button id="angle" class="control-button" title="Ángulos"><img src="icono/angulo.png"></button>
  <button id="eraser" class="control-button" title="Borrador"><img src="icono/eraser.png"></button>
  <button id="flip" class="control-button" title="Flip Horizontal"><img src="icono/flip.png"></button>
  <button id="wwc_zone" class="control-button" title="Nivel de WWWWC"><img src="icono/wwc.png"></button>
  <button id="length_tool" class="control-button" title="Longitud"><img src="icono/regla.png"></button>
  <button id="arrow_anotate" class="control-button" title="Anotación con flecha"><img src="icono/arrow.png"></button>
  <button id="rectangulo" class="control-button" title="Rectangulo ROI"><img src="icono/rectangulo.png"></button>
  
</div>

    <div>

    </div>
  </div>

  <div id="main-container">
    <div class="sidebar-left">

    <div id="dicomInfo">
    <span id="patientName" style="color: white; font-size: 1.1em;"></span>
    <span id="studyDate" style="color: white; font-size: 1.1em;"></span>
    <span id="modality" style="color: white; font-size: 1.1em;"></span>
    <span id="viewPosition" style="color: white; font-size: 1.1em;"></span>
    <span id="acrDensity" style="color: white; font-size: 1.1em;"></span>

</div>

      <div>
    <label for="ww">WW:</label>
    <input type="number" id="ww" placeholder="0">
    <label for="wc">WC:</label>
    <input type="number" id="wc" placeholder="0">
    <button id="apply">Aplicar</button>

    <h1 style="color: white">Estudios</h1>

    <div class="galeria-scroll">
    <div class="container">
    <table class="dicom-table">
        <?php for ($i = 0; $i < count($dicomFiles); $i += 2): ?>
            <tr>
                <!-- Primera imagen del par -->
                <td>
                    <?php if (isset($dicomFiles[$i])): ?>
                        <?php $currentImagePath = $dicomPath . '/' . urlencode($dicomFiles[$i]); ?>
                        <div class="image-container reload-button-container" data-image-path="<?php echo htmlspecialchars($currentImagePath); ?>">
                            <div id="dicomImage1<?php echo $i; ?>" class="dicom-thumbnail"></div>
                        </div>
                    <?php endif; ?>
                </td>
                
                <!-- Segunda imagen del par -->
                <td>
                    <?php if (isset($dicomFiles[$i+1])): ?>
                        <?php $currentImagePath = $dicomPath . '/' . urlencode($dicomFiles[$i+1]); ?>
                        <div class="image-container reload-button-container" data-image-path="<?php echo htmlspecialchars($currentImagePath); ?>">
                            <div id="dicomImage1<?php echo $i+1; ?>" class="dicom-thumbnail"></div>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>
</div>

</div>

    </div>
    
    <div id="image-viewer-container">
    <div id="dicomImage" ></div>
    </div>
    <div class="sidebar-right">
    
    <form id="formularioPrediccion">
    <input type="hidden" id="rutaDCM" name="dcm_path" value="">
    <button id="predecir" type="button" title="Realizar prediccion ">
        <img src="icono/predecir.png" width="40" height="40">PREDICCION LESION
    </button>
</form>

<div id="resultadoPrediccion" class="resultado-prediccion-simple">

  <!-- El contenido se llenará con JavaScript -->
</div>

<form id="formularioDeteccion">
        <input type="hidden" id="ruta_dcm" name="dcm_path1" value="">
        <button id="botonExtra" style="display:none;"><img src="icono/deteccion.png" width="40" height="40">DETECCION LESION
</button>
    </form>

    <div class="galeria-scroll1">
    <div id="resultadoDeteccion">
    
  <!-- El contenido se llenará con JavaScript -->
</div>
</div>
<button id="btn_ver" style="display:none;"><img src="icono/ver_img.png" width="40" height="40">VER DETECCION
    </button>
    </div>
  </div>
  
  <div id="footer">
    
  <button id="reset" class="control-button" title="Reinicar imagen"><img src="icono/reiniciar.png"></button>
    Zoom: <span id="zoom-level">100%</span>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
  <script src="https://cdn.jsdelivr.net/npm/dicom-parser@1.8.21/dist/dicomParser.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cornerstone-math@0.1.10/dist/cornerstoneMath.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/cornerstone-math@0.1.10/coverage/html/base.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/cornerstone-core@2.6.1/dist/cornerstone.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cornerstone-wado-image-loader@4.13.2/dist/cornerstoneWADOImageLoader.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cornerstone-tools@6.0.10/dist/cornerstoneTools.min.js"></script>
  <!-- Modal flotante -->
  <div id="imageModal" class="modal">
        <div class="modal-header" id="modalHeader">
            <span class="modal-title">Imagen de la deteccion</span>
            <div class="modal-controls">
                <button id="minimizeBtn">-</button>
                <button id="closeBtn">×</button>
            </div>
        </div>
        <div class="modal-content" id="modalContent">
            <!-- Aquí se mostrará imgPath -->
        </div>
    </div>



<script>
  const reloadButtons = document.querySelectorAll('.reload-button-container');
  const element = document.getElementById('dicomImage');
  const zoomInBtn = document.getElementById('zoomIn');
  const zoomOutBtn = document.getElementById('zoomOut');
  const resetBtn = document.getElementById('reset');
  const zoomLevelDisplay = document.getElementById('zoom-level');
  const applyWWWCBtn = document.getElementById('apply');
  const invertBtn = document.getElementById('invert');
  const magnifyBtn = document.getElementById('magnify');
  const panBtn = document.getElementById('pan_move');
  const rotbtn = document.getElementById('rotate');
  const angbtn = document.getElementById('angle');
  const eraserbtn = document.getElementById('eraser');
  const punterobtn = document.getElementById('puntero');
  const filpBtn = document.getElementById('flip');
  const wwcBtn = document.getElementById('wwc_zone');
  const lengthBtn = document.getElementById('length_tool');
  const arrowBtn = document.getElementById('arrow_anotate');
  const elipBtn = document.getElementById('elipse');
  const lapizBtn = document.getElementById('lapiz');
  const rectangleBtn = document.getElementById('rectangulo');
// Elemento donde se mostrarán las mediciones


// Inicializar Cornerstone Tools
cornerstoneTools.init({
  showSVGCursors: true,
  globalToolSyncEnabled: true
});

// Habilitar el elemento
cornerstone.enable(element);
cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
cornerstoneWADOImageLoader.configure({});

// Configurar herramientas
const magnifyTool = cornerstoneTools.MagnifyTool;
const zoomMouseWheelTool = cornerstoneTools.ZoomMouseWheelTool;
const ProbeTool = cornerstoneTools.ProbeTool;
const PanTool = cornerstoneTools.PanTool;
const RotateTool= cornerstoneTools.RotateTool;
const AngleTool= cornerstoneTools.AngleTool;
const EraserTool= cornerstoneTools.EraserTool;
const WwwcRegionTool= cornerstoneTools.WwwcRegionTool;
const LengthTool = cornerstoneTools.LengthTool;
const ArrowAnnotateTool = cornerstoneTools.ArrowAnnotateTool;
const EllipticalRoiTool = cornerstoneTools.EllipticalRoiTool;;
const FreehandRoiTool = cornerstoneTools.FreehandRoiTool;
const RectangleRoiTool = cornerstoneTools.RectangleRoiTool;
// Añadir herramientas
cornerstoneTools.addTool(PanTool);

cornerstoneTools.addTool(RotateTool, {
  name: 'RotateTool', // Nombre personalizado (opcional)
  configuration: {
    rotationIncrement: 360, // Incremento de rotación en grados (opcional)
  }
});
// Configuración mejorada del Magnify Tool
cornerstoneTools.addTool(cornerstoneTools.MagnifyTool, {
  name: 'Magnify',
  configuration: {
    magnifySize: 220,          // Tamaño del área de magnificación en píxeles
    magnificationLevel: 3,     // Nivel de zoom inicial (3x)
    magnifyToolStyle: {
      position: 'absolute',    // Permite superposición
      zIndex: 1000,           // Asegura que esté por encima de todo
      width: '200px',
      height: '200px',
      border: '3px solid #00FF00',
      borderRadius: '50%',     // Forma completamente circular
      boxShadow: '0 0 15px rgba(0, 255, 0, 0.8), inset 0 0 10px rgba(0, 255, 0, 0.5)',
      pointerEvents: 'none',   // Permite interacción con la imagen debajo
      transform: 'translate(-50%, -50%)', // Centra el cursor
      background: 'rgba(0, 0, 0, 0.7)', // Fondo semitransparente
      overflow: 'hidden'       // Contenido recortado al círculo
    }
    // Nueva configuración para mejor posicionamiento
  }
});

cornerstoneTools.addTool(zoomMouseWheelTool, {
  name: 'ZoomMouseWheel',
  configuration: {
    zoomSpeed: 0.1, // Velocidad del zoom (más bajo = más suave)
    minScale: 0.1,  // Zoom mínimo permitido
    maxScale: 20.0  // Zoom máximo permitido
  }
});

cornerstoneTools.addTool(ProbeTool);


cornerstoneTools.addTool(AngleTool, {
  name: 'AngleTool', // Nombre personalizado
  configuration: {
    drawHandles: true,    // Muestra círculos en los puntos de medición
    disableAutoPan: false, // Desactiva el auto-pan al mover puntos
    textBox: true         // Muestra un cuadro de texto con el ángulo
  }
});

cornerstoneTools.addTool(EraserTool, {
  name: 'EraserTool',
  configuration: {
    strategies: {
      'Angle': true,  // Nombre exacto de la herramienta de ángulo
      'Length': true, // Ejemplo: no borrar mediciones de longitud
      'Probe': true, // Ejemplo: no borrar mediciones de longitud
      'ArroeAnnotate': true, // Ejemplo: no borrar mediciones de longitud
      'EllipticalRoi': true // Ejemplo: no borrar mediciones de longitud
      
    },
    eraseAll: false
  }
});

cornerstoneTools.addTool(WwwcRegionTool);

cornerstoneTools.addTool(LengthTool);

cornerstoneTools.addTool(cornerstoneTools.WwwcTool);

cornerstoneTools.addTool(ArrowAnnotateTool, {
  name: 'ArrowAnnotate', // Nombre personalizado de la herramienta (opcional)
  configuration: {
    // --- Estilos de visualización ---
    color: 'lime',             // Color de la flecha y el texto
    lineWidth: 2,             // Ancho de la línea de la flecha
    handleRadius: 6,          // Radio de los "handles" para mover la anotación
    drawHandlesOnHover: true,  // Mostrar los handles solo al pasar el ratón
    arrowFirst: true,         // Dibujar la punta de la flecha primero (al crear)
    textStyle: {               // Estilos del texto de la anotación
      color: 'yellow',
      fontSize: '16px',
      backgroundColor: 'rgba(0, 0, 0, 0.5)'
    },
    shadow: {                  // Estilos de la sombra del texto
      color: 'black',
      blur: 2,
      offsetX: 1,
      offsetY: 1
    },
    textBoxMaxWidth: 200,      // Ancho máximo del cuadro de texto
    disableMeasurement: false, // Evitar que se muestre la longitud de la flecha
    renderOutsideCanvas: false,// Permitir que la anotación se dibuje fuera del lienzo
  }
});

cornerstoneTools.addTool(EllipticalRoiTool);

cornerstoneTools.addTool(FreehandRoiTool);

cornerstoneTools.addTool(RectangleRoiTool, {
  name: 'RectangleRoi', // ¡Case-sensitive!
  configuration: {
    shadow: true,
    stroke: 'rgb(255, 0, 0)',
    fill: 'rgba(255, 0, 0, 0.2)',
    lineWidth: 2,
    getMeasurementLocation: () => 'top-left'
  }
});


let imageId = 'wadouri:' + "<?php echo $image_path; ?>";
let initialViewport;
let isMagnifyActive = false;
let isProbeActive = false; // Activado por defecto
let isPan = false; // DesActivado por defecto
let isRot = false; // DesActivado por defecto
let isAngle = false; // DesActivado por defecto
let isEraserActive = false; // DesActivado por defecto
let estaFlipeadaHorizontalmente = false;
let isWWCRT = false;
let isLenth = false;
let isArrow = false;
let isElip = false;
let isLapiz = false;
let isrectangle = false;
let currentImagePath = "";

cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
cornerstoneTools.setToolActive('ZoomMouseWheel', { mouseButtonMask: 0 });

// Función para configurar el cursor inicial

function disableAllTools() {
  cornerstoneTools.setToolDisabled('Pan');
  cornerstoneTools.setToolDisabled('RotateTool');
  cornerstoneTools.setToolDisabled('Magnify');
  
  cornerstoneTools.setToolDisabled('EraserTool');
  cornerstoneTools.setToolDisabled('WwwcRegion');
  
  
}

function setDefaultCursor() {
  const element = document.getElementById('dicomImage');
  
  // Establecer cursor personalizado (puedes usar una imagen SVG o PNG)
  const cursorSVG =  `url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="%23EEEEEE" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,12H8V10H12V6H14V10H18V12H14V16H12V12Z"/></svg>') 12 12, auto`;
  element.style.cursor = cursorSVG;
 
  // Activar Drag Probe Tool por defecto
}

function cursor() {
   if (!isProbeActive) {

    disableAllTools();
    // Activar magnify con configuración mejorada
    cornerstoneTools.setToolActive('Probe', { mouseButtonMask: 1 })

    } 
 else {
      // Reactivar herramientas normales
      cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });


      }
      isProbeActive = !isProbeActive;
  
}

function updateZoomLevel() {
  const viewport = cornerstone.getViewport(element);
  zoomLevelDisplay.textContent = (viewport.scale * 100).toFixed(0) + '%';
}

function setCornerstoneViewport(newViewport) {
  cornerstone.setViewport(element, newViewport);
  updateZoomLevel();
}

function adjustZoom(scaleFactor) {
  const viewport = cornerstone.getViewport(element);
  viewport.scale *= scaleFactor;
  setCornerstoneViewport(viewport);
}

function toggleMagnifyTool() {

  if (!isMagnifyActive) {

    disableAllTools();
    // Activar magnify con configuración mejorada
    cornerstoneTools.setToolActive('Magnify', {
      mouseButtonMask: 1,
      configuration: {
        
      }
    });
    
  } 
  else {
    // Reactivar herramientas normales
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
  
   
  }
  isMagnifyActive = !isMagnifyActive;
}
// Función para activar/desactivar la herramienta de magnificación
function pantool() {
  
  if (!isPan) {
    disableAllTools();
    cornerstoneTools.setToolActive('Pan', { mouseButtonMask: 1 });
    
  } 
  else {
    // Reactivar herramientas normales
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
  
   
  }
  isPan = !isPan;
}

function rotate() {
  
  if (!isRot) {
    // Desactivar herramientas que interfieren
    disableAllTools();
    // Activar pan con configuración mejorada
    cornerstoneTools.setToolActive('RotateTool', { mouseButtonMask: 1,});
    
  }
  else {
    // Reactivar herramientas normales
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
  
   
  }
  isRot = !isRot;
}


function angle() {
  
  if (!isAngle) {
    // Desactivar herramientas que interfieren
    disableAllTools();

    cornerstoneTools.setToolActive('AngleTool', { mouseButtonMask: 1 }); // Clic izquierdo
    
  } else {
    // Reactivar herramientas normales
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
  
   
  }
  isAngle = !isAngle;
}

function toggleEraser() {
  
  
  if (!isEraserActive) {
    // Desactivar otras herramientas que puedan interferir
   
    disableAllTools();
    
    // Activar el borrador con clic izquierdo
    cornerstoneTools.setToolActive('EraserTool', {  mouseButtonMask: 1  });
    
    // Cambiar el cursor a un icono de borrador
    element.style.cursor = `url('data:image/svg+xml;utf8,
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <path fill="red" d="M16.24,3.56L21.19,8.5C21.97,9.29 21.97,10.55 21.19,11.34L12,20.53C10.44,22.09 7.91,22.09 6.34,20.53L2.81,17C2.03,16.21 2.03,14.95 2.81,14.16L13.41,3.56C14.2,2.78 15.46,2.78 16.24,3.56M4.22,15.58L7.76,19.11C8.54,19.9 9.8,19.9 10.59,19.11L14.12,15.58L9.17,10.63L4.22,15.58Z"/>
    </svg>') 12 12, auto`;
    } else {
    // Desactivar el borrador
   
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
    
  
  }
  isEraserActive = !isEraserActive;
}

function flipHorizontal(element) {
    const viewport = cornerstone.getViewport(element);
    
    if (!estaFlipeadaHorizontalmente) {
        // Aplicar flip horizontal (si no estaba activado)
        viewport.hflip = true;
    } else {
        // Quitar el flip (si ya estaba activado)
        viewport.hflip = false;
    }
    
    cornerstone.setViewport(element, viewport);
    estaFlipeadaHorizontalmente = !estaFlipeadaHorizontalmente; // Cambiar estado después
}

function wwrtc() {
 
  if (!isWWCRT) {
    // Desactivar herramientas que interfieren
    disableAllTools();
    // Activar pan con configuración mejorada
    cornerstoneTools.setToolActive('WwwcRegion', { mouseButtonMask: 1 })
    
  } 
  else {
    // Desactivar el borrador
    
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
    
  
  }
  isWWCRT = !isWWCRT;
}

function length() {
 
  if (isLenth) {
    // Desactivar herramientas que interfieren
    disableAllTools();
    cornerstoneTools.setToolActive( 'Length', {
    mouseButtonMask: 1,
    color: 'yellow'
  });

    
  } else {
    // Reactivar herramientas normales
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
    
   
  }
  isLenth = !isLenth;
}

function arrow() {
 
 if (!isArrow) {
   // Desactivar herramientas que interfieren
   disableAllTools();
   cornerstoneTools.setToolActive('ArrowAnnotate', { mouseButtonMask: 1 });

   
 } else {
   // Reactivar herramientas normales
   cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
   
  
 }
 isArrow = !isArrow;
}

function elipsefunc() {
 
 if (!isElip) {
   // Desactivar herramientas que interfieren
   disableAllTools();
   cornerstoneTools.setToolActive('EllipticalRoi', { mouseButtonMask: 1 })

   
 } else {
   // Reactivar herramientas normales
   cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
   
  
 }
 isElip = !isElip;
}

function lapizfunc() {
 
 if (!isLapiz) {
   // Desactivar herramientas que interfieren
   disableAllTools();
   cornerstoneTools.setToolActive('FreehandRoi', { mouseButtonMask: 1 });

   
   } else {
   // Reactivar herramientas normales
   cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
     }
     isLapiz = !isLapiz;
}

function rectanglefunc() {
 
 if (!isrectangle) {
   // Desactivar herramientas que interfieren
   disableAllTools();
   cornerstoneTools.setToolActive('RectangleRoi', { mouseButtonMask: 1 });

   
 } else {
   // Reactivar herramientas normales
   cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 2 });
   
  
 }
 isrectangle = !isrectangle;
}

function capturarImagen(elementId = 'dicomImage') {
  const element = document.getElementById(elementId);
  if (!element) {
    console.error('Elemento no encontrado:', elementId);
    return;
  }

  // Asegúrate de que el canvas de Cornerstone exista
  const canvas = element.querySelector('canvas');
  if (!canvas) {
    console.error('No se encontró un canvas dentro del elemento:', elementId);
    return;
  }

  // Opcional: crear una imagen en base64
  const imagenBase64 = canvas.toDataURL('image/png');

  // Crear un enlace de descarga
  const enlace = document.createElement('a');
  enlace.href = imagenBase64;
  enlace.download = 'captura_dicom.png';
  document.body.appendChild(enlace);
  enlace.click();
  document.body.removeChild(enlace);
}


setDefaultCursor();
 // Event listeners para botones
 element.addEventListener('contextmenu', (e) => e.preventDefault());
 zoomInBtn.addEventListener('click', () => adjustZoom(1.25));
 zoomOutBtn.addEventListener('click', () => adjustZoom(0.8));
 resetBtn.addEventListener('click', () => cornerstone.reset(element));
 magnifyBtn.addEventListener('click', toggleMagnifyTool);
 panBtn.addEventListener('click', pantool);
 rotbtn.addEventListener('click', rotate);
 angbtn.addEventListener('click', angle);
 eraserbtn.addEventListener('click',  toggleEraser);
 punterobtn.addEventListener('click',  cursor);
 filpBtn.addEventListener('click',  () =>  flipHorizontal(element));
 wwcBtn.addEventListener('click',  wwrtc);
 lengthBtn.addEventListener('click',  length);
 arrowBtn.addEventListener('click',  arrow);
 elipBtn.addEventListener('click',  elipsefunc);
 lapizBtn.addEventListener('click',  lapizfunc);
 rectangleBtn.addEventListener('click',  rectanglefunc);

// Apply WW/WC
applyWWWCBtn.addEventListener('click', () => {
   const wwInput = document.getElementById('ww');
   const wcInput = document.getElementById('wc');
   if (wwInput && wcInput) {
     const wwValue = parseFloat(wwInput.value);
     const wcValue = parseFloat(wcInput.value);
     if (!isNaN(wwValue) && !isNaN(wcValue)) {
       const viewport = cornerstone.getViewport(element);
       viewport.voi.windowWidth = wwValue;
       viewport.voi.windowCenter = wcValue;
       setCornerstoneViewport(viewport);
     }
   }
});

 // Invert colors
invertBtn.addEventListener('click', () => {
   const viewport = cornerstone.getViewport(element);
   viewport.invert = !viewport.invert;
   setCornerstoneViewport(viewport);
});

function drawROIs(element, boundingBoxes) {
  if (!boundingBoxes || !boundingBoxes.length) return;

  // Desactivar interacción manual
 
  cornerstoneTools.setToolActive('RectangleRoi', { mouseButtonMask: 1 });

  // Limpiar ROIs existentes (usando nombre exacto)
  cornerstoneTools.clearToolState(element, 'RectangleRoi');

  // Configurar estilo (con nombre exacto)
 cornerstoneTools.setToolOptions('RectangleRoi', {
    toolStyle: {
      color: 'rgb(255, 0, 0)',
      lineWidth: 3,
      fillOpacity: 0.3
    }
 });


  // Dibujar cada ROI
  boundingBoxes.forEach((box) => {
    
    const measurementData = {
      toolType: 'RectangleRoi', // ¡Case-sensitive!
      visible: true,
      active: false,
      handles: {
        start: { x: parseFloat(box.xmin), y: parseFloat(box.ymin) },
        end: { x: parseFloat(box.xmax), y: parseFloat(box.ymax) },
        textBox: {
          hasMoved: false,
          movesIndependently: false,
          drawnIndependently: true,
          allowedOutsideImage: true, 
     }
    },
    visible: true,
    active: false,
    invalidated: true // 🔑 Forzar recálculo
};
    cornerstoneTools.addToolState(element, 'RectangleRoi', measurementData);
  });

  // Forzar actualización
  cornerstone.updateImage(element);
  cornerstone.reset(element);
  
  // Verificación en consola
  console.log('ROIs dibujados:', cornerstoneTools.getToolState(element, 'RectangleRoi'));
}

function loadDicomImage(imagePath) {
    let imageId = 'wadouri:' + imagePath;
    cornerstone.loadAndCacheImage(imageId).then(function(image) {
      cornerstone.displayImage(element, image);
      initialViewport = cornerstone.getViewport(element);
      updateZoomLevel();
    currentImagePath = imagePath; 
 // Deshabilitar menú contextual
    cornerstone.reset(element);

     readDicomFile(imagePath);
     enviarDensidad(imagePath);

    }).catch(err => {
      console.error('Error cargando la imagen: ', err);
      alert('Error al cargar la imagen DICOM.');
    });
}  


function enviarDeteccion() {
  
  document.getElementById('btn_ver').style.display = 'none'; // Asegurar que el botón esté oculto en caso de error
  document.getElementById('resultadoDeteccion').innerHTML = "";
  document.getElementById('imageModal').style.display = 'none';;
    const ruta_dcm = currentImagePath;
    const formData1 = new FormData();
    formData1.append('dcm_path1', ruta_dcm);
    console.log('ruta enviada:', ruta_dcm);
    fetch('det_lesion.php', {
        method: 'POST',
        body: formData1

       
    })
    .then(response1 => response1.json())
    .then(data1 => {
      console.log('datsos:', data1);
      if (data1.success) {
        const imgPath = data1.image_path;
        const boxes = data1.bounding_boxes;
        
        console.log('Imagen procesada:', imgPath);
        console.log('Bounding boxes:', boxes);
        document.getElementById('btn_ver').style.display = 'inline-flex';
     // Mostrar los bounding boxes
     
     let boxesHtml = '<div class="boxes-container">';
        boxes.forEach((box, index) => {
            boxesHtml += `
                <div class="box">
                    <h4  style="color: white;">Detección ${index + 1}</h4>
                    <ul>
                        <li style="color: white;"><strong>Xmin:</strong> ${box.xmin}</li>
                        <li style="color: white;"><strong>Ymin:</strong> ${box.ymin}</li>
                        <li style="color: white;"><strong>Xmax:</strong> ${box.xmax}</li>
                        <li style="color: white;"><strong>Ymax:</strong> ${box.ymax}</li>
                    </ul>
                </div>
            `;
        });
        boxesHtml += '</div>';
        
        document.getElementById('resultadoDeteccion').innerHTML = boxesHtml;
        
        const enabledElement = cornerstone.getEnabledElement(element);
            if (!enabledElement || !enabledElement.image) {
                console.warn('La imagen DICOM no está cargada en el visor');
                return;
            }
            
            
            // 3. Dibujar ROIs
            cornerstone.loadAndCacheImage('wadouri:' + currentImagePath).then(() => {  
             
      drawROIs(element, data1.bounding_boxes);
    
    });

        document.getElementById('btn_ver').addEventListener('click', function(event) {
    event.preventDefault(); 
      
        // Elementos del DOM
    const modal = document.getElementById('imageModal');
    const modalContent = document.getElementById('modalContent');
    const closeBtn = document.getElementById('closeBtn');
    const minimizeBtn = document.getElementById('minimizeBtn');
    const modalHeader = document.getElementById('modalHeader');

    // Variables para arrastrar el modal
    let isDragging = false;
    let offsetX, offsetY;

    // Mostrar modal al hacer clic en btn_ver

        modal.style.display = 'block';
        modal.classList.remove('minimized');
        
        // Crear elemento de imagen en lugar de mostrar solo el texto
        modalContent.innerHTML = ''; // Limpiar contenido previo
        const imgElement = document.createElement('img');
        imgElement.src = "../../../../../" + imgPath;
        imgElement.style.maxWidth = '100%';
        imgElement.style.height = 'auto';
        imgElement.alt = 'Imagen cargada';
        modalContent.appendChild(imgElement);


    // Cerrar modal
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Minimizar/restaurar modal
    minimizeBtn.addEventListener('click', () => {
        modal.classList.toggle('minimized');
    });

    // Funcionalidad para arrastrar el modal - solo cuando se hace clic en el header
    modalHeader.addEventListener('mousedown', (e) => {
        // Solo permitir arrastrar si se hace clic directamente en el header
        if (e.target === modalHeader || e.target === minimizeBtn || e.target === closeBtn) {
            isDragging = true;
            offsetX = e.clientX - modal.getBoundingClientRect().left;
            offsetY = e.clientY - modal.getBoundingClientRect().top;
            modal.style.cursor = 'grabbing';
            e.preventDefault(); // Evitar selección de texto accidental
        }
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        
        modal.style.left = (e.clientX - offsetX) + 'px';
        modal.style.top = (e.clientY - offsetY) + 'px';
        modal.style.right = 'auto';
        modal.style.bottom = 'auto';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        modal.style.cursor = 'default';
    });
        });
    }
    else {
            // Mostrar error
            document.getElementById('btn_ver').style.display = 'none'; // Asegurar que el botón esté oculto en caso de error
        }
        
      
    })
        .catch(error => {
            console.error('Error al enviar la solicitud de detccion:', error);
            document.getElementById('resultadoDeteccion').innerText = 'Error al realizar la deteccion.';
          
        });
}

function enviarPrediccion() {
    const rutaDCM = currentImagePath;
    const formData = new FormData();
  document.getElementById('btn_ver').style.display = 'none'; // Asegurar que el botón esté oculto en caso de error
  document.getElementById('resultadoDeteccion').innerHTML = "";
  document.getElementById('imageModal').style.display = 'none';;
    botonExtra.style.display = 'none'; // Asegurar que el botón esté oculto en caso de error
    document.getElementById('resultadoPrediccion').innerText = "";
    formData.append('dcm_path', rutaDCM);
    console.log('ruta enviada:', rutaDCM);
    fetch('pred_clasi.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log('Resultado de la predicción:', data);
        document.getElementById('resultadoPrediccion').innerText = data;

        // Verificar el resultado y habilitar el botón extra si es necesario
        const resultado = data.toLowerCase(); // Convertir a minúsculas para comparación insensible a mayúsculas
       

        if (resultado.includes('calcificacion') || resultado.includes('masa')) {
            botonExtra.style.display = 'inline-flex'; // Mostrar el botón extra
            // Aquí podrías agregar un event listener al botón extra para su funcionalidad
            botonExtra.addEventListener('click', function() {
              event.preventDefault(); // Evitar la acción por defecto del botón (submit)
             
              enviarDeteccion(); // Llamar a la función para enviar la ruta mediante AJAX
            
            });
        } else {
            botonExtra.style.display = 'none'; // Ocultar el botón extra si no es Calcificacion o Masa
            // Remover cualquier event listener anterior para evitar duplicados
           
        }
    })
    .catch(error => {
        console.error('Error al enviar la solicitud de predicción:', error);
        document.getElementById('resultadoPrediccion').innerText = 'Error al realizar la predicción.';
        document.getElementById('botonExtra').style.display = 'none'; // Asegurar que el botón esté oculto en caso de error
    });
}



  // Cargar la imagen inicial al cargar la página
if ("<?php echo $image_path; ?>" !== "") {
    loadDicomImage("<?php echo $image_path; ?>");
}
  
reloadButtons.forEach(button => {
    button.addEventListener('click', function() {
        const imagePath = this.getAttribute('data-image-path');

        fetch('actualizar_ruta.php?image_path=' + encodeURIComponent(imagePath))
            .then(response => response.json())
            .then(data => {
                console.log('Nuevo imagePath:', data.imagePath);
                loadDicomImage(data.imagePath); // Cargar la nueva imagen
            })
            .catch(error => {
                console.error('Error al actualizar la ruta:', error);
            });
    });
});

// Agregar un event listener al botón de predicción para enviar la ruta actual
document.getElementById('predecir').addEventListener('click', function(event) {
    event.preventDefault(); // Evitar la acción por defecto del botón (submit)
    enviarPrediccion(); // Llamar a la función para enviar la ruta mediante AJAX
});



 </script>



<script>
    // Obtener la ruta del archivo DICOM desde PHP
    const imagePath = "<?php echo $image_path; ?>"; // Ruta al archivo DICOM

    function formatDate(dateString) {
        if (!dateString || dateString.length !== 8) {
            return "N/A";
        }
        const year = dateString.substring(0, 4);
        const month = dateString.substring(4, 6);
        const day = dateString.substring(6, 8);
        return `${day}-${month}-${year}`;
    }

    // Función para leer el archivo DICOM y extraer los metadatos
  // Función para leer el archivo DICOM y extraer los metadatos
function readDicomFile(fileUrl) {
    fetch(fileUrl)
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => {
            const byteArray = new Uint8Array(arrayBuffer);

            try {
                const dataSet = dicomParser.parseDicom(byteArray);

                const patientName = getDicomValue(dataSet, 'x00100010') || 'N/A';
                const studyDate = getDicomValue(dataSet, 'x00080020') || 'N/A';
                const modality = getDicomValue(dataSet, 'x00080060') || 'N/A';
                const viewPosition = getDicomValue(dataSet, 'x00185101') || 'N/A'; // Tipo de corte

                const formattedDate = formatDate(studyDate);

                const patientNameElement = document.getElementById('patientName');
                const studyDateElement = document.getElementById('studyDate');
                const modalityElement = document.getElementById('modality');
                const viewPositionElement = document.getElementById('viewPosition');

                if (patientNameElement && studyDateElement && modalityElement) {
                    patientNameElement.textContent = ` ${patientName}`;
                    studyDateElement.textContent = ` ${formattedDate}`;
                    modalityElement.textContent = ` MODALIDAD: ${modality}`;
                } else {
                    console.error('No se encontraron los elementos p dentro de #dicomInfo');
                }

                if (viewPositionElement) {
                    viewPositionElement.textContent = ` TIPO DE CORTE: ${viewPosition}`;
                }

            } catch (e) {
                console.error('Error parsing DICOM file', e);
                const dicomInfo = document.getElementById('patientName'); // Usamos un elemento existente para mostrar el error
                if (dicomInfo) {
                    dicomInfo.textContent = 'Error al leer el archivo DICOM.';
                }
            }
        })
        .catch(error => {
            console.error('Error loading file', error);
            const dicomInfo = document.getElementById('patientName');
            if (dicomInfo) {
                dicomInfo.textContent = 'Error al cargar el archivo DICOM.';
            }
        });
}

  function getDicomValue(dataSet, tag) {
    const element = dataSet.elements[tag];
    if (element && element.dataOffset !== undefined) {
        try {
            switch (element.vr) {
                case 'PN':
                case 'LO':
                case 'SH':
                case 'ST':
                case 'LT':
                case 'UC':
                case 'UR':
                case 'CS':
                case 'UI':
                case 'IS':
                case 'DS':
                case 'DA':
                case 'TM':
                    return dicomParser.readFixedString(dataSet.byteArray, element.dataOffset, element.length).trim();
                default:
                    console.warn(`VR "${element.vr}" not handled for tag "${tag}"`);
                    return "N/A";
            }
        } catch (error) {
            console.error(`Error reading tag "${tag}":`, error);
            return "N/A";
        }
    } else {
        return "N/A";
    }
}

readDicomFile(imagePath);


</script>

<script>
    // Inyectamos desde PHP el valor estimado de densidad ACR


       function enviarDensidad(rutaDCM) {
    const formData = new FormData();
    formData.append('dcm_path2', rutaDCM);

    // Limpiar densidad anterior
    document.getElementById('acrDensity').innerText = "";

    fetch('densidad.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Resultado densidad:', data);
        const resultado = data.clasificacion || "N/A";

        if (resultado !== "N/A") {
            document.getElementById('acrDensity').textContent = `DENSIDAD: ACR ${resultado}`;
        }
    })
    .catch(error => {
        console.error('Error al obtener densidad:', error);
        document.getElementById('acrDensity').innerText = "Error al obtener densidad";
    });
}
 enviarDensidad(imagePath);
 

</script>

<script>
 
 function loadAndViewImage(elementId1, imagePath1) {
    const element1 = document.getElementById(elementId1);

    // Habilitar solo para visualización, sin herramientas
    cornerstone.enable(element1);

    // Cargar la imagen
    const imageId1 = `wadouri:${imagePath1}`;
    cornerstone.loadImage(imageId1).then(function(image1) {
        cornerstone.displayImage(element1, image1);

     
        element1.style.pointerEvents = 'none';    
        // Estilo del cursor: por si hay cambios
        element1.style.cursor = 'default';

         readDicomFile(imagePath1);

       


    }).catch(function(error) {
        console.error('Error al cargar la imagen:', error);
        element1.innerHTML = '<p style="color:red;">Error al cargar la imagen DICOM</p>';
    });
   }
 document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($dicomFiles as $index => $file): ?>
        loadAndViewImage(
            'dicomImage1<?php echo $index; ?>',
            '<?php echo  $dicomPath . '/' . urlencode($file); ?>'
        );
    <?php endforeach; ?>
 });
</script>



</body>
</html>