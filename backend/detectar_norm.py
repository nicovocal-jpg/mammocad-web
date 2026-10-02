import os
import sys
import json
import shutil
from PIL import Image, ImageDraw
import numpy as np
import pydicom
import cv2
from ultralytics import YOLO


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

        # Dibujar bounding boxes sobre la imagen original (no resized)
        draw = ImageDraw.Draw(image_pil_original)

        if results and results[0].boxes:
            boxes = results[0].boxes.xyxy.cpu().numpy()
            for box in boxes:
                xmin, ymin, xmax, ymax = box

                # Reescalar coords a tamaño original
                xmin_orig = int(xmin * orig_w / 640)
                xmax_orig = int(xmax * orig_w / 640)
                ymin_orig = int(ymin * orig_h / 640)
                ymax_orig = int(ymax * orig_h / 640)

                # Dibujar rectángulo en rojo
                draw.rectangle([xmin_orig, ymin_orig, xmax_orig, ymax_orig], outline='red', width=5)

                # Agregar al JSON
                output_data['bounding_boxes'].append({
                    'xmin': f"{xmin_orig}",
                    'ymin': f"{ymin_orig}",
                    'xmax': f"{xmax_orig}",
                    'ymax': f"{ymax_orig}"
                })

        # Guardar la imagen con ROIs dibujados
        output_path = os.path.join(output_folder, f"{base_name}_detected.jpg")
        image_pil_original.save(output_path)
        salida="detections/"+base_name+"_detected.jpg"

        # Actualizar ruta en output_data
        output_data['image_path'] = salida.replace('\\', '/')

        # Imprimir JSON
        print(json.dumps(output_data, indent=2))

    except Exception as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1)


if __name__ == "__main__":
    main()