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

## ⚙️ Dokumentasi API

### Autentikasi API

Sebagian besar endpoint API memerlukan **API Key** untuk autentikasi. API Key harus disertakan dalam header request:

```
X-API-Key: 12345
```

**Catatan**: API Key default adalah `12345`. Untuk keamanan production, ganti nilai ini di file `server_side/auth.php`.

Endpoint yang memerlukan autentikasi:
- POST `/post.php`
- GET `/account.php`
- GET `/profile.php`
- PUT `/profile.php`
- GET `/user_posts.php`
- PUT `/user_posts.php`
- DELETE `/user_posts.php`

---

### 1. GET `/api.php` - Mendapatkan Semua Artikel

Mengambil daftar semua artikel yang tersedia dalam database.

**Request:**
```http
GET http://localhost/cloneX/server_side/api.php
```

**Headers:**
```
Content-Type: application/json
```

**Response Success (200):**
```json
[
  {
    "id": 1,
    "username": "john_doe",
    "title": "Analisis Saham Tech 2024",
    "content": "Konten artikel lengkap...",
    "createdAt": "2024-01-15 10:30:00"
  },
  {
    "id": 2,
    "username": "jane_smith",
    "title": "Trend Pasar Saham Q1",
    "content": "Konten artikel lengkap...",
    "createdAt": "2024-01-16 14:20:00"
  }
]
```

**Response Tidak Ada Data (404):**
```json
[]
```

---

### 2. GET `/detail.php?id={id}` - Mendapatkan Detail Artikel

Mengambil detail lengkap dari satu artikel berdasarkan ID.

**Request:**
```http
GET http://localhost/cloneX/server_side/detail.php?id=1
```

**Parameters:**
- `id` (required): ID artikel yang ingin diambil

**Headers:**
```
Content-Type: application/json
```

**Response Success (200):**
```json
{
  "message": "Data detail berhasil dimuat.",
  "record": {
    "id": 1,
    "title": "Analisis Saham Tech 2024",
    "content": "Konten artikel lengkap tentang analisis saham teknologi...",
    "createdAt": "2024-01-15 10:30:00"
  }
}
```

**Response Error - ID Tidak Ditemukan (404):**
```json
{
  "message": "Artikel tidak ditemukan.",
  "record": null
}
```

**Response Error - ID Tidak Disediakan (400):**
```json
{
  "message": "ID post wajib disediakan."
}
```

---

### 3. POST `/login.php` - Login Pengguna

Melakukan autentikasi login pengguna dengan username dan password.

**Request:**
```http
POST http://localhost/cloneX/server_side/login.php
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

**Request Body:**
```json
{
  "username": "john_doe",
  "password": "mypassword123"
}
```

**Response Success (200):**
```json
{
  "message": "Login successful",
  "success": true,
  "username": "john_doe"
}
```

**Response Error - Kredensial Salah (401):**
```json
{
  "message": "Invalid credentials",
  "success": false
}
```

**Response Error - Database (500):**
```json
{
  "error": "Database connection failed"
}
```

---

### 4. POST `/register.php` - Registrasi Pengguna Baru

Mendaftarkan pengguna baru ke dalam sistem.

**Request:**
```http
POST http://localhost/cloneX/server_side/register.php
```

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "username": "new_user",
  "password": "securepass123",
  "email": "user@example.com"
}
```

**Response Success (201):**
```json
{
  "message": "Registrasi berhasil.",
  "success": true,
  "insert_id": 5
}
```

**Response Error - Field Kosong (400):**
```json
{
  "message": "Field username dan password wajib diisi dengan benar.",
  "success": false
}
```

**Response Error - Username Sudah Ada (500):**
```json
{
  "message": "Gagal menyimpan data: Duplicate entry 'new_user' for key 'username'",
  "success": false
}
```

---

### 5. POST `/post.php` - Membuat Artikel Baru

Membuat dan mempublikasikan artikel baru.

