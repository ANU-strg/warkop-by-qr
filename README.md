# Warkop API - Sistem Pemesanan dengan QR Code

## 📋 Overview

API Laravel untuk sistem pemesanan makanan di warkop menggunakan QR code yang ditempel di meja. Sistem ini tidak memerlukan login untuk pelanggan dan dapat diintegrasikan langsung dengan aplikasi Flutter.

## 🚀 Features

- ✅ **QR Code Based Ordering**: Setiap meja memiliki QR code unik
- ✅ **No Customer Login**: Pelanggan tidak perlu registrasi/login
- ✅ **Real-time Order Tracking**: Status pesanan dapat ditrack real-time
- ✅ **Menu Management**: CRUD operations untuk menu dengan kategori
- ✅ **Order Management**: Sistem pemesanan dengan detail items
- ✅ **REST API**: API yang ready untuk Flutter integration
- ✅ **CORS Support**: Configured untuk cross-origin requests
- ✅ **Sample Data**: Seeder dengan data meja dan menu lengkap

## 🏗️ Database Schema

### Tabel `meja`
- `id` (Primary Key)
- `nomor_meja` (Unique)
- `qr_code` (Unique UUID)
- `is_active` (Boolean)
- `timestamps`

### Tabel `menu`
- `id` (Primary Key)
- `nama`
- `deskripsi`
- `harga` (Decimal)
- `kategori` (Enum: makanan, minuman, snack)
- `gambar` (URL)
- `is_available` (Boolean)
- `timestamps`

### Tabel `orders`
- `id` (Primary Key)
- `meja_id` (Foreign Key ke meja)
- `order_number` (Unique)
- `total_harga` (Decimal)
- `status` (Enum: pending, processing, ready, completed, cancelled)
- `catatan`
- `waktu_pesan`
- `timestamps`

### Tabel `order_details`
- `id` (Primary Key)
- `order_id` (Foreign Key ke orders)
- `menu_id` (Foreign Key ke menu)
- `jumlah` (Integer)
- `harga_satuan` (Decimal)
- `subtotal` (Decimal)
- `catatan_item`
- `timestamps`

## 🔗 Relationships

- `Meja` → `Order` (One to Many)
- `Order` → `OrderDetail` (One to Many)  
- `Menu` → `OrderDetail` (One to Many)

## 📁 Project Structure

```
warkop-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── MejaController.php      # CRUD Meja + QR lookup
│   │   │       ├── MenuController.php      # CRUD Menu + Category filter
│   │   │       └── OrderController.php     # Order management + Status tracking
│   │   └── Middleware/
│   │       └── Cors.php                    # CORS untuk Flutter
│   └── Models/
│       ├── Meja.php                        # Model dengan relationships
│       ├── Menu.php                        # Model dengan categories
│       ├── Order.php                       # Model dengan order logic
│       └── OrderDetail.php                 # Model untuk item details
├── database/
│   ├── migrations/
│   │   ├── create_meja_table.php          # Schema meja dengan QR code
│   │   ├── create_menu_table.php          # Schema menu dengan kategori
│   │   ├── create_orders_table.php        # Schema orders dengan status
│   │   └── create_order_details_table.php # Schema detail items
│   └── seeders/
│       ├── MejaSeeder.php                 # 10 meja dengan QR unik
│       ├── MenuSeeder.php                 # 15+ sample menu items
│       └── DatabaseSeeder.php             # Main seeder
├── routes/
│   └── api.php                            # API routes dengan versioning
├── API_DOCUMENTATION.md                    # Complete API docs
├── FLUTTER_EXAMPLES.md                     # Flutter integration examples
└── README.md                              # This file
```

## 🛠️ Installation

1. **Clone & Setup**
```bash
git clone <repository>
cd warkop-api
composer install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
# Configure database in .env file
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Run migrations & seeders
php artisan migrate
php artisan db:seed
```

4. **Start Development Server**
```bash
php artisan serve
```

Server will run at `http://localhost:8000`

## 🔧 Configuration

### CORS (Cross-Origin Resource Sharing)
CORS middleware sudah dikonfigurasi untuk menerima requests dari Flutter aplikasi.

### API Versioning
All API endpoints menggunakan prefix `/api/v1/` untuk future versioning.

## 📊 Sample Data

### 10 Meja dengan QR Code
- Meja 01-10
- Setiap meja memiliki UUID unik sebagai QR code
- Semua meja aktif by default

### 15+ Menu Items
**Makanan:**
- Nasi Goreng Kampung (Rp 25,000)
- Mie Ayam Bakso (Rp 20,000)
- Ayam Bakar Madu (Rp 35,000)
- Soto Ayam (Rp 18,000)
- Gado-Gado (Rp 15,000)

**Minuman:**
- Es Teh Manis (Rp 5,000)
- Es Jeruk (Rp 8,000)
- Kopi Tubruk (Rp 7,000)
- Es Kopi Susu (Rp 12,000)
- Jus Alpukat (Rp 15,000)
- Es Campur (Rp 12,000)

**Snack:**
- Pisang Goreng (Rp 10,000)
- Tahu Isi (Rp 8,000)
- Bakwan Jagung (Rp 6,000)
- Kerupuk Udang (Rp 5,000)
- Tempe Mendoan (Rp 7,000)

## 🔄 Customer Flow

1. **QR Code Scan**: Pelanggan scan QR code di meja
2. **Get Meja Info**: App Flutter call `/api/v1/meja/qr/{qr-code}`
3. **Browse Menu**: Call `/api/v1/menu/kategori/{category}` 
4. **Add to Cart**: Collect items di aplikasi Flutter
5. **Place Order**: Submit via `/api/v1/orders`
6. **Track Status**: Monitor via `/api/v1/orders/qr/{qr-code}`

## 🏪 Admin Features

- **Meja Management**: Add/Edit/Deactivate tables
- **Menu Management**: CRUD operations dengan kategori
- **Order Tracking**: Update status pesanan
- **Real-time Updates**: Status changes reflected immediately

## 🔐 Security Features

- Input validation pada semua endpoints
- SQL injection protection via Eloquent ORM
- CORS configuration untuk security
- Error handling yang proper
- UUID untuk QR codes (tidak predictable)

## 🚀 Production Deployment

1. **Environment Variables**
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql  # or postgresql
```

2. **Optimize for Production**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Web Server Configuration**
Setup Apache/Nginx to point to `/public` directory.

## 📱 Flutter Integration

Lengkap dengan:
- Model classes untuk semua entities
- API service class dengan error handling
- Example screens dan widgets
- HTTP client configuration
- QR code scanning integration

Lihat file `FLUTTER_EXAMPLES.md` untuk implementasi lengkap.

## 🧪 Testing API

Test semua endpoints menggunakan:
- **Postman Collection** (bisa di-import)
- **curl commands** (lihat API_DOCUMENTATION.md)
- **Built-in health check** endpoint

## 📞 Support

Untuk pertanyaan atau issues:
1. Check documentation files
2. Review API responses untuk error messages
3. Monitor Laravel logs di `storage/logs/`

---

**Best Practices Applied:**
- ✅ RESTful API design
- ✅ Proper HTTP status codes  
- ✅ Consistent JSON responses
- ✅ Database transactions untuk order creation
- ✅ Eloquent relationships
- ✅ Input validation
- ✅ Error handling
- ✅ CORS configuration
- ✅ API versioning ready
- ✅ Sample data included
