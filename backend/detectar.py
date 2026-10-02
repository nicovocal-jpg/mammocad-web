import os
import sys
import json
import shutil
from PIL import Image, ImageDraw
import numpy as np
import pydicom
import cv2
from ultralytics import YOLO

def calculate_iou(box1, box2):
    x1 = max(box1['xmin'], box2['xmin'])
    y1 = max(box1['ymin'], box2['ymin'])
    x2 = min(box1['xmax'], box2['xmax'])
    y2 = min(box1['ymax'], box2['ymax'])

    intersection_area = max(0, x2 - x1) * max(0, y2 - y1)

    box1_area = (box1['xmax'] - box1['xmin']) * (box1['ymax'] - box1['ymin'])
    box2_area = (box2['xmax'] - box2['xmin']) * (box2['ymax'] - box2['ymin'])
    union_area = box1_area + box2_area - intersection_area
    return intersection_area / union_area if union_area > 0 else 0

def merge_connected_boxes(boxes, iou_threshold=0.3):
    num_boxes = len(boxes)
    if num_boxes == 0:
        return []

    adjacency_list = [[] for _ in range(num_boxes)]
    for i in range(num_boxes):
        for j in range(i + 1, num_boxes):
            if calculate_iou(boxes[i], boxes[j]) > iou_threshold:
                adjacency_list[i].append(j)
                adjacency_list[j].append(i)

    visited = [False] * num_boxes
    merged_boxes = []

    for i in range(num_boxes):
        if not visited[i]:
            component = []
            stack = [i]
            visited[i] = True
            while stack:
                u = stack.pop()
                component.append(boxes[u])
                for v in adjacency_list[u]:
                    if not visited[v]:
                        visited[v] = True
                        stack.append(v)

            if component:
                min_x = min(box['xmin'] for box in component)
                min_y = min(box['ymin'] for box in component)
                max_x = max(box['xmax'] for box in component)
                max_y = max(box['ymax'] for box in component)
                merged_boxes.append({
                    'xmin': min_x,
                    'ymin': min_y,
                    'xmax': max_x,
                    'ymax': max_y
                })

    return merged_boxes

