FROM python:3.11-slim

# System dependencies for OpenCV headless
RUN apt-get update && apt-get install -y --no-install-recommends \
    libgl1-mesa-glx \
    libglib2.0-0 \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Install Python dependencies first (layer caching)
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

# Create models directory for anti-spoofing ONNX model
RUN mkdir -p /app/models

# Non-root user for security
RUN useradd -m -r faceservice && chown -R faceservice:faceservice /app
USER faceservice

EXPOSE 8000

# Bind to 0.0.0.0 inside container; reverse proxy handles access control
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "1", "--log-level", "info"]
