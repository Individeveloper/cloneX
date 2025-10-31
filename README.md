# CloneX - Aplikasi Manajemen Artikel

CloneX adalah aplikasi web mobile yang dibangun dengan Vue.js dan Ionic Framework untuk mengelola dan membaca artikel tentang pasar saham. Aplikasi ini memungkinkan pengguna untuk mendaftar, login, membuat postingan artikel, dan membaca artikel yang telah dibuat.

## 📋 Deskripsi Projek

Aplikasi ini adalah platform berbagi artikel yang fokus pada topik pasar saham. Pengguna dapat:
- Mendaftar dan login dengan autentikasi
- Membaca daftar artikel yang tersedia
- Membuat dan memposting artikel baru
- Melihat detail lengkap dari setiap artikel
- Mengelola akun pengguna mereka

## ✨ Fitur Utama

- **Autentikasi Pengguna**: Sistem login dan registrasi dengan keamanan berbasis PHP
- **Manajemen Artikel**: Buat, baca, dan kelola artikel tentang pasar saham
- **Responsive Design**: Tampilan yang optimal di berbagai perangkat mobile dan desktop
- **RESTful API**: Backend PHP dengan API endpoints untuk komunikasi data
- **Real-time Updates**: Tampilan artikel yang dinamis dan terupdate

## 🛠️ Teknologi yang Digunakan

### Frontend
- **Vue.js 3** - Framework JavaScript progresif
- **Ionic Framework 8** - Framework UI untuk aplikasi mobile
- **TypeScript** - Superset JavaScript dengan type safety
- **Vue Router** - Routing untuk single page application
- **Axios** - HTTP client untuk API calls
- **Vite** - Build tool yang cepat dan modern

### Backend
- **PHP** - Server-side scripting
- **MySQL** - Database relasional
- **Apache/XAMPP** - Web server lokal

### Testing & Development
- **Vitest** - Unit testing framework
- **Cypress** - End-to-end testing
- **ESLint** - Linting untuk kualitas kode

## 📦 Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:
- **Node.js** (versi 14 atau lebih tinggi)
- **npm** atau **yarn**
- **XAMPP** atau **Apache + MySQL + PHP**
- **Git** (opsional, untuk cloning repository)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Individeveloper/cloneX.git
cd cloneX
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Setup Database

1. Buka XAMPP dan jalankan **Apache** dan **MySQL**
2. Buka **phpMyAdmin** di browser (`http://localhost/phpmyadmin`)
3. Buat database baru dengan nama `tugasMobile`
4. Buat tabel yang diperlukan:

#### Tabel `loginuser` (untuk autentikasi)
```sql
CREATE TABLE loginuser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Tabel `post` (untuk artikel)
```sql
CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Setup Backend (Server Side)

1. **Pindahkan folder `server_side`** ke dalam direktori **htdocs** di XAMPP Anda
   ```bash
   # Contoh di Windows (sesuaikan path dengan lokasi instalasi XAMPP Anda)
   # Default: C:/xampp/htdocs/
   cp -r server_side C:/xampp/htdocs/cloneX/
   
   # Contoh di Mac/Linux (sesuaikan path dengan lokasi instalasi XAMPP Anda)
   # Default Mac: /Applications/XAMPP/htdocs/
   # Default Linux: /opt/lampp/htdocs/
   cp -r server_side /Applications/XAMPP/htdocs/cloneX/
   ```
   
   **Catatan**: Buat folder `cloneX` di dalam `htdocs` jika belum ada, atau sesuaikan nama folder dengan preferensi Anda.

2. Pastikan konfigurasi database di file PHP sudah sesuai:
   - Host: `localhost`
   - Username: `root`
   - Password: `` (kosong secara default)
   - Database: `tugasMobile`

### 5. Konfigurasi API Endpoint

Pastikan URL API di file Vue sudah mengarah ke lokasi yang benar. Default-nya adalah:
```
http://localhost/cloneX/server_side/
```

Jika Anda meletakkan folder `server_side` di lokasi berbeda, update URL di file-file berikut:
- `src/views/Tab1Page.vue`
- `src/views/Tab2Page.vue`
- `src/views/Login.vue`
- `src/views/register.vue`
- `src/views/detail.vue`

## 🎯 Cara Menggunakan

### Menjalankan Development Server

```bash
npm run dev
```

Aplikasi akan berjalan di `http://localhost:5173` (atau port lain yang ditampilkan di terminal)

