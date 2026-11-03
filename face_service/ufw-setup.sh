#!/bin/bash
# UFW Firewall Setup for HRMS Face Recognition Sidecar VPS
# Run as root on the VPS

set -e

echo "=== HRMS Face Service — UFW Firewall Setup ==="

# Enable UFW
ufw --force enable

# Default policies: deny incoming, allow outgoing
ufw default deny incoming
ufw default allow outgoing

# Allow SSH (essential — DO NOT remove or you'll be locked out)
ufw allow 22/tcp comment "SSH"

# Allow HTTPS (for nginx reverse proxy — only if PHP hosting is on a DIFFERENT host)
# CHANGE 1.2.3.4 to the actual IP of your PHP hosting server
# ufw allow from 1.2.3.4 to any port 443 proto tcp comment "HRMS PHP hosting HTTPS"

# If PHP hosting and sidecar are on the SAME host:
# No firewall rule needed — service binds to 127.0.0.1 only

# Block direct access to port 8000 from outside (uvicorn)
# This is already blocked by default deny, but be explicit
ufw deny 8000/tcp comment "Block direct access to face service"

# Show status
ufw status verbose

echo ""
echo "=== IMPORTANT ==="
echo "1. If PHP hosting is on a DIFFERENT host, uncomment the 'ufw allow from' line above"
echo "   and replace 1.2.3.4 with the actual PHP hosting IP."
echo "2. If PHP hosting is on the SAME host, no additional rules needed."
echo "3. Port 8000 is NEVER exposed to the internet."
echo ""
