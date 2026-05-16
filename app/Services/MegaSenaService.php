<?php

namespace App\Services;

use App\Models\Sorteio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MegaSenaService
{
    private const API_URL = 'https://loteriascaixa-api.herokuapp.com/api/megasena';

    /**
     * Sincroniza os resultados da Mega-Sena com a base local.
     *
     * @return array{imported: int, latest: int|null, total: int}
     */
    public function sync(): array
    {
        $response = Http::timeout(30)->get(self::API_URL);

        if (!$response->successful()) {
            Log::error('MegaSenaService: Falha ao consultar API', [
                'status' => $response->status(),
            ]);
            throw new \RuntimeException('Falha ao consultar API da Caixa. Status: ' . $response->status());
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('Resposta da API em formato inesperado.');
        }

        // Buscar concursos já existentes
        $existingConcursos = Sorteio::pluck('concurso')->toArray();

        $imported = 0;
        $toInsert = [];

        foreach ($data as $sorteio) {
            $concurso = (int) $sorteio['concurso'];

            if (in_array($concurso, $existingConcursos)) {
                continue;
            }

            $toInsert[] = [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'concurso' => $concurso,
                'data' => Carbon::createFromFormat('d/m/Y', $sorteio['data'])->format('Y-m-d'),
                'dezenas' => json_encode($sorteio['dezenas']),
                'acumulou' => (bool) $sorteio['acumulou'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $imported++;
        }

        // Insert em chunks para melhor performance
        if (!empty($toInsert)) {
            foreach (array_chunk($toInsert, 100) as $chunk) {
                Sorteio::insert($chunk);
            }
        }

        $latest = Sorteio::orderBy('concurso', 'desc')->first();

        return [
            'imported' => $imported,
            'latest' => $latest?->concurso,
            'total' => Sorteio::count(),
        ];
    }
}
