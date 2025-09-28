<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $meja = Meja::where('is_active', true)->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Data meja berhasil diambil',
                'data' => $meja
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data meja',
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
                'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja',
                'is_active' => 'boolean'
            ]);

            $validatedData['qr_code'] = Str::uuid()->toString();

            $meja = Meja::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Meja berhasil ditambahkan',
                'data' => $meja
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
                'message' => 'Terjadi kesalahan saat menambahkan meja',
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
            $meja = Meja::find($id);

            if (!$meja) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data meja berhasil diambil',
                'data' => $meja
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data meja',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get meja by QR code.
     */
    public function getByQrCode(string $qrCode): JsonResponse
    {
        try {
            $meja = Meja::where('qr_code', $qrCode)
                        ->where('is_active', true)
                        ->first();

            if (!$meja) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau meja tidak aktif'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data meja berhasil diambil',
                'data' => $meja
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data meja',
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
            $meja = Meja::find($id);

            if (!$meja) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'nomor_meja' => 'string|max:10|unique:meja,nomor_meja,' . $id,
                'is_active' => 'boolean'
            ]);

            $meja->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Meja berhasil diupdate',
                'data' => $meja
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
                'message' => 'Terjadi kesalahan saat mengupdate meja',
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
            $meja = Meja::find($id);

            if (!$meja) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja tidak ditemukan'
                ], 404);
            }

            $meja->delete();

            return response()->json([
                'success' => true,
                'message' => 'Meja berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus meja',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