### Build untuk Production

```bash
npm run build
```

File hasil build akan tersimpan di folder `dist/`

### Preview Production Build

```bash
npm run preview
```

## 📱 Navigasi Aplikasi

1. **Halaman Login** (`/tabs/login`)
   - Halaman pertama yang muncul saat aplikasi dibuka
   - Masukkan username dan password untuk login
   - Link ke halaman registrasi tersedia

2. **Halaman Registrasi** (`/tabs/register`)
   - Daftar akun baru dengan username dan password
   - Setelah berhasil, akan diarahkan ke halaman login

3. **Halaman Artikel** (`/tabs/tab1`)
   - Menampilkan daftar semua artikel
   - Klik "Baca Artikel" untuk melihat detail
   - Tombol "Post Artikel" untuk membuat artikel baru

4. **Halaman Post Artikel** (`/tabs/tab2`)
   - Form untuk membuat artikel baru
   - Isi judul dan konten artikel
   - Klik "Post" untuk mempublikasikan

5. **Halaman Detail Artikel** (`/tabs/detail/:id`)
   - Menampilkan konten lengkap artikel
   - Informasi waktu pembuatan artikel

6. **Halaman Profil** (`/tabs/tab3`)
   - Menampilkan informasi akun pengguna
   - Opsi logout

## 🧪 Testing

### Unit Testing
```bash
npm run test:unit
```

### End-to-End Testing
```bash
npm run test:e2e
```

## 🔍 Linting

Untuk memeriksa kualitas kode:
```bash
npm run lint
```

## 📂 Struktur Folder

```
cloneX/
├── src/
│   ├── components/      # Komponen Vue yang reusable
│   ├── views/          # Halaman-halaman aplikasi
│   │   ├── Login.vue
│   │   ├── register.vue
│   │   ├── Tab1Page.vue  (Daftar Artikel)
│   │   ├── Tab2Page.vue  (Post Artikel)
│   │   ├── Tab3Page.vue  (Profil)
│   │   └── detail.vue
│   ├── router/         # Konfigurasi routing
│   ├── theme/          # Styling dan tema
│   ├── App.vue         # Root component
│   └── main.ts         # Entry point
├── server_side/        # Backend PHP
│   ├── api.php         # Endpoint untuk list artikel
│   ├── login.php       # Endpoint autentikasi login
│   ├── register.php    # Endpoint registrasi user
│   ├── post.php        # Endpoint untuk create artikel
│   ├── detail.php      # Endpoint detail artikel
│   ├── account.php     # Endpoint informasi akun
│   └── auth.php        # Helper autentikasi
├── tests/              # Unit dan E2E tests
├── public/             # Static assets
└── package.json        # Dependencies dan scripts
```

## ⚙️ API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api.php` | Mendapatkan semua artikel |
| GET | `/detail.php?id={id}` | Mendapatkan detail artikel |
| POST | `/login.php` | Login pengguna |
| POST | `/register.php` | Registrasi pengguna baru |
| POST | `/post.php` | Membuat artikel baru |
| GET | `/account.php` | Mendapatkan info akun |

## 🔐 Keamanan

**PENTING**: Aplikasi ini dibuat untuk tujuan pembelajaran. Untuk penggunaan production, pertimbangkan:
- Menggunakan password hashing yang lebih aman (bcrypt, Argon2)
- Implementasi JWT atau session management yang proper
- Validasi input yang lebih ketat
- Proteksi terhadap SQL injection
- HTTPS untuk komunikasi data
- Environment variables untuk kredensial database

## 🐛 Troubleshooting

### Error: "Database connection failed"
- Pastikan MySQL sudah berjalan di XAMPP
- Cek konfigurasi database di file PHP
- Pastikan database `tugasMobile` sudah dibuat

### Error: "CORS policy"
- Pastikan header CORS sudah diset di file PHP
- Cek apakah Apache di XAMPP sudah berjalan

### Error: "Cannot read properties of undefined"
- Pastikan API endpoint URL sudah benar
- Cek console browser untuk detail error
- Pastikan data dari API sesuai dengan interface TypeScript

## 📄 Lisensi

Projek ini dibuat untuk keperluan pembelajaran dan pengembangan skill.

## 👥 Kontribusi

Kontribusi, issues, dan feature requests sangat diterima!

## 📧 Kontak

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.

---

**Catatan**: Pastikan XAMPP (Apache dan MySQL) berjalan sebelum menjalankan aplikasi!
