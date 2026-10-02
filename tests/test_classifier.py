import os
import sys
import pytest
# Agrega el path raíz del proyecto al sys.path
sys.path.insert(0, os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))

from backend import predecir as ci  # ← Asegúrate de que esto sea correcto
# Ruta a una imagen DICOM válida para pruebas
TEST_DCM = "test_images/test_image.dcm"


def test_modelo_carga_correcta():
    """Verifica que el modelo se cargue correctamente"""
    assert ci.cargar_modelo() == True
    assert ci.model is not None

def test_conversion_dcm_a_jpg():
    """Verifica la conversión de DICOM a JPG temporal"""
    ruta_jpg = ci.transformar_dcm_a_jpg_temporal(TEST_DCM)
    assert ruta_jpg is not None, "No se generó el JPG temporal"
    assert os.path.exists(ruta_jpg), "El archivo JPG no existe"
    os.remove(ruta_jpg)  # Limpieza

def test_preprocesamiento_imagen():
    """Verifica que el preprocesamiento devuelva la imagen con forma correcta"""
    ruta_jpg = ci.transformar_dcm_a_jpg_temporal(TEST_DCM)
    imagen = ci.preprocesar_imagen(ruta_jpg)
    assert imagen is not None, "Imagen no preprocesada"
    assert imagen.shape == (1, 299, 299, 3), "Forma incorrecta de la imagen"
    os.remove(ruta_jpg)

def test_realizar_prediccion():
    """Verifica que la predicción funcione y devuelva una etiqueta válida"""
    cargado = ci.cargar_modelo()
    assert cargado, "El modelo no se cargó correctamente"

    ruta_jpg = ci.transformar_dcm_a_jpg_temporal(TEST_DCM)
    imagen = ci.preprocesar_imagen(ruta_jpg)
    resultado = ci.realizar_prediccion(imagen)
    assert resultado is not None, "No se generó ninguna predicción"
    assert resultado['label'] in ci.etiquetas_clases.values(), "Etiqueta no válida"
    os.remove(ruta_jpg)