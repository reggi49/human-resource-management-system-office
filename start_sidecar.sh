#!/bin/bash
# ══════════════════════════════════════════════════════════════════════════════
# Start HRMS Face Recognition AI Sidecar (InsightFace buffalo_l)
# ══════════════════════════════════════════════════════════════════════════════
cd "$(dirname "$0")"

# Load .env if present
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

export FACE_SECRET="${FACE_SECRET:-dev-secret-hrms-local-2026}"
export FACE_SERVICE_PORT="${FACE_SERVICE_PORT:-8000}"

echo "Starting Face AI Sidecar on http://127.0.0.1:${FACE_SERVICE_PORT}..."
exec /opt/miniconda3/bin/python3 -m uvicorn face_service.main:app --host 127.0.0.1 --port "${FACE_SERVICE_PORT}"