**Request:**
```http
POST http://localhost/cloneX/server_side/post.php
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

**Request Body:**
```json
{
  "username": "john_doe",
  "title": "Strategi Investasi 2024",
  "content": "Dalam artikel ini, saya akan membahas strategi investasi terbaik untuk tahun 2024..."
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Post created successfully",
  "post_id": 10
}
```

**Response Error - Field Tidak Lengkap (400):**
```json
{
  "success": false,
  "message": "Title, content, and username are required"
}
```

**Response Error - Method Tidak Diizinkan (405):**
```json
{
  "success": false,
  "message": "Method not allowed"
}
```

---

### 6. GET `/account.php` - Mendapatkan Data Akun/Post dengan Endpoint Fleksibel

API ini menyediakan beberapa endpoint dalam satu file dengan parameter `endpoint` untuk mengakses berbagai data.

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

#### 6.1. Mendapatkan Daftar User

**Request:**
```http
GET http://localhost/cloneX/server_side/account.php?endpoint=users
```

**Response (200):**
```json
[
  {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com"
  },
  {
    "id": 2,
    "username": "jane_smith",
    "email": "jane@example.com"
  }
]
```

#### 6.2. Mendapatkan Semua Post dengan Informasi Author

**Request:**
```http
GET http://localhost/cloneX/server_side/account.php?endpoint=posts
```

**Response (200):**
```json
[
  {
    "id": 1,
    "username": "john_doe",
    "title": "Analisis Saham",
    "content": "Konten lengkap...",
    "createdAt": "2024-01-15 10:30:00",
    "author_name": "john_doe",
    "author_email": "john@example.com"
  }
]
```

#### 6.3. Mendapatkan Post dari User Tertentu

**Request:**
```http
GET http://localhost/cloneX/server_side/account.php?endpoint=user_posts&username=john_doe
```

**Parameters:**
- `username` (required): Username yang ingin diambil post-nya

**Response (200):**
```json
[
  {
    "id": 1,
    "username": "john_doe",
    "title": "Analisis Saham",
    "content": "Konten lengkap...",
    "createdAt": "2024-01-15 10:30:00",
    "author_name": "john_doe",
    "author_email": "john@example.com"
  }
]
```

**Response Error (400):**
```json
{
  "error": "Username parameter required"
}
```

#### 6.4. Mendapatkan Detail Post Tertentu

**Request:**
```http
GET http://localhost/cloneX/server_side/account.php?endpoint=post_detail&id=1
```

**Parameters:**
- `id` (required): ID post yang ingin diambil

**Response (200):**
```json
[
  {
    "id": 1,
    "username": "john_doe",
    "title": "Analisis Saham",
    "content": "Konten lengkap...",
    "createdAt": "2024-01-15 10:30:00",
    "author_name": "john_doe",
    "author_email": "john@example.com"
  }
]
```

---

### 7. GET `/profile.php` - Mendapatkan Profil User

Mengambil informasi profil pengguna berdasarkan username.

**Request:**
```http
GET http://localhost/cloneX/server_side/profile.php?username=john_doe
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

**Parameters:**
- `username` (required): Username yang ingin diambil profilnya

**Response Success (200):**
```json
{
  "id": 1,
  "username": "john_doe",
  "password": "mypassword123",
  "email": "john@example.com",
  "created_at": "2024-01-10 08:00:00"
}
```

