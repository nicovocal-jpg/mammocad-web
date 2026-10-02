import numpy as np
import pydicom
import cv2
import sys
import json
import logging

logging.basicConfig(level=logging.INFO, format='%(levelname)s: %(message)s')

CONFIG_PARAMS = {
    "umbral_a": 20,
    "umbral_b": 35,
    "umbral_c": 45,
    "ponderacion_mixto": 0.85,
    "percentil_adiposo": 30,
    "percentil_denso": 90,
    "gauss_blur_kernel": (9, 9),
    "adapt_thresh_block_size": 51,
    "adapt_thresh_C": 7,
    "morph_kernel_size_large": (35, 35),
    "morph_kernel_size_small": (5, 5),
    "min_contour_area": 5000
}

def clasificar_densidad_letra(porcentaje, config):
    if porcentaje < config["umbral_a"]:
        base_class = "Baja densidad"
    elif porcentaje < config["umbral_b"]:
        base_class = "Densidad moderada"
    elif porcentaje < config["umbral_c"]:
        base_class = "Heterogeneamente densa"
    else:
        base_class = "Extremadamente Densa"

    # Eliminamos la aleatoriedad
    ajustada = False  

    return base_class, ajustada

def transformar_como_cornerstone(path):
    dicom = pydicom.dcmread(path)
    img = dicom.pixel_array.astype(np.float32)
    img = img * float(dicom.get('RescaleSlope', 1.0)) + float(dicom.get('RescaleIntercept', 0.0))

    center = dicom.get('WindowCenter', None)
    width = dicom.get('WindowWidth', None)
    if center and width:
        if isinstance(center, (list, pydicom.multival.MultiValue)):
            center = float(center[0])
            width = float(width[0])
        vmin, vmax = center - width / 2, center + width / 2
        img = np.clip(img, vmin, vmax)
    return img

def segmentar_mama(img, config):
    p_low_seg, p_high_seg = np.percentile(img, (1, 99))
    img_seg = np.clip(img, p_low_seg, p_high_seg)
    img_seg = ((img_seg - p_low_seg) / (p_high_seg - p_low_seg + 1e-5)) * 255.0
    img_seg = img_seg.astype(np.uint8)
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
    img_seg = clahe.apply(img_seg)

    blur = cv2.GaussianBlur(img_seg, config["gauss_blur_kernel"], 0)
    binary = cv2.adaptiveThreshold(blur, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
                                   cv2.THRESH_BINARY_INV, config["adapt_thresh_block_size"], config["adapt_thresh_C"])
    kernel_large = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, config["morph_kernel_size_large"])
    binary = cv2.morphologyEx(binary, cv2.MORPH_CLOSE, kernel_large)
    binary = cv2.morphologyEx(binary, cv2.MORPH_OPEN, kernel_large)

    contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    mask = np.zeros_like(img_seg, dtype=np.uint8)
    valid = [c for c in contours if cv2.contourArea(c) > config["min_contour_area"]]
    if valid:
        largest = max(valid, key=cv2.contourArea)
        cv2.drawContours(mask, [largest], -1, 255, thickness=-1)
        kernel_small = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, config["morph_kernel_size_small"])
        mask = cv2.erode(mask, kernel_small, iterations=1)
        mask = cv2.dilate(mask, kernel_small, iterations=2)
    return mask

def calcular_densidad(path, config):
    try:
        img = transformar_como_cornerstone(path)
        mask_mama = segmentar_mama(img, config)
        if np.count_nonzero(mask_mama) == 0:
            raise ValueError("No se pudo segmentar la mama.")

        pixels_mama = img[mask_mama > 0]
        if pixels_mama.size == 0:
            raise ValueError("La región segmentada está vacía.")

        min_val, max_val = pixels_mama.min(), pixels_mama.max()
        norm = np.zeros_like(img, dtype=np.uint8)
        norm[mask_mama > 0] = np.clip(((pixels_mama - min_val) / (max_val - min_val)) * 255.0, 0, 255).astype(np.uint8)

        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
        norm = clahe.apply(norm)

        p_low = np.percentile(norm[mask_mama > 0], config["percentil_adiposo"])
        p_high = np.percentile(norm[mask_mama > 0], config["percentil_denso"])

        _, mask_adiposo = cv2.threshold(norm, p_low, 255, cv2.THRESH_BINARY)
        _, mask_denso = cv2.threshold(norm, p_high, 255, cv2.THRESH_BINARY)
        mask_adiposo = cv2.bitwise_and(mask_adiposo, mask_mama)
        mask_denso = cv2.bitwise_and(mask_denso, mask_mama)

        mask_mixto = cv2.bitwise_and(mask_mama, cv2.bitwise_not(cv2.bitwise_or(mask_adiposo, mask_denso)))

        area_total = np.count_nonzero(mask_mama)
        area_denso = np.count_nonzero(mask_denso)
        area_mixto = np.count_nonzero(mask_mixto)

        porcentaje = ((area_denso + config["ponderacion_mixto"] * area_mixto) / area_total) * 100
        letra, ajustada = clasificar_densidad_letra(porcentaje, config)

        print(json.dumps({
            'clasificacion': letra,
            'porcentaje_densidad': round(porcentaje, 2),
            'ajustada': ajustada
        }))
    except Exception as e:
        logging.error(f"Error en calcular_densidad: {e}", exc_info=True)
        print(json.dumps({'error': str(e)}))

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'Falta la ruta del archivo DICOM. Uso: python script.py <ruta_dicom>'}))
    else:
        calcular_densidad(sys.argv[1], CONFIG_PARAMS)
