# WordPress Docker Development Environment

## 📖 Overview
This repository contains a **Dockerized WordPress** setup ready for local development and testing. All services (WordPress, MySQL, phpMyAdmin, etc.) are orchestrated via **Docker Compose**, providing an isolated environment that mirrors a production stack.

---

## 🛠 Prerequisites
- **Docker Engine** (>= 20.10)
- **Docker Compose** (v2 plugin or standalone) – `docker compose` command
- Optional: **Git** for version control

---

## 🚀 Quick Start
1. **Clone the repository** (including the theme submodule):
   ```bash
   git clone --recursive <your-repo-url>
   cd wordpress-docker-wa-order
   ```
   *If you already cloned without `--recursive`, run this inside the folder:*
   ```bash
   git submodule update --init --recursive
   ```

### 🔄 Updating Submodules
Jika ada pembaruan pada theme (submodule), Anda dapat menarik perubahan terbaru dengan perintah:
```bash
git submodule update --remote --merge
```
Atau jika ingin menarik perubahan di project utama sekaligus semua submodule:
```bash
git pull --recurse-submodules
```

2. **Start the containers**:
   ```bash
   docker compose up -d
   ```
   This will pull the required images and launch:
   - `wordpress` (PHP 8, Apache)
   - `db` (MySQL)
   - `phpmyadmin` (optional UI for DB)
3. **Initial WordPress setup**:
   - Open your browser and navigate to **http://localhost**.
   - Follow the on‑screen installer (site title, admin user, password, etc.).
   - Use the following DB credentials (default values from `docker-compose.yml`):
     - **Database Host**: `db`
     - **Database Name**: `wordpress`
     - **User**: `wordpress`
     - **Password**: `wordpress`
4. **Access phpMyAdmin** (optional):
   - Visit **http://localhost:8080**.
   - Login with the same DB credentials above.

---

## 🛑 Stopping & Cleaning Up
- **Stop containers** (keep data):
  ```bash
  docker compose stop
  ```
- **Remove containers & networks** (data persists in the `db_data` volume):
  ```bash
  docker compose down
  ```
- **Full reset** (remove volumes & start fresh):
  ```bash
  docker compose down -v
  docker compose up -d
  ```

---

## 📂 Project Structure
```
.
├─ docker-compose.yml      # Docker Compose configuration
├─ wp-content/             # Custom themes/plugins
├─ custom-url.php          # Custom URL handling script
├─ wp-config.php           # WordPress configuration (generated on first run)
└─ README.md               # This file
```

---

## ⚙️ Configuration
You can adjust the environment variables in **docker-compose.yml** to suit your needs (e.g., change MySQL root password, expose different ports, enable Xdebug, etc.). After any change, recreate the containers:
```bash
docker compose up -d --force-recreate
```

---

## 📚 Helpful Commands
| Action | Command |
|--------|---------|
| View logs | `docker compose logs -f` |
| Enter WordPress container | `docker compose exec wordpress bash` |
| Enter DB container | `docker compose exec db bash` |
| Run WP‑CLI | `docker compose exec wordpress wp <command>` |

---

## 🤝 Contributing
Feel free to fork the repo, make improvements, and submit a pull request. Ensure you follow the existing coding style and update this README if you add new services or scripts.

---

## 📜 License
This project is licensed under the **MIT License** – see the `LICENSE` file for details.
