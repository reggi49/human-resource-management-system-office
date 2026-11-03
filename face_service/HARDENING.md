# VPS Hardening Guide — HRMS Face Recognition Sidecar

## 1. Disable Password SSH Login

```bash
sudo nano /etc/ssh/sshd_config
```

Set:
```
PasswordAuthentication no
PermitRootLogin prohibit-password
PubkeyAuthentication yes
```

```bash
sudo systemctl restart sshd
```

> ⚠️ PASTIKAN SSH key sudah terpasang sebelum disable password login.

## 2. Install & Configure fail2ban

```bash
sudo apt install fail2ban -y
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo nano /etc/fail2ban/jail.local
```

Set di `[sshd]`:
```ini
[sshd]
enabled = true
port = 22
filter = sshd
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600
findtime = 600
```

```bash
sudo systemctl enable fail2ban
sudo systemctl restart fail2ban
```

## 3. Setup Dedicated Service User

```bash
sudo useradd -r -m -s /usr/sbin/nologin faceservice
sudo mkdir -p /opt/hrms-face-service
sudo chown faceservice:faceservice /opt/hrms-face-service
```

## 4. Deploy Face Service

```bash
# Option A: Direct (recommended for single VPS)
cd /opt/hrms-face-service
sudo -u faceservice python3 -m venv venv
sudo -u faceservice venv/bin/pip install -r requirements.txt

# Copy .env
sudo cp .env.example .env
sudo nano .env  # Set FACE_SECRET and other values
sudo chown faceservice:faceservice .env
sudo chmod 600 .env

# Install systemd service
sudo cp face_service.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable face_service
sudo systemctl start face_service
sudo systemctl status face_service

# Option B: Docker
docker compose up -d
```

## 5. SSL Certificate (if PHP hosting is on different host)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d face.yourdomain.com
```

## 6. Nginx Reverse Proxy (if needed)

```bash
sudo cp nginx-face-service.conf /etc/nginx/sites-available/face-service
sudo ln -s /etc/nginx/sites-available/face-service /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 7. UFW Firewall

```bash
sudo bash ufw-setup.sh
```

## 8. Automatic Security Updates

```bash
sudo apt install unattended-upgrades -y
sudo dpkg-reconfigure -plow unattended-upgrades
```

## 9. Monitor

```bash
# Check service status
sudo systemctl status face_service

# View logs
sudo journalctl -u face_service -f

# Check model health
curl http://127.0.0.1:8000/health
```

## 10. Backup

Model weights are downloaded from InsightFace model zoo on first run.
The ONNX anti-spoofing model file must be placed in `/opt/hrms-face-service/models/anti_spoof.onnx`.
No persistent state to back up — all data lives in the PHP hosting MySQL.
