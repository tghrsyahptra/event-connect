# 📊 **ADMIN DASHBOARD FUNCTIONS DOCUMENTATION**

## 🎯 **Overview**
Dokumentasi lengkap fungsi-fungsi yang tersedia di Admin Dashboard Event Connect.

---

## 👥 **USERS MANAGEMENT**

### **Tabel Fungsi Users**

| **Fungsi** | **Method** | **Route** | **Deskripsi** | **Parameter** |
|------------|------------|-----------|---------------|---------------|
| **List Users** | `GET` | `/admin/users` | Menampilkan daftar semua users dengan fitur search, filter, dan pagination | `search`, `role`, `is_organizer` |
| **Create User** | `GET` | `/admin/users/create` | Form untuk membuat user baru | - |
| **Store User** | `POST` | `/admin/users` | Menyimpan user baru ke database | `name`, `email`, `password`, `role`, `phone`, `bio`, `is_organizer` |
| **Show User** | `GET` | `/admin/users/{user}` | Menampilkan detail user | `user` (ID) |
| **Edit User** | `GET` | `/admin/users/{user}/edit` | Form untuk edit user | `user` (ID) |
| **Update User** | `PUT/PATCH` | `/admin/users/{user}` | Update data user | `user` (ID), data user |
| **Delete User** | `DELETE` | `/admin/users/{user}` | Hapus user dari database | `user` (ID) |
| **Toggle Status** | `POST` | `/admin/users/{user}/toggle-status` | Aktifkan/nonaktifkan user | `user` (ID) |

### **Fitur Users:**
- ✅ **Search**: Cari berdasarkan nama, email, atau full name
- ✅ **Filter**: Filter berdasarkan role (admin/participant) dan status organizer
- ✅ **Pagination**: 10 users per halaman
- ✅ **Role Management**: Kelola role admin dan participant
- ✅ **Status Toggle**: Aktifkan/nonaktifkan user
- ✅ **Validation**: Validasi data input yang ketat
- ✅ **Security**: Admin tidak bisa hapus akun sendiri

---

## 📅 **EVENTS MANAGEMENT**

### **Tabel Fungsi Events**

| **Fungsi** | **Method** | **Route** | **Deskripsi** | **Parameter** |
|------------|------------|-----------|---------------|---------------|
| **List Events** | `GET` | `/admin/events` | Menampilkan daftar semua events dengan fitur search dan filter | `search`, `category_id`, `status`, `date_from`, `date_to` |
| **Create Event** | `GET` | `/admin/events/create` | Form untuk membuat event baru | - |
| **Store Event** | `POST` | `/admin/events` | Menyimpan event baru ke database | `title`, `description`, `category_id`, `organizer_id`, `start_date`, `end_date`, `location`, `max_participants`, `price`, `status`, `image` |
| **Show Event** | `GET` | `/admin/events/{event}` | Menampilkan detail event beserta participants | `event` (ID) |
| **Edit Event** | `GET` | `/admin/events/{event}/edit` | Form untuk edit event | `event` (ID) |
| **Update Event** | `PUT/PATCH` | `/admin/events/{event}` | Update data event | `event` (ID), data event |
| **Delete Event** | `DELETE` | `/admin/events/{event}` | Hapus event dari database | `event` (ID) |
| **Toggle Status** | `POST` | `/admin/events/{event}/toggle-status` | Ubah status event (draft→published→cancelled→completed) | `event` (ID) |
| **Event Participants** | `GET` | `/admin/events/{event}/participants` | Daftar participants event | `event` (ID) |

### **Fitur Events:**
- ✅ **Search**: Cari berdasarkan title, description, atau location
- ✅ **Filter**: Filter berdasarkan category, status, dan date range
- ✅ **Image Upload**: Upload gambar event
- ✅ **Status Management**: Kelola status (draft, published, cancelled, completed)
- ✅ **Participant Tracking**: Lihat daftar participants
- ✅ **Date Validation**: Validasi tanggal start dan end
- ✅ **Price Management**: Kelola harga event
- ✅ **Organizer Assignment**: Assign organizer ke event

---

## 🏷️ **CATEGORIES MANAGEMENT**

### **Tabel Fungsi Categories**

| **Fungsi** | **Method** | **Route** | **Deskripsi** | **Parameter** |
|------------|------------|-----------|---------------|---------------|
| **List Categories** | `GET` | `/admin/categories` | Menampilkan daftar semua categories dengan jumlah events | `search` |
| **Store Category** | `POST` | `/admin/categories` | Membuat category baru | `name`, `description`, `color`, `is_active` |
| **Update Category** | `PUT/PATCH` | `/admin/categories/{category}` | Update data category | `category` (ID), data category |
| **Delete Category** | `DELETE` | `/admin/categories/{category}` | Hapus category (jika tidak ada events) | `category` (ID) |
| **Toggle Status** | `POST` | `/admin/categories/{category}/toggle-status` | Aktifkan/nonaktifkan category | `category` (ID) |
| **Category Statistics** | `GET` | `/admin/categories/{category}/statistics` | Statistik category (JSON) | `category` (ID) |

