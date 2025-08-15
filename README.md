# 📚 Book Store - Laravel 12

Proyek ini menggunakan **Repository Pattern** di Laravel, memisahkan logika menjadi:
- **Interface** → kontrak repository
- **Repository** → query & akses data
- **Service** → logika bisnis
- **Controller** → mengatur request/response
- **View** (Blade + Vite) → tampilan UI

---

## 📦 Requirement
- PHP 8.3+
- Laravel 12
- MySQL 
- Composer
- Node.js + NPM (untuk Vite)

---

## 🚀 Instalasi & Menjalankan Aplikasi
Jalankan perintah berikut secara berurutan di terminal:

```bash
# 1. Clone repository & masuk folder project
git clone https://github.com/username/nama-project.git
cd nama-project

# 2. Install dependency PHP & NPM
composer install
npm install

# 3. Copy file .env dan set konfigurasi database
cp .env.example .env
# lalu edit file .env sesuai database
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nama_db
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Generate key Laravel
php artisan key:generate

# 5. Migrasi database + seeder 
php artisan migrate --seed

# 6. Jalankan Vite (untuk compile asset)
npm run dev

# 7. Jalankan server Laravel
php artisan serve
