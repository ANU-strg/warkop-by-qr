# Warkop API Documentation

API Laravel untuk sistem pemesanan makanan di warkop menggunakan QR code.

## Base URL
```
http://localhost:8000/api
```

## API Endpoints

### 1. Health Check
**GET** `/health`
```json
{
    "status": "OK",
    "message": "Warkop API is running",
    "timestamp": "2025-09-28T09:30:00.000000Z"
}
```

---

### 2. Meja Endpoints

#### Get All Meja
**GET** `/v1/meja`
```json
{
    "success": true,
    "message": "Data meja berhasil diambil",
    "data": [
        {
            "id": 1,
            "nomor_meja": "01",
            "qr_code": "uuid-string",
            "is_active": true,
            "created_at": "2025-09-28T09:30:00.000000Z",
            "updated_at": "2025-09-28T09:30:00.000000Z"
        }
    ]
}
```

#### Get Meja by QR Code
**GET** `/v1/meja/qr/{qrCode}`
```json
{
    "success": true,
    "message": "Data meja berhasil diambil",
    "data": {
        "id": 1,
        "nomor_meja": "01",
        "qr_code": "uuid-string",
        "is_active": true,
        "created_at": "2025-09-28T09:30:00.000000Z",
        "updated_at": "2025-09-28T09:30:00.000000Z"
    }
}
```

#### Create Meja
**POST** `/v1/meja`

Request Body:
```json
{
    "nomor_meja": "11",
    "is_active": true
}
```

Response:
```json
{
    "success": true,
    "message": "Meja berhasil ditambahkan",
    "data": {
        "id": 11,
        "nomor_meja": "11",
        "qr_code": "auto-generated-uuid",
        "is_active": true,
        "created_at": "2025-09-28T09:30:00.000000Z",
        "updated_at": "2025-09-28T09:30:00.000000Z"
    }
}
```

---

### 3. Menu Endpoints

#### Get All Menu
**GET** `/v1/menu`

Query Parameters:
- `kategori` (optional): makanan, minuman, snack
- `available` (optional): true/false

```json
{
    "success": true,
    "message": "Data menu berhasil diambil",
    "data": [
        {
            "id": 1,
            "nama": "Nasi Goreng Kampung",
            "deskripsi": "Nasi goreng khas kampung dengan bumbu tradisional",
            "harga": "25000.00",
            "kategori": "makanan",
            "gambar": null,
            "is_available": true,
            "created_at": "2025-09-28T09:30:00.000000Z",
            "updated_at": "2025-09-28T09:30:00.000000Z"
        }
    ]
}
```

#### Get Menu by Category
**GET** `/v1/menu/kategori/{kategori}`

Parameter: makanan, minuman, snack

#### Create Menu
**POST** `/v1/menu`

Request Body:
```json
{
    "nama": "Nasi Gudeg",
    "deskripsi": "Nasi gudeg khas Yogyakarta",
    "harga": 20000,
    "kategori": "makanan",
    "gambar": "https://example.com/image.jpg",
    "is_available": true
}
```

---

### 4. Order Endpoints

#### Get All Orders
**GET** `/v1/orders`

Query Parameters:
- `status` (optional): pending, processing, ready, completed, cancelled
- `meja_id` (optional): filter by meja ID

```json
{
    "success": true,
    "message": "Data pesanan berhasil diambil",
    "data": [
        {
            "id": 1,
            "meja_id": 1,
            "order_number": "ORD-20250928-001",
            "total_harga": "45000.00",
            "status": "pending",
            "catatan": null,
            "waktu_pesan": "2025-09-28T09:30:00.000000Z",
            "created_at": "2025-09-28T09:30:00.000000Z",
            "updated_at": "2025-09-28T09:30:00.000000Z",
            "meja": {
                "id": 1,
                "nomor_meja": "01",
                "qr_code": "uuid-string",
                "is_active": true
            },
            "order_details": [
                {
                    "id": 1,
                    "order_id": 1,
                    "menu_id": 1,
                    "jumlah": 1,
                    "harga_satuan": "25000.00",
                    "subtotal": "25000.00",
                    "catatan_item": null,
                    "menu": {
                        "id": 1,
                        "nama": "Nasi Goreng Kampung",
                        "harga": "25000.00",
                        "kategori": "makanan"
                    }
                }
            ]
        }
    ]
}
```

