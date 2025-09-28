# Test API Endpoints

Berikut adalah contoh penggunaan API menggunakan curl atau tools testing API lainnya:

## 1. Test Health Check
```bash
curl -X GET http://localhost:8000/api/health
```

## 2. Get All Meja
```bash
curl -X GET http://localhost:8000/api/v1/meja
```

## 3. Get Menu by Category
```bash
# Get semua makanan
curl -X GET http://localhost:8000/api/v1/menu/kategori/makanan

# Get semua minuman  
curl -X GET http://localhost:8000/api/v1/menu/kategori/minuman

# Get semua snack
curl -X GET http://localhost:8000/api/v1/menu/kategori/snack
```

## 4. Get Meja by QR Code
```bash
# Ganti {qr-code} dengan QR code dari database
curl -X GET http://localhost:8000/api/v1/meja/qr/{qr-code}
```

## 5. Create Order
```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{
    "meja_id": 1,
    "catatan": "Pedasnya sedang",
    "items": [
      {
        "menu_id": 1,
        "jumlah": 1,
        "catatan_item": "Tanpa kerupuk"
      },
      {
        "menu_id": 6,
        "jumlah": 2,
        "catatan_item": null
      }
    ]
  }'
```

## 6. Get Orders by Meja
```bash
curl -X GET http://localhost:8000/api/v1/orders/meja/1
```

## 7. Update Order Status
```bash
curl -X PATCH http://localhost:8000/api/v1/orders/1/status \
  -H "Content-Type: application/json" \
  -d '{"status": "processing"}'
```

## 8. Get Active Orders by QR Code
```bash
# Ganti {qr-code} dengan QR code dari database
curl -X GET http://localhost:8000/api/v1/orders/qr/{qr-code}
```

## Untuk Flutter Developer

### Model Classes

```dart
// models/meja.dart
class Meja {
  final int id;
  final String nomorMeja;
  final String qrCode;
  final bool isActive;
  final DateTime createdAt;
  final DateTime updatedAt;

  Meja({
    required this.id,
    required this.nomorMeja,
    required this.qrCode,
    required this.isActive,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Meja.fromJson(Map<String, dynamic> json) {
    return Meja(
      id: json['id'],
      nomorMeja: json['nomor_meja'],
      qrCode: json['qr_code'],
      isActive: json['is_active'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}

// models/menu.dart
class Menu {
  final int id;
  final String nama;
  final String? deskripsi;
  final double harga;
  final String kategori;
  final String? gambar;
  final bool isAvailable;
  final DateTime createdAt;
  final DateTime updatedAt;

  Menu({
    required this.id,
    required this.nama,
    this.deskripsi,
    required this.harga,
    required this.kategori,
    this.gambar,
    required this.isAvailable,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Menu.fromJson(Map<String, dynamic> json) {
    return Menu(
      id: json['id'],
      nama: json['nama'],
      deskripsi: json['deskripsi'],
      harga: double.parse(json['harga']),
      kategori: json['kategori'],
      gambar: json['gambar'],
      isAvailable: json['is_available'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}

// models/order.dart
class Order {
  final int id;
  final int mejaId;
  final String orderNumber;
  final double totalHarga;
  final String status;
  final String? catatan;
  final DateTime waktuPesan;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Meja? meja;
  final List<OrderDetail>? orderDetails;

  Order({
    required this.id,
    required this.mejaId,
    required this.orderNumber,
    required this.totalHarga,
    required this.status,
    this.catatan,
    required this.waktuPesan,
    required this.createdAt,
    required this.updatedAt,
    this.meja,
    this.orderDetails,
  });

  factory Order.fromJson(Map<String, dynamic> json) {
    return Order(
      id: json['id'],
      mejaId: json['meja_id'],
      orderNumber: json['order_number'],
      totalHarga: double.parse(json['total_harga']),
      status: json['status'],
      catatan: json['catatan'],
      waktuPesan: DateTime.parse(json['waktu_pesan']),
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      meja: json['meja'] != null ? Meja.fromJson(json['meja']) : null,
      orderDetails: json['order_details'] != null 
          ? (json['order_details'] as List)
              .map((item) => OrderDetail.fromJson(item))
              .toList()
          : null,
    );
  }
}

// models/order_detail.dart
class OrderDetail {
  final int id;
  final int orderId;
  final int menuId;
  final int jumlah;
  final double hargaSatuan;
  final double subtotal;
  final String? catatanItem;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Menu? menu;

  OrderDetail({
    required this.id,
    required this.orderId,
    required this.menuId,
    required this.jumlah,
    required this.hargaSatuan,
    required this.subtotal,
    this.catatanItem,
    required this.createdAt,
    required this.updatedAt,
    this.menu,
  });

  factory OrderDetail.fromJson(Map<String, dynamic> json) {
    return OrderDetail(
      id: json['id'],
      orderId: json['order_id'],
      menuId: json['menu_id'],
      jumlah: json['jumlah'],
      hargaSatuan: double.parse(json['harga_satuan']),
      subtotal: double.parse(json['subtotal']),
      catatanItem: json['catatan_item'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      menu: json['menu'] != null ? Menu.fromJson(json['menu']) : null,
    );
  }
}
```

### API Service Class

