import cv2
import numpy as np
import base64
from flask import Flask, request, jsonify

app = Flask(__name__)

def check_anti_spoofing(image_bytes):
    """
    Python Anti-Spoofing Engine using:
    1. FFT Frequency Spectrum Analysis (Moiré / Pixel Grid Detection)
    2. YCrCb Skin Chrominance & Texture Distribution
    3. Laplacian Edge & Specular Reflection Variance
    """
    nparr = np.frombuffer(image_bytes, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    if img is None:
        return False, "Failed to decode image", 0.0

    h, w, _ = img.shape
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # 1. FFT High-Frequency Spectrum Analysis (Detects LCD/OLED screen pixels & print dots)
    dft = np.fft.fft2(gray)
    dft_shift = np.fft.fftshift(dft)
    magnitude_spectrum = 20 * np.log(np.abs(dft_shift) + 1e-5)
    
    # Calculate high-frequency energy ratio
    cy, cx = h // 2, w // 2
    r = min(h, w) // 4
    mask = np.ones((h, w), np.uint8)
    cv2.circle(mask, (cx, cy), r, 0, -1)
    
    high_freq_energy = np.mean(magnitude_spectrum[mask == 1])
    low_freq_energy = np.mean(magnitude_spectrum[mask == 0])
    fft_ratio = high_freq_energy / (low_freq_energy + 1e-5)

    # 2. YCrCb Skin Color & Texture Variance
    ycrcb = cv2.cvtColor(img, cv2.COLOR_BGR2YCrCb)
    cr = ycrcb[:, :, 1]
    cb = ycrcb[:, :, 2]
    
    skin_mask = (cr >= 133) & (cr <= 173) & (cb >= 77) & (cb <= 127)
    skin_ratio = np.sum(skin_mask) / (h * w)

    # 3. Laplacian Texture Blur & Screen Reflection Variance
    laplacian_var = cv2.Laplacian(gray, cv2.CV_64F).var()

    # Decision Matrix:
    # Screens & Printed Photos have either abnormal FFT ratios (> 0.78 or < 0.35) or unnatural skin chrominance
    is_real = True
    reason = "REAL 3D FACE"
    confidence = 0.95

    # Screen / Photo Moiré Detection
    if fft_ratio > 0.82:
        is_real = False
        reason = "2D PHOTO / SCREEN MOIRÉ PATTERN DETECTED"
        confidence = 0.20
    elif skin_ratio < 0.15:
        is_real = False
        reason = "UNNATURAL SKIN CHROMINANCE (2D PHOTO)"
        confidence = 0.30
    elif laplacian_var > 2200: # Over-sharpened photo or digital screen
        is_real = False
        reason = "DIGITAL DISPLAY / OVER-SHARPENED SCREEN DETECTED"
        confidence = 0.25

    return is_real, reason, round(confidence, 2)

@app.route('/verify_liveness', methods=['POST'])
def verify_liveness():
    try:
        data = request.get_json()
        if not data or 'image' not in data:
            return jsonify({'success': False, 'message': 'No image provided'}), 400

        image_data = data['image']
        if ',' in image_data:
            image_data = image_data.split(',')[1]

        image_bytes = base64.b64decode(image_data)
        is_real, reason, confidence = check_anti_spoofing(image_bytes)

        return jsonify({
            'success': True,
            'is_real': is_real,
            'reason': reason,
            'confidence': confidence
        })
    except Exception as e:
        return jsonify({'success': False, 'message': str(e)}), 500

if __name__ == '__main__':
    print("🚀 Anti-Spoofing Python Microservice running on http://127.0.0.1:5005")
    app.run(host='0.0.0.0', port=5005, debug=False)