**Response Error - User Tidak Ditemukan (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

---

### 8. PUT `/profile.php` - Update Profil atau Password

API ini memiliki dua fungsi: update profil atau ubah password, tergantung pada URL dan parameter yang dikirim.

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

#### 8.1. Update Profil (Email/Username)

**Request:**
```http
PUT http://localhost/cloneX/server_side/profile.php
```

**Request Body:**
```json
{
  "username": "john_doe",
  "email": "newemail@example.com",
  "new_username": "john_doe_updated"
}
```

**Fields:**
- `username` (required): Username saat ini
- `email` (optional): Email baru
- `new_username` (optional): Username baru (akan dicek ketersediaannya)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Profile updated"
}
```

**Response Error - Username Sudah Digunakan (409):**
```json
{
  "success": false,
  "message": "Username already taken"
}
```

#### 8.2. Ubah Password

**Request:**
```http
PUT http://localhost/cloneX/server_side/profile.php
```

**Request Body:**
```json
{
  "username": "john_doe",
  "currentPassword": "oldpass123",
  "newPassword": "newpass456"
}
```

**Fields:**
- `username` (required): Username yang akan diubah passwordnya
- `currentPassword` (required): Password lama untuk verifikasi
- `newPassword` (required): Password baru

**Response Success (200):**
```json
{
  "success": true,
  "message": "Password updated"
}
```

**Response Error - Password Lama Salah (401):**
```json
{
  "success": false,
  "message": "Current password is incorrect"
}
```

---

### 9. GET `/user_posts.php` - Mendapatkan Post Milik User

Mengambil semua post yang dibuat oleh user tertentu.

**Request:**
```http
GET http://localhost/cloneX/server_side/user_posts.php?username=john_doe
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
```

**Parameters:**
- `username` (required): Username yang ingin diambil post-nya

**Response Success (200):**
```json
[
  {
    "id": 1,
    "title": "Analisis Saham Tech",
    "content": "Konten lengkap...",
    "createdAt": "2024-01-15 10:30:00",
    "username": "john_doe"
  },
  {
    "id": 3,
    "title": "Strategi Investasi",
    "content": "Konten lengkap...",
    "createdAt": "2024-01-18 15:45:00",
    "username": "john_doe"
  }
]
```

**Response Tidak Ada Data (200):**
```json
[]
```

---

### 10. PUT `/user_posts.php` - Update Post

Mengupdate konten atau judul dari post yang sudah ada. Hanya pemilik post yang bisa mengupdate.

**Request:**
```http
PUT http://localhost/cloneX/server_side/user_posts.php
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
X-Username: john_doe
```

**Request Body:**
```json
{
  "id": 1,
  "username": "john_doe",
  "title": "Analisis Saham Tech 2024 - Updated",
  "content": "Konten yang sudah diperbarui..."
}
```

**Fields:**
- `id` (required): ID post yang akan diupdate (bisa juga di URL path)
- `username` (required): Username pemilik post untuk verifikasi
- `title` (optional): Judul baru
- `content` (optional): Konten baru

**Response Success (200):**
```json
{
  "success": true,
  "message": "Post updated"
}
```

**Response Error - Bukan Pemilik (403):**
```json
{
  "success": false,
  "message": "You are not the owner of this post"
}
```

**Response Error - Post Tidak Ditemukan (404):**
```json
{
  "success": false,
  "message": "Post not found"
}
```

---

### 11. DELETE `/user_posts.php` - Hapus Post

Menghapus post dari database. Hanya pemilik post yang bisa menghapus.

**Request:**
```http
DELETE http://localhost/cloneX/server_side/user_posts.php?id=1&username=john_doe
```

**Headers:**
```
Content-Type: application/json
X-API-Key: 12345
X-Username: john_doe
```

**Parameters:**
- `id` (required): ID post yang akan dihapus
- `username` (required): Username pemilik untuk verifikasi (bisa di query param atau header X-Username)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Post deleted"
}
```

**Response Error - Bukan Pemilik (403):**
```json
{
  "success": false,
  "message": "You are not the owner of this post"
}
```

**Response Error - Post Tidak Ditemukan (404):**
```json
{
  "success": false,
  "message": "Post not found"
}
```

---

### Contoh Penggunaan API dengan Axios (JavaScript)

#### Login dan Simpan Session

```javascript
import axios from 'axios';

const API_BASE_URL = 'http://localhost/cloneX/server_side';
const API_KEY = '12345';

// Login
async function login(username, password) {
  try {
    const response = await axios.post(
      `${API_BASE_URL}/login.php`,
      { username, password },
      { headers: { 'X-API-Key': API_KEY } }
    );
    
    if (response.data.success) {
      // Simpan username di localStorage
      localStorage.setItem('username', response.data.username);
      console.log('Login berhasil!');
      return true;
    }
  } catch (error) {
    console.error('Login gagal:', error.response?.data?.message);
    return false;
  }
}
```

#### Mengambil dan Menampilkan Artikel

```javascript
// Ambil semua artikel
async function getArticles() {
  try {
    const response = await axios.get(`${API_BASE_URL}/api.php`);
    console.log('Artikel:', response.data);
    return response.data;
  } catch (error) {
    console.error('Gagal mengambil artikel:', error);
    return [];
  }
}

// Ambil detail artikel
async function getArticleDetail(id) {
  try {
    const response = await axios.get(`${API_BASE_URL}/detail.php?id=${id}`);
    console.log('Detail:', response.data.record);
    return response.data.record;
  } catch (error) {
    console.error('Gagal mengambil detail:', error);
    return null;
  }
}
```

#### Membuat Artikel Baru

