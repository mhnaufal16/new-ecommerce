<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Return a list of cities for a given province id.
     * If RAJAONGKIR_API_KEY is set in env, fetch from RajaOngkir API.
     * Otherwise fall back to an in-memory map.
     */
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');

        // If user configured RajaOngkir, attempt to fetch remote list
        $apiKey = config('services.rajaongkir.key') ?: env('RAJAONGKIR_API_KEY');
        $base = config('services.rajaongkir.base_url') ?: env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');

        if ($apiKey && $provinceId) {
            try {
                // RajaOngkir expects header 'key' with API key for Starter/Pro
                $resp = Http::withHeaders(['key' => $apiKey, 'Accept' => 'application/json'])
                    ->timeout(5)
                    ->get(rtrim($base, '/') . '/city', ['province' => $provinceId]);

                if ($resp->ok()) {
                    $body = $resp->json();

                    // Normalize response: support RajaOngkir v2 structure
                    $results = $body['rajaongkir']['results'] ?? $body['results'] ?? [];

                    $cities = array_map(function ($c) {
                        // RajaOngkir may provide city_id or city_id numeric string
                        $id = $c['city_id'] ?? $c['id'] ?? ($c['city_id'] ?? null);
                        $name = $c['city_name'] ?? $c['name'] ?? ($c['city'] ?? null);
                        return ['id' => (int) $id, 'name' => $name];
                    }, $results ?: []);

                    return response()->json($cities);
                }
            } catch (\Exception $e) {
                // ignore and fall back to local map
            }
        }

        // Local fallback map
        $map = [
            5 => [
                ['id' => 501, 'name' => 'Yogyakarta'],
                ['id' => 502, 'name' => 'Sleman'],
                ['id' => 503, 'name' => 'Bantul'],
            ],
            6 => [
                ['id' => 151, 'name' => 'Jakarta Selatan'],
                ['id' => 152, 'name' => 'Jakarta Pusat'],
                ['id' => 153, 'name' => 'Jakarta Barat'],
            ],
            11 => [
                ['id' => 444, 'name' => 'Surabaya'],
                ['id' => 445, 'name' => 'Malang'],
                ['id' => 446, 'name' => 'Sidoarjo'],
            ],
            10 => [
                ['id' => 1001, 'name' => 'Semarang'],
                ['id' => 1002, 'name' => 'Surakarta'],
                ['id' => 1003, 'name' => 'Magelang'],
            ],
            9 => [
                ['id' => 9001, 'name' => 'Bandung'],
                ['id' => 9002, 'name' => 'Bekasi'],
                ['id' => 9003, 'name' => 'Bogor'],
            ],
        ];

        $cities = $map[(int) $provinceId] ?? [];

        return response()->json($cities);
    }
}
