<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Menu::query();

            // Filter by category if provided
            if ($request->has('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter by availability if provided
            if ($request->has('available')) {
                $query->where('is_available', $request->boolean('available'));
            }

            $menu = $query->where('is_available', true)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data menu berhasil diambil',
                'data' => $menu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'kategori' => 'required|in:makanan,minuman,snack',
                'gambar' => 'nullable|string',
                'is_available' => 'boolean'
            ]);

            $menu = Menu::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan',
                'data' => $menu
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan menu',
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
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data menu berhasil diambil',
                'data' => $menu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get menu by category.
     */
    public function getByCategory(string $kategori): JsonResponse
    {
        try {
            $validCategories = ['makanan', 'minuman', 'snack'];
            
            if (!in_array($kategori, $validCategories)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid'
                ], 400);
            }

            $menu = Menu::where('kategori', $kategori)
                        ->where('is_available', true)
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data menu berhasil diambil',
                'data' => $menu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'nama' => 'string|max:255',
                'deskripsi' => 'nullable|string',
                'harga' => 'numeric|min:0',
                'kategori' => 'in:makanan,minuman,snack',
                'gambar' => 'nullable|string',
                'is_available' => 'boolean'
            ]);

            $menu->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diupdate',
                'data' => $menu
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
                'message' => 'Terjadi kesalahan saat mengupdate menu',
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
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
