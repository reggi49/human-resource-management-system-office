#!/opt/miniconda3/bin/python3
"""
PDF Page Renderer - Renders each PDF page as a separate high-res PNG image.
Usage: pdf_stitcher.py <pdf_path> <output_prefix>
Output: Saves <prefix>_page_1.png, <prefix>_page_2.png, etc. Prints SUCCESS:<count>.
"""
import sys, os, io
import fitz
from PIL import Image

if len(sys.argv) < 3:
    print("Usage: pdf_stitcher.py <pdf_path> <output_prefix>")
    sys.exit(1)

pdf_path = sys.argv[1]
output_prefix = sys.argv[2]

if not os.path.exists(pdf_path):
    print("PDF file not found")
    sys.exit(1)

try:
    doc = fitz.open(pdf_path)
    page_count = 0

    for i, page in enumerate(doc):
        # 300 DPI for maximum text clarity on small receipts/cards
        pix = page.get_pixmap(dpi=300)
        img = Image.open(io.BytesIO(pix.tobytes('png')))

        # Save as PNG (lossless) for maximum OCR accuracy
        output_path = f"{output_prefix}_page_{i+1}.png"
        img.save(output_path, 'PNG')
        page_count += 1

    if page_count == 0:
        print("No pages rendered")
        sys.exit(1)

    print(f"SUCCESS:{page_count}")
except Exception as e:
    print("ERROR:" + str(e))
    sys.exit(1)