### **Fitur Categories:**
- ✅ **Search**: Cari berdasarkan nama atau description
- ✅ **Color Coding**: Set warna untuk category
- ✅ **Status Management**: Aktifkan/nonaktifkan category
- ✅ **Event Count**: Tampilkan jumlah events per category
- ✅ **Statistics**: Statistik detail per category
- ✅ **Validation**: Validasi nama unik dan format warna
- ✅ **Safety Check**: Tidak bisa hapus category yang memiliki events

---

## 📈 **ANALYTICS & REPORTS**

### **Tabel Fungsi Analytics**

| **Fungsi** | **Method** | **Route** | **Deskripsi** | **Parameter** |
|------------|------------|-----------|---------------|---------------|
| **Analytics Dashboard** | `GET` | `/admin/analytics` | Dashboard analytics lengkap | `date_range` |
| **Export Data** | `GET` | `/admin/analytics/export` | Export data analytics | `format`, `date_range` |
| **Real-time Stats** | `GET` | `/admin/analytics/realtime` | Statistik real-time (JSON) | - |

### **Fitur Analytics:**

#### **📊 Basic Statistics:**
- Total Users & New Users
- Total Events & Active Events  
- Total Participants
- Total Revenue
- Average Rating

#### **👥 User Analytics:**
- User Registration Trends
- Role Distribution (Admin vs Participant)
- Organizer Statistics
- User Growth Charts

#### **📅 Event Analytics:**
- Event Creation Trends
- Event Status Distribution
- Monthly Event Counts
- Event Performance Metrics

#### **💰 Revenue Analytics:**
- Revenue Trends Over Time
- Revenue by Category
- Revenue Growth Analysis
- Top Performing Events

#### **🏷️ Category Analytics:**
- Events per Category
- Category Performance
- Category Popularity Trends
- Category Revenue Analysis

#### **📈 Advanced Features:**
- **Date Range Filtering**: 7, 30, 90, 365 days
- **Monthly Trends**: 12 bulan terakhir
- **Top Events**: 10 events terpopuler
- **Real-time Updates**: Statistik live
- **Export Functionality**: CSV/Excel export
- **Interactive Charts**: Visualisasi data

---

## 🔧 **TECHNICAL SPECIFICATIONS**

### **Database Tables Used:**
- `users` - User management
- `events` - Event management  
- `categories` - Category management
- `event_participants` - Event participation tracking
- `feedbacks` - User feedback and ratings

### **Security Features:**
- ✅ **Role-based Access Control**: Hanya admin yang bisa akses
- ✅ **CSRF Protection**: Semua form protected
- ✅ **Input Validation**: Validasi ketat semua input
- ✅ **File Upload Security**: Validasi tipe dan ukuran file
- ✅ **SQL Injection Protection**: Menggunakan Eloquent ORM

### **Performance Features:**
- ✅ **Pagination**: Mencegah loading data berlebihan
- ✅ **Eager Loading**: Optimasi query database
- ✅ **Caching**: Cache untuk data yang sering diakses
- ✅ **Search Optimization**: Index pada kolom yang sering dicari

---

## 🚀 **USAGE EXAMPLES**

### **Mengelola Users:**
```bash
# Lihat semua users
GET /admin/users

# Cari users dengan role admin
GET /admin/users?role=admin

# Cari users dengan keyword "john"
GET /admin/users?search=john

# Toggle status user
POST /admin/users/5/toggle-status
```

### **Mengelola Events:**
```bash
# Lihat semua events
GET /admin/events

# Filter events by category
GET /admin/events?category_id=3

# Filter events by status
GET /admin/events?status=published

# Lihat participants event
GET /admin/events/10/participants
```

### **Mengelola Categories:**
```bash
# Lihat semua categories
GET /admin/categories

# Lihat statistik category
GET /admin/categories/5/statistics

# Toggle status category
POST /admin/categories/5/toggle-status
```

### **Analytics:**
```bash
# Dashboard analytics (30 hari terakhir)
GET /admin/analytics

# Analytics dengan range 90 hari
GET /admin/analytics?date_range=90

# Export data analytics
GET /admin/analytics/export?format=csv&date_range=30

# Real-time statistics
GET /admin/analytics/realtime
```

---

## 📝 **NOTES**

- Semua fungsi memerlukan **authentication** dan **admin role**
- Semua form menggunakan **CSRF protection**
- File upload dibatasi **2MB** untuk gambar
- Pagination default **10 items per page**
- Date range analytics default **30 hari**
- Real-time stats update setiap **30 detik**

---

*Dokumentasi ini akan terus diupdate seiring dengan pengembangan fitur-fitur baru.*