def detect_microcalcifications(image_array, orig_h, orig_w):
    """Detecta microcalcificaciones utilizando PDI con operaciones morfológicas ajustadas."""
    if len(image_array.shape) == 3:
        gray = cv2.cvtColor(image_array, cv2.COLOR_RGB2GRAY)
    else:
        gray = image_array
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edges = cv2.Canny(blurred, 50, 150)
    kernel_erode = np.ones((1, 1), np.uint8)
    kernel_dilate = np.ones((2, 2), np.uint8)
    kernel_close = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
    kernel_open = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (2, 2))
    eroded = cv2.erode(edges, kernel_erode, iterations=1)
    dilated = cv2.dilate(eroded, kernel_dilate, iterations=1)
    closed = cv2.morphologyEx(dilated, cv2.MORPH_CLOSE, kernel_close, iterations=1)
    opened = cv2.morphologyEx(closed, cv2.MORPH_OPEN, kernel_open, iterations=1)
    contours, _ = cv2.findContours(opened, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    all_areas = [cv2.contourArea(cnt) for cnt in contours]
    if not all_areas:
        return []
    avg_area = np.mean(all_areas)
    min_area_threshold = 1.0 * avg_area
    max_area_threshold = 1.9 * avg_area
    min_circularity_threshold = 0.3
    microcalc_boxes = []
    expand_factor = 5
    for cnt in contours:
        area = cv2.contourArea(cnt)
        if min_area_threshold <= area <= max_area_threshold:
            perimeter = cv2.arcLength(cnt, True)
            if perimeter > 0:
                circularity = 4 * np.pi * area / (perimeter * perimeter)
                if circularity >= min_circularity_threshold:
                    x, y, w, h = cv2.boundingRect(cnt)
                    expand_w = int(w * (expand_factor - 1) / 2)
                    expand_h = int(h * (expand_factor - 1) / 2)
                    xmin = max(0, x - expand_w)
                    ymin = max(0, y - expand_h)
                    xmax = min(orig_w, x + w + expand_w)
                    ymax = min(orig_h, y + h + expand_h)
                    center_x = (xmin + xmax) // 2
                    center_y = (ymin + ymax) // 2
                    microcalc_boxes.append({
                        'xmin': xmin,
                        'ymin': ymin,
                        'xmax': xmax,
                        'ymax': ymax,
                        'center_x': center_x,
                        'center_y': center_y
                    })
    return microcalc_boxes

def draw_microcalcifications_pdi(draw, microcalc_boxes):
    """Dibuja las microcalcificaciones detectadas por PDI en la imagen."""
    for box in microcalc_boxes:
        xmin, ymin, xmax, ymax = box['xmin'], box['ymin'], box['xmax'], box['ymax']
        draw.rectangle([xmin, ymin, xmax, ymax], outline='lime', width=2) # Color verde lima para PDI

def main():
    try:
        os.environ['YOLO_VERBOSE'] = 'False'  # Silenciar logs

        script_dir = os.path.dirname(os.path.abspath(__file__))
        model_path = os.path.join(script_dir, '..', 'modelo', 'best.pt')
        output_folder = os.path.join(script_dir, '..', 'detections')

        # Cargar el modelo YOLO
        model = YOLO(model_path)

        # Verificar argumentos
        if len(sys.argv) < 2:
            raise ValueError("No se proporcionó la ruta de la imagen DICOM.")

        ruta_relativa_dcm_recibido = sys.argv[1]
        dcm_image_path = os.path.join(ruta_relativa_dcm_recibido)

        # Limpiar detections
        if os.path.exists(output_folder):
            shutil.rmtree(output_folder)
        os.makedirs(output_folder, exist_ok=True)

        # Leer DICOM
        dicom_image = pydicom.dcmread(dcm_image_path)
        pixel_array = dicom_image.pixel_array

        # Normalizar
        if pixel_array.dtype != np.uint8:
            pixel_array = ((pixel_array - pixel_array.min()) /
                           (pixel_array.max() - pixel_array.min()) * 255).astype(np.uint8)

        orig_h, orig_w = pixel_array.shape

        # Convertir a PIL y guardar copia original JPG (sin resize)
        image_pil_original = Image.fromarray(pixel_array).convert('RGB')
        base_name = os.path.basename(dcm_image_path).replace('.dcm', '')
        jpg_original_path = os.path.join(output_folder, f"{base_name}_original.jpg")
        image_pil_original.save(jpg_original_path)

        # Redimensionar a 640x640 para YOLO
        img_resized_pil = image_pil_original.resize((640, 640))
        img_resized_cv2 = cv2.cvtColor(np.array(img_resized_pil), cv2.COLOR_RGB2BGR)

        # Inferencia con YOLO
        results = model.predict(
            source=img_resized_cv2,
            imgsz=640,
            conf=0.1,
            iou=0.5,
            show=False,
            verbose=False
        )

        output_data = {
            'image_path': jpg_original_path.replace('\\', '/'),
            'bounding_boxes': []
        }

        # Procesar detecciones de YOLO
        if results and results[0].boxes:
            boxes = results[0].boxes.xyxy.cpu().numpy()
            for box in boxes:
                xmin, ymin, xmax, ymax = box
                xmin_orig = int(xmin * orig_w / 640)
                xmax_orig = int(xmax * orig_w / 640)
                ymin_orig = int(ymin * orig_h / 640)
                ymax_orig = int(ymax * orig_h / 640)
                output_data['bounding_boxes'].append({
                    'xmin': xmin_orig,
                    'ymin': ymin_orig,
                    'xmax': xmax_orig,
                    'ymax': ymax_orig
                })

        # Detección de microcalcificaciones con PDI
        microcalc_boxes_pdi = detect_microcalcifications(pixel_array, orig_h, orig_w)
        for box in microcalc_boxes_pdi:
            output_data['bounding_boxes'].append({
                'xmin': box['xmin'],
                'ymin': box['ymin'],
                'xmax': box['xmax'],
                'ymax': box['ymax']
            })

        # Fusionar bounding boxes conectados por superposición
        output_data['bounding_boxes'] = merge_connected_boxes(output_data['bounding_boxes'], iou_threshold=0.3)

        # Dibujar bounding boxes fusionados sobre la imagen original
        draw = ImageDraw.Draw(image_pil_original)
        for box in output_data['bounding_boxes']:
            xmin, ymin, xmax, ymax = int(box['xmin']), int(box['ymin']), int(box['xmax']), int(box['ymax'])
            draw.rectangle([xmin, ymin, xmax, ymax], outline='red', width=5)

        # Guardar la imagen con los bounding boxes fusionados
        output_path = os.path.join(output_folder, f"{base_name}_detected_merged_graph.jpg")
        image_pil_original.save(output_path)
        salida = "detections/" + base_name + "_detected_merged_graph.jpg"
        output_data['image_path'] = salida.replace('\\', '/')

        # Imprimir JSON
        print(json.dumps(output_data, indent=2))

    except Exception as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()