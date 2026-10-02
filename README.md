# MammoCAD Web: DICOM Viewer + AI-Assisted Mammography Analysis

**Web platform that loads mammograms in DICOM format, shows them in a diagnostic viewer, and runs machine learning models to classify, detect and estimate breast density.**

Undergraduate thesis project in Biomedical Engineering, Universidad Privada del Valle (UNIVALLE), Bolivia.

![PHP](https://img.shields.io/badge/PHP-MySQL-777BB4?logo=php&logoColor=white)
![Python](https://img.shields.io/badge/Python-3.10-3776AB?logo=python&logoColor=white)
![TensorFlow](https://img.shields.io/badge/TensorFlow-InceptionV3-FF6F00?logo=tensorflow&logoColor=white)
![YOLOv8](https://img.shields.io/badge/Ultralytics-YOLOv8-111F68)
![DICOM](https://img.shields.io/badge/DICOM-pydicom_%7C_Cornerstone.js-0A7BBB)

<!-- Add screenshots here: login, patient list, viewer with detections -->
<!-- ![Viewer](docs/viewer.png) -->

---

## Features

| Module | What it does | Implementation |
|---|---|---|
| **DICOM viewer** | Zoom, pan, rotate, magnifier, probe, angle, window/level by region | Cornerstone.js + cornerstoneTools + WADO image loader |
| **Study upload** | Reads DICOM tags (patient, StudyInstanceUID, SeriesInstanceUID, SOPInstanceUID) and links each study to a patient and a physician; rejects duplicate SOP UIDs | PHP + PDO prepared statements |
| **Classification** | `normal` / `mass` / `calcification` with a confidence score | InceptionV3 fine-tuned (`backend/predecir.py`) |
| **Lesion detection** | Bounding boxes from YOLOv8 combined with a classical image-processing detector for microcalcifications (Canny + morphology + circularity filter); overlapping boxes are merged with an IoU graph | `backend/detectar.py` |
| **Breast density** | Breast segmentation (CLAHE + adaptive threshold + morphology) and density estimation by percentile thresholds, mapped to 4 categories similar to BI-RADS | `backend/densidad_mama.py` |
| **Roles** | Administrator, Operator (physician) and Service (support) dashboards | PHP sessions, bcrypt (`password_hash` / `password_verify`) |

## Architecture

```
Browser (Bootstrap + Cornerstone.js)
        │  upload .dcm / request analysis
        ▼
PHP (XAMPP) ── PDO ──► MySQL / MariaDB  (patients, studies, users)
        │
        │ shell_exec + escapeshellarg
        ▼
Python backend
  ├── predecir.py        InceptionV3  → class + %
  ├── detectar.py        YOLOv8 + image processing → boxes (JSON) + annotated image
  └── densidad_mama.py   segmentation → density % + category
```

Each Python script reads a DICOM path and prints JSON, which PHP returns to the viewer.

## Tech stack

**AI / Imaging:** Python, TensorFlow/Keras, PyTorch (Ultralytics YOLOv8), OpenCV, pydicom, NumPy, Pillow
**Web:** PHP, MySQL/MariaDB, JavaScript, Bootstrap ([PolluxUI](https://github.com/BootstrapDash) MIT template), Cornerstone.js
**Testing:** pytest (`tests/test_classifier.py`: model loading, DICOM to JPG conversion, preprocessing, prediction)

## Setup (local, XAMPP)

1. Clone into `htdocs/Proyecto3` (the app uses absolute paths starting with `/Proyecto3/`).
2. Create the database `php_login_database` and import:
   ```
   database/schema.sql
   database/seed_demo.sql      # demo users, password: demo1234
   ```
3. Python environment:
   ```bash
   pip install -r requirements-backend.txt
   ```
4. Download the model weights from the **[Releases](../../releases)** page and place them in `modelo/`:
   - `modelo/inception_v3_final_tuning_model2.h5`
   - `modelo/best.pt`
5. Open `http://localhost/Proyecto3/frontend/Inicio/template/pages/samples/login.php`.

Demo logins: `admin` (Administrator), `medico@demo.com` (Operator), `soporte` (Service).

## Tests

```bash
# place any de-identified mammography DICOM at test_images/test_image.dcm
pytest tests/
```

## Known limitations / next steps

These are the next things I would improve before using it outside a lab:

- **Access control:** only the login page checks the session. Every dashboard page should check the session and the user's role.
- **File paths from the client:** analysis endpoints receive a DICOM path from the client. They should receive a study ID and resolve the path on the server.
- **Configuration:** DB credentials are hard-coded (XAMPP defaults). They should move to a `.env` file.
- **Inference speed:** each request starts a new Python process. A FastAPI service that keeps the models loaded would respond much faster.
- **Model validation:** the classifier was later re-evaluated with a patient-level split. See [mammo-cad-hybrid](https://github.com/nicovocal-jpg/mammo-cad-hybrid).
- **Interoperability:** add DICOMweb or PACS integration (Orthanc) instead of storing files on disk.
- **Portability:** some includes use `Index.php` (capital I), so the app only runs on Windows/XAMPP.

> ⚠️ Academic prototype. This is not a medical device and must not be used for clinical diagnosis. No patient data is included in this repository.

## Author

**Nicolás Villarroel Vocal**, Biomedical Engineer
[GitHub](https://github.com/nicovocal-jpg)
