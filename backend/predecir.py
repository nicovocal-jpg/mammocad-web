import os
from PIL import Image
import numpy as np
import pydicom
import tempfile
import sys  # Para leer argumentos de la línea de comandos

# Definir la ruta al modelo
MODEL_PATH = os.path.join(os.path.dirname(__file__), '..', 'modelo', 'inception_v3_final_tuning_model2.h5')

# Variable para almacenar el modelo cargado
model = None

# Define tus etiquetas de clases
etiquetas_clases = {0: 'Calcificacion', 1: 'Normal', 2: 'Masa'}

def cargar_modelo():
    global model
    try:
        from tensorflow import keras
        model = keras.models.load_model(MODEL_PATH)
       
        return True
    except Exception as e:
        print(f"MODEL_LOAD_ERROR: {e}") # Marcador para PHP
        return False

def transformar_dcm_a_jpg_temporal(ruta_dcm):
    try:
        ds = pydicom.dcmread(ruta_dcm)
        if 'PixelData' in ds:
            # Obtener el array de píxeles
            pixel_array = ds.pixel_array
            
            # Normalizar los valores de 16 bits a 8 bits
            if pixel_array.dtype == np.uint16:
                pixel_array = ((pixel_array - pixel_array.min()) * (255.0 / (pixel_array.max() - pixel_array.min()))).astype(np.uint8)
            
            # Crear la imagen y convertir a RGB (JPEG no soporta escala de grises directamente)
            imagen = Image.fromarray(pixel_array).convert('RGB')
            
            with tempfile.NamedTemporaryFile(suffix=".jpg", delete=False) as tmp_file:
                ruta_jpg_temporal = tmp_file.name
                imagen.save(ruta_jpg_temporal, quality=95)  # quality opcional para controlar compresión
                
                return ruta_jpg_temporal
        else:
            print(f"DCM_ERROR: No PixelData in {ruta_dcm}") # Marcador para PHP
            return None
    except Exception as e:
        print(f"DCM_TRANSFORM_ERROR: {e}") # Marcador para PHP
        return None

def preprocesar_imagen(ruta_jpg):
    try:
        img = Image.open(ruta_jpg).resize((299, 299)).convert('RGB')
        img_array = np.array(img).astype('float32')
        img_array /= 255.0
        img_array = np.expand_dims(img_array, axis=0)
        return img_array
    except Exception as e:
        print(f"PREPROCESS_ERROR: {e}") # Marcador para PHP
        return None

def realizar_prediccion(imagen_preprocesada):
    global model
    if model is None:
        print("MODEL_NOT_LOADED") # Marcador para PHP
        return None
    try:
        predictions = model.predict(imagen_preprocesada)
        predicted_index = np.argmax(predictions[0])
        probability_percentage = predictions[0][predicted_index] * 100
        label = etiquetas_clases.get(predicted_index, f'Clase desconocida {predicted_index}')
        print(f"{label}:{probability_percentage:.2f}%") # Marcador para PHP
        return {'label': label, 'probability_percentage': float(probability_percentage)}
    except Exception as e:
        print(f"PREDICTION_ERROR: {e}") # Marcador para PHP
        return None

if __name__ == '__main__':
    if len(sys.argv) > 1:
        ruta_relativa_dcm_recibido = sys.argv[1]
        ruta_completa_dcm_simulado = os.path.join( ruta_relativa_dcm_recibido)

        if cargar_modelo():
            if os.path.exists(ruta_completa_dcm_simulado):
                ruta_jpg_temporal = transformar_dcm_a_jpg_temporal(ruta_completa_dcm_simulado)
                if ruta_jpg_temporal:
                    imagen_preprocesada = preprocesar_imagen(ruta_jpg_temporal)
                    if imagen_preprocesada is not None:
                        resultado = realizar_prediccion(imagen_preprocesada)
                        if resultado:
                            pass # La salida ya se hizo en realizar_prediccion
                        try:
                            os.remove(ruta_jpg_temporal)
                        except Exception as e:
                            print(f"TEMP_JPG_DELETE_ERROR: {e}") # Marcador para PHP
                    else:
                        print("PREPROCESS_FAILED") # Marcador para PHP
                else:
                    print("DCM_TO_JPG_FAILED") # Marcador para PHP
            else:
                print(f"DCM_NOT_FOUND:{ruta_completa_dcm_simulado}") # Marcador para PHP
    else:
        print("ERROR: No se proporcionó la ruta al archivo DCM.") # Marcador para PHP