```dart
// services/api_service.dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/meja.dart';
import '../models/menu.dart';
import '../models/order.dart';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';

  // Helper method untuk HTTP requests
  static Future<Map<String, dynamic>> _request(
    String method,
    String endpoint, {
    Map<String, dynamic>? data,
  }) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    late http.Response response;

    switch (method.toUpperCase()) {
      case 'GET':
        response = await http.get(
          uri,
          headers: {'Content-Type': 'application/json'},
        );
        break;
      case 'POST':
        response = await http.post(
          uri,
          headers: {'Content-Type': 'application/json'},
          body: data != null ? json.encode(data) : null,
        );
        break;
      case 'PATCH':
        response = await http.patch(
          uri,
          headers: {'Content-Type': 'application/json'},
          body: data != null ? json.encode(data) : null,
        );
        break;
      default:
        throw Exception('Unsupported HTTP method: $method');
    }

    final responseData = json.decode(response.body);

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return responseData;
    } else {
      throw Exception(responseData['message'] ?? 'Unknown error occurred');
    }
  }

  // Meja APIs
  static Future<List<Meja>> getAllMeja() async {
    final response = await _request('GET', '/v1/meja');
    return (response['data'] as List)
        .map((item) => Meja.fromJson(item))
        .toList();
  }

  static Future<Meja> getMejaByQrCode(String qrCode) async {
    final response = await _request('GET', '/v1/meja/qr/$qrCode');
    return Meja.fromJson(response['data']);
  }

  // Menu APIs
  static Future<List<Menu>> getAllMenu({String? kategori}) async {
    String endpoint = '/v1/menu';
    if (kategori != null) {
      endpoint = '/v1/menu/kategori/$kategori';
    }
    
    final response = await _request('GET', endpoint);
    return (response['data'] as List)
        .map((item) => Menu.fromJson(item))
        .toList();
  }

  // Order APIs
  static Future<Order> createOrder({
    required int mejaId,
    required List<Map<String, dynamic>> items,
    String? catatan,
  }) async {
    final orderData = {
      'meja_id': mejaId,
      'items': items,
      if (catatan != null) 'catatan': catatan,
    };

    final response = await _request('POST', '/v1/orders', data: orderData);
    return Order.fromJson(response['data']);
  }

  static Future<List<Order>> getOrdersByMeja(int mejaId) async {
    final response = await _request('GET', '/v1/orders/meja/$mejaId');
    return (response['data'] as List)
        .map((item) => Order.fromJson(item))
        .toList();
  }

  static Future<Map<String, dynamic>> getActiveOrdersByQrCode(String qrCode) async {
    final response = await _request('GET', '/v1/orders/qr/$qrCode');
    return {
      'meja': Meja.fromJson(response['data']['meja']),
      'orders': (response['data']['orders'] as List)
          .map((item) => Order.fromJson(item))
          .toList(),
    };
  }

  static Future<Order> updateOrderStatus(int orderId, String status) async {
    final response = await _request(
      'PATCH',
      '/v1/orders/$orderId/status',
      data: {'status': status},
    );
    return Order.fromJson(response['data']);
  }
}
```

### Usage Example in Flutter Widget

```dart
// screens/menu_screen.dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/menu.dart';
import '../models/meja.dart';

class MenuScreen extends StatefulWidget {
  final Meja meja;

  const MenuScreen({Key? key, required this.meja}) : super(key: key);

  @override
  _MenuScreenState createState() => _MenuScreenState();
}

class _MenuScreenState extends State<MenuScreen> {
  List<Menu> menuItems = [];
  List<Map<String, dynamic>> cartItems = [];
  bool isLoading = true;
  String selectedCategory = 'makanan';

  @override
  void initState() {
    super.initState();
    loadMenu();
  }

  Future<void> loadMenu() async {
    try {
      setState(() => isLoading = true);
      final items = await ApiService.getAllMenu(kategori: selectedCategory);
      setState(() {
        menuItems = items;
        isLoading = false;
      });
    } catch (e) {
      setState(() => isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    }
  }

  Future<void> submitOrder() async {
    if (cartItems.isEmpty) return;

    try {
      final order = await ApiService.createOrder(
        mejaId: widget.meja.id,
        items: cartItems,
      );

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Pesanan berhasil! Nomor: ${order.orderNumber}')),
      );

      setState(() => cartItems.clear());
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    }
  }

  void addToCart(Menu menu) {
    final existingIndex = cartItems.indexWhere((item) => item['menu_id'] == menu.id);
    
    if (existingIndex >= 0) {
      setState(() {
        cartItems[existingIndex]['jumlah']++;
      });
    } else {
      setState(() {
        cartItems.add({
          'menu_id': menu.id,
          'jumlah': 1,
          'catatan_item': null,
        });
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Menu - Meja ${widget.meja.nomorMeja}'),
        actions: [
          IconButton(
            icon: Badge(
              label: Text('${cartItems.length}'),
              child: Icon(Icons.shopping_cart),
            ),
            onPressed: cartItems.isEmpty ? null : submitOrder,
          ),
        ],
      ),
      body: Column(
        children: [
          // Category tabs
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: ['makanan', 'minuman', 'snack'].map((category) {
              return ElevatedButton(
                onPressed: () {
                  setState(() => selectedCategory = category);
                  loadMenu();
                },
                child: Text(category.toUpperCase()),
                style: ElevatedButton.styleFrom(
                  backgroundColor: selectedCategory == category 
                      ? Theme.of(context).primaryColor 
                      : Colors.grey,
                ),
              );
            }).toList(),
          ),
          
          // Menu list
          Expanded(
            child: isLoading
                ? Center(child: CircularProgressIndicator())
                : ListView.builder(
                    itemCount: menuItems.length,
                    itemBuilder: (context, index) {
                      final menu = menuItems[index];
                      return ListTile(
                        title: Text(menu.nama),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (menu.deskripsi != null) Text(menu.deskripsi!),
                            Text('Rp ${menu.harga.toInt()}'),
                          ],
                        ),
                        trailing: IconButton(
                          icon: Icon(Icons.add_shopping_cart),
                          onPressed: () => addToCart(menu),
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
```