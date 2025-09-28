<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Order::with(['meja', 'orderDetails.menu']);

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by meja if provided
            if ($request->has('meja_id')) {
                $query->where('meja_id', $request->meja_id);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data pesanan berhasil diambil',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'meja_id' => 'required|exists:meja,id',
                'catatan' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.menu_id' => 'required|exists:menu,id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.catatan_item' => 'nullable|string'
            ]);

            // Validate meja is active
            $meja = Meja::find($validatedData['meja_id']);
            if (!$meja->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja tidak aktif'
                ], 400);
            }

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'meja_id' => $validatedData['meja_id'],
                'order_number' => $orderNumber,
                'catatan' => $validatedData['catatan'] ?? null,
                'waktu_pesan' => Carbon::now(),
                'status' => 'pending',
                'total_harga' => 0
            ]);

            $totalHarga = 0;

            // Create order details
            foreach ($validatedData['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                
                if (!$menu->is_available) {
                    throw new \Exception("Menu {$menu->nama} sedang tidak tersedia");
                }

                $subtotal = $menu->harga * $item['jumlah'];
                $totalHarga += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $menu->harga,
                    'subtotal' => $subtotal,
                    'catatan_item' => $item['catatan_item'] ?? null
                ]);
            }

            // Update total harga
            $order->update(['total_harga' => $totalHarga]);

            // Load relationships
            $order->load(['meja', 'orderDetails.menu']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $order
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $order = Order::with(['meja', 'orderDetails.menu'])->find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pesanan berhasil diambil',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'status' => 'required|in:pending,processing,ready,completed,cancelled'
            ]);

            $order->update(['status' => $validatedData['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate',
                'data' => $order
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate status pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders by meja ID.
     */
    public function getByMeja(string $mejaId): JsonResponse
    {
        try {
            $orders = Order::with(['orderDetails.menu'])
                          ->where('meja_id', $mejaId)
                          ->orderBy('created_at', 'desc')
                          ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data pesanan berhasil diambil',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active orders by meja QR code.
     */
    public function getByQrCode(string $qrCode): JsonResponse
    {
        try {
            $meja = Meja::where('qr_code', $qrCode)->first();

            if (!$meja) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid'
                ], 404);
            }

            $orders = Order::with(['orderDetails.menu'])
                          ->where('meja_id', $meja->id)
                          ->whereIn('status', ['pending', 'processing', 'ready'])
                          ->orderBy('created_at', 'desc')
                          ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data pesanan aktif berhasil diambil',
                'data' => [
                    'meja' => $meja,
                    'orders' => $orders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            // Only allow deletion if order is pending or cancelled
            if (!in_array($order->status, ['pending', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat dihapus karena sedang diproses'
                ], 400);
            }

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