#### Create Order
**POST** `/v1/orders`

Request Body:
```json
{
    "meja_id": 1,
    "catatan": "Pedasnya sedang",
    "items": [
        {
            "menu_id": 1,
            "jumlah": 2,
            "catatan_item": "Tanpa kerupuk"
        },
        {
            "menu_id": 6,
            "jumlah": 1,
            "catatan_item": null
        }
    ]
}
```

#### Update Order Status
**PATCH** `/v1/orders/{id}/status`

Request Body:
```json
{
    "status": "processing"
}
```

Status options: pending, processing, ready, completed, cancelled

#### Get Orders by Meja ID
**GET** `/v1/orders/meja/{mejaId}`

#### Get Active Orders by QR Code
**GET** `/v1/orders/qr/{qrCode}`

Response includes meja info and active orders:
```json
{
    "success": true,
    "message": "Data pesanan aktif berhasil diambil",
    "data": {
        "meja": {
            "id": 1,
            "nomor_meja": "01",
            "qr_code": "uuid-string",
            "is_active": true
        },
        "orders": [...]
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Resource tidak ditemukan"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Terjadi kesalahan server",
    "error": "Error details"
}
```

---

## Flutter Integration

### 1. Setup HTTP Client
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<Map<String, dynamic>> get(String endpoint) async {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: {'Content-Type': 'application/json'},
    );
    
    return json.decode(response.body);
  }
  
  static Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode(data),
    );
    
    return json.decode(response.body);
  }
}
```

### 2. Get Menu by Category
```dart
Future<List<Menu>> getMenuByCategory(String category) async {
  final response = await ApiService.get('/v1/menu/kategori/$category');
  
  if (response['success']) {
    return (response['data'] as List)
        .map((item) => Menu.fromJson(item))
        .toList();
  }
  
  throw Exception(response['message']);
}
```

### 3. Create Order
```dart
Future<Order> createOrder(int mejaId, List<OrderItem> items, {String? catatan}) async {
  final orderData = {
    'meja_id': mejaId,
    'catatan': catatan,
    'items': items.map((item) => {
      'menu_id': item.menuId,
      'jumlah': item.jumlah,
      'catatan_item': item.catatanItem,
    }).toList(),
  };
  
  final response = await ApiService.post('/v1/orders', orderData);
  
  if (response['success']) {
    return Order.fromJson(response['data']);
  }
  
  throw Exception(response['message']);
}
```

### 4. Get Meja by QR Code
```dart
Future<Meja> getMejaByQrCode(String qrCode) async {
  final response = await ApiService.get('/v1/meja/qr/$qrCode');
  
  if (response['success']) {
    return Meja.fromJson(response['data']);
  }
  
  throw Exception(response['message']);
}
```

---

## Installation & Setup

1. Clone repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Set database configuration in `.env`
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan db:seed`
8. Start server: `php artisan serve`

Database sudah include sample data:
- 10 meja dengan QR code unik
- 15+ menu items (makanan, minuman, snack)

## QR Code Usage

Setiap meja memiliki QR code unik yang dapat di-scan oleh pelanggan. QR code berisi UUID yang dapat digunakan untuk:

1. Mengidentifikasi meja
2. Mengambil data meja
3. Melihat pesanan aktif di meja tersebut
4. Membuat pesanan baru

Flow pelanggan:
1. Scan QR code → dapat UUID
2. Call API `/v1/meja/qr/{uuid}` → dapat data meja
3. Call API `/v1/menu` → dapat daftar menu
4. Submit pesanan via `/v1/orders`
5. Track status pesanan via `/v1/orders/qr/{uuid}`