```javascript
async function createPost(title, content) {
  const username = localStorage.getItem('username');
  
  try {
    const response = await axios.post(
      `${API_BASE_URL}/post.php`,
      { username, title, content },
      { headers: { 'X-API-Key': API_KEY } }
    );
    
    if (response.data.success) {
      console.log('Post berhasil dibuat! ID:', response.data.post_id);
      return response.data.post_id;
    }
  } catch (error) {
    console.error('Gagal membuat post:', error.response?.data?.message);
    return null;
  }
}
```

#### Update dan Hapus Post

```javascript
// Update post
async function updatePost(postId, title, content) {
  const username = localStorage.getItem('username');
  
  try {
    const response = await axios.put(
      `${API_BASE_URL}/user_posts.php`,
      { id: postId, username, title, content },
      { headers: { 'X-API-Key': API_KEY, 'X-Username': username } }
    );
    
    if (response.data.success) {
      console.log('Post berhasil diupdate!');
      return true;
    }
  } catch (error) {
    console.error('Gagal update post:', error.response?.data?.message);
    return false;
  }
}

// Hapus post
async function deletePost(postId) {
  const username = localStorage.getItem('username');
  
  try {
    const response = await axios.delete(
      `${API_BASE_URL}/user_posts.php?id=${postId}&username=${username}`,
      { headers: { 'X-API-Key': API_KEY, 'X-Username': username } }
    );
    
    if (response.data.success) {
      console.log('Post berhasil dihapus!');
      return true;
    }
  } catch (error) {
    console.error('Gagal hapus post:', error.response?.data?.message);
    return false;
  }
}
```

---

### HTTP Status Codes

API ini menggunakan status code HTTP standar:

| Status Code | Deskripsi |
|-------------|-----------|
| **200** | OK - Request berhasil |
| **201** | Created - Resource berhasil dibuat |
| **400** | Bad Request - Parameter tidak valid atau tidak lengkap |
| **401** | Unauthorized - API Key tidak valid atau autentikasi gagal |
| **403** | Forbidden - Tidak memiliki akses ke resource |
| **404** | Not Found - Resource tidak ditemukan |
| **405** | Method Not Allowed - HTTP method tidak diizinkan |
| **409** | Conflict - Data sudah ada (duplikat) |
| **500** | Internal Server Error - Error pada server atau database |

---

### Tips Penggunaan API

1. **Selalu sertakan API Key** untuk endpoint yang memerlukan autentikasi
2. **Gunakan HTTPS** di production untuk keamanan data
3. **Validasi input** di sisi client sebelum mengirim request
4. **Handle error** dengan baik untuk UX yang lebih baik
5. **Simpan username** di localStorage setelah login untuk request selanjutnya
6. **Logout**: Hapus data dari localStorage saat user logout

```javascript
function logout() {
  localStorage.removeItem('username');
  // Redirect ke halaman login
}
```

---

### Ringkasan Endpoint API

| Method | Endpoint | Auth Required | Deskripsi |
|--------|----------|---------------|-----------|
| GET | `/api.php` | ❌ | Mendapatkan semua artikel |
| GET | `/detail.php?id={id}` | ❌ | Mendapatkan detail artikel |
| POST | `/login.php` | ✅ API Key | Login pengguna |
| POST | `/register.php` | ❌ | Registrasi pengguna baru |
| POST | `/post.php` | ✅ API Key | Membuat artikel baru |
| GET | `/account.php?endpoint=users` | ✅ API Key | Mendapatkan daftar user |
| GET | `/account.php?endpoint=posts` | ✅ API Key | Mendapatkan semua post dengan author |
| GET | `/account.php?endpoint=user_posts&username={username}` | ✅ API Key | Mendapatkan post dari user tertentu |
| GET | `/account.php?endpoint=post_detail&id={id}` | ✅ API Key | Mendapatkan detail post dengan author |
| GET | `/profile.php?username={username}` | ✅ API Key | Mendapatkan profil user |
| PUT | `/profile.php` | ✅ API Key | Update profil atau password |
| GET | `/user_posts.php?username={username}` | ✅ API Key | Mendapatkan post milik user |
| PUT | `/user_posts.php` | ✅ API Key | Update post (owner only) |
| DELETE | `/user_posts.php?id={id}&username={username}` | ✅ API Key | Hapus post (owner only) |

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