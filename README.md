# CloneX - Aplikasi Artikel Mobile

CloneX adalah aplikasi mobile berbasis Ionic Vue yang memungkinkan pengguna untuk membaca, membuat, dan berbagi artikel seputar pasar saham. Aplikasi ini menggunakan Vue 3 dengan Ionic Framework untuk frontend dan PHP dengan MySQL untuk backend.

## 📋 Deskripsi Proyek

Proyek ini merupakan aplikasi mobile yang menyediakan fitur-fitur berikut:

- **Autentikasi Pengguna**: Login dan register untuk mengakses aplikasi
- **Kelola Artikel**: Membuat, membaca, dan melihat artikel seputar pasar saham
- **Detail Artikel**: Melihat konten artikel secara lengkap
- **Antarmuka Modern**: Menggunakan Ionic components dengan tema dark mode

### Teknologi yang Digunakan

**Frontend:**
- Vue 3 (Composition API)
- Ionic Framework 8
- TypeScript
- Vite (Build tool)
- Vue Router
- Axios (HTTP client)

**Backend:**
- PHP (REST API)
- MySQL Database

## 🚀 Cara Menjalankan Proyek

### Prasyarat

Pastikan Anda telah menginstal:
- Node.js (versi 16 atau lebih baru)
- npm atau yarn
- XAMPP/WAMP/MAMP (untuk PHP dan MySQL)
- Git

### Langkah 1: Clone Repository

```bash
git clone https://github.com/Individeveloper/cloneX.git
cd cloneX
```

### Langkah 2: Setup Database

1. Jalankan XAMPP dan aktifkan Apache dan MySQL
2. Buka phpMyAdmin di browser: `http://localhost/phpmyadmin`
3. Buat database baru dengan nama `tugasMobile`
4. Jalankan query SQL berikut untuk membuat tabel yang diperlukan:

```sql
-- Membuat database
CREATE DATABASE IF NOT EXISTS tugasMobile;
USE tugasMobile;

-- Tabel untuk user login
CREATE TABLE IF NOT EXISTS loginuser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk post artikel
CREATE TABLE IF NOT EXISTS post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data contoh (opsional)
INSERT INTO post (title, content) VALUES 
('Panduan Investasi Saham untuk Pemula', 'Investasi saham merupakan salah satu cara yang populer untuk mengembangkan aset. Bagi pemula, penting untuk memahami dasar-dasar pasar modal, melakukan riset mendalam, dan diversifikasi portofolio untuk meminimalkan risiko.'),
('Analisis Pasar Saham Hari Ini', 'Pasar saham hari ini menunjukkan tren yang positif dengan berbagai sektor mengalami kenaikan. Sektor teknologi dan kesehatan memimpin dengan pertumbuhan signifikan, sementara investor tetap optimis melihat indikator ekonomi yang stabil.');
```

### Langkah 3: Setup Backend (Server Side)

1. Copy folder `server_side` ke dalam folder `htdocs` (untuk XAMPP) atau folder web server Anda:
   
   **Windows (XAMPP):**
   ```bash
   # Copy folder server_side ke C:\xampp\htdocs\
   cp -r server_side C:\xampp\htdocs\
   ```
   
   **Mac (MAMP):**
   ```bash
   # Copy folder server_side ke /Applications/MAMP/htdocs/
   cp -r server_side /Applications/MAMP/htdocs/
   ```

2. Pastikan file PHP dapat diakses melalui: `http://localhost/server_side/`

3. Verifikasi koneksi database di file PHP sudah benar:
   - Host: `localhost`
   - Username: `root`
   - Password: `` (kosong, default XAMPP)
   - Database: `tugasMobile`

### Langkah 4: Setup Frontend

1. Install dependencies:
   ```bash
   npm install
   ```

2. Verifikasi URL API di file `src/views/Tab1Page.vue` sesuai dengan lokasi server Anda:
   ```typescript
   const api = 'http://localhost/server_side/api.php';
   ```

### Langkah 5: Menjalankan Aplikasi

1. Jalankan development server:
   ```bash
   npm run dev
   ```

2. Buka browser dan akses: `http://localhost:5173` (atau port yang ditampilkan di terminal)

3. Aplikasi akan terbuka dan Anda akan diarahkan ke halaman login

## 📱 Cara Menggunakan Aplikasi

1. **Register**: Klik tombol register, masukkan username dan password baru
2. **Login**: Masukkan kredensial yang telah didaftarkan
3. **Lihat Artikel**: Setelah login, Anda akan melihat daftar artikel yang tersedia
4. **Buat Artikel**: Klik tombol "Post Artikel" untuk membuat artikel baru
5. **Baca Detail**: Klik "Baca Artikel" untuk melihat konten lengkap artikel

## 🛠️ Perintah yang Tersedia

```bash
# Menjalankan development server
npm run dev

# Build untuk production
npm run build

# Preview build production
npm run preview

# Menjalankan linter
npm run lint

# Menjalankan unit tests
npm run test:unit

# Menjalankan end-to-end tests
npm run test:e2e
```

## 📁 Struktur Proyek

```
cloneX/
├── src/
│   ├── components/      # Komponen Vue yang dapat digunakan kembali
│   ├── router/          # Konfigurasi routing
│   ├── views/           # Halaman-halaman aplikasi
│   │   ├── Login.vue    # Halaman login
│   │   ├── register.vue # Halaman registrasi
│   │   ├── Tab1Page.vue # Halaman daftar artikel
│   │   ├── Tab2Page.vue # Halaman buat artikel
│   │   ├── Tab3Page.vue # Halaman profil/account
│   │   └── detail.vue   # Halaman detail artikel
│   ├── theme/           # CSS variables dan tema
│   └── main.ts          # Entry point aplikasi
├── server_side/         # Backend PHP
│   ├── api.php          # Get semua artikel
│   ├── login.php        # Autentikasi login
│   ├── register.php     # Registrasi user baru
│   ├── post.php         # Create artikel baru
│   ├── detail.php       # Get detail artikel
│   └── account.php      # Get info akun user
├── public/              # Asset statis
└── tests/               # File testing

```

## ⚠️ Troubleshooting

### Error "Database connection failed"
- Pastikan MySQL sudah berjalan di XAMPP
- Periksa nama database sudah benar (`tugasMobile`)
- Verifikasi kredensial database di file PHP

### Error "CORS policy"
- Pastikan header CORS sudah diset di file PHP backend
- Periksa URL API sudah sesuai dengan lokasi server

### Port 5173 sudah digunakan
- Hentikan proses yang menggunakan port tersebut, atau
- Vite akan otomatis menggunakan port lain (misal: 5174)

## 📝 Catatan Penting

- **Keamanan**: Aplikasi ini untuk pembelajaran. Dalam produksi, gunakan hashing untuk password (bcrypt/Argon2)
- **CORS**: Backend sudah dikonfigurasi untuk menerima request dari semua origin (`*`)
- **Database**: Pastikan backup database secara berkala

## 🤝 Kontribusi

Jika Anda ingin berkontribusi pada proyek ini:
1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran.

---

Dibuat dengan ❤️ menggunakan Vue 3 + Ionic + TypeScript
