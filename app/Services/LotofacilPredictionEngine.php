<?php

namespace App\Services;

use App\Models\SorteioLotofacil;

class LotofacilPredictionEngine
{
    private const WEIGHT_FREQUENCY = 0.35;
    private const WEIGHT_DELAY = 0.30;
    private const WEIGHT_TREND = 0.35;
    private const RECENT_DRAWS = 30;
    private const MIN_DEZENA = 1;
    private const MAX_DEZENA = 25;

    /**
     * Gera jogos preditos baseados em análise estatística da Lotofácil.
     *
     * @param int $qtdDezenas Quantidade de dezenas por jogo (15-20)
     * @param int $qtdJogos Quantidade de jogos a gerar
     * @return array{jogos: array, scores: array}
     */
    public function generate(int $qtdDezenas, int $qtdJogos): array
    {
        $sorteios = SorteioLotofacil::orderBy('concurso', 'desc')->get();

        if ($sorteios->isEmpty()) {
            throw new \RuntimeException('Nenhum sorteio na base de dados. Sincronize os resultados primeiro.');
        }

        $scores = $this->calculateScores($sorteios);
        $jogos = [];

        for ($i = 0; $i < $qtdJogos; $i++) {
            $jogos[] = $this->generateSingleGame($scores, $qtdDezenas);
        }

        return [
            'jogos' => $jogos,
            'scores' => $scores,
        ];
    }

    /**
     * Calcula o score composto para cada dezena (01-25).
     */
    private function calculateScores($sorteios): array
    {
        $totalSorteios = $sorteios->count();

        // 1. Frequência absoluta
        $frequency = array_fill(self::MIN_DEZENA, self::MAX_DEZENA, 0);
        foreach ($sorteios as $sorteio) {
            foreach ($sorteio->dezenas as $dezena) {
                $num = (int) $dezena;
                if ($num >= self::MIN_DEZENA && $num <= self::MAX_DEZENA) {
                    $frequency[$num]++;
                }
            }
        }

        // 2. Atraso (quantos sorteios desde a última aparição)
        $delay = array_fill(self::MIN_DEZENA, self::MAX_DEZENA, $totalSorteios);
        foreach ($sorteios as $index => $sorteio) {
            foreach ($sorteio->dezenas as $dezena) {
                $num = (int) $dezena;
                if ($num >= self::MIN_DEZENA && $num <= self::MAX_DEZENA) {
                    // Só registra a primeira ocorrência (sorteio mais recente)
                    if ($delay[$num] === $totalSorteios) {
                        $delay[$num] = $index; // 0 = mais recente
                    }
                }
            }
        }

        // 3. Tendência (frequência nos últimos N sorteios)
        $recentSorteios = $sorteios->take(self::RECENT_DRAWS);
        $recentFreq = array_fill(self::MIN_DEZENA, self::MAX_DEZENA, 0);
        foreach ($recentSorteios as $sorteio) {
            foreach ($sorteio->dezenas as $dezena) {
                $num = (int) $dezena;
                if ($num >= self::MIN_DEZENA && $num <= self::MAX_DEZENA) {
                    $recentFreq[$num]++;
                }
            }
        }

        // Normalizar e calcular score composto
        $maxFreq = max($frequency) ?: 1;
        $maxDelay = max($delay) ?: 1;
        $maxRecentFreq = max($recentFreq) ?: 1;

        $scores = [];
        for ($n = self::MIN_DEZENA; $n <= self::MAX_DEZENA; $n++) {
            $normalizedFreq = $frequency[$n] / $maxFreq;
            $normalizedDelay = $delay[$n] / $maxDelay;
            $normalizedTrend = $recentFreq[$n] / $maxRecentFreq;

            $score = ($normalizedFreq * self::WEIGHT_FREQUENCY)
                   + ($normalizedDelay * self::WEIGHT_DELAY)
                   + ($normalizedTrend * self::WEIGHT_TREND);

            $scores[$n] = [
                'dezena' => str_pad($n, 2, '0', STR_PAD_LEFT),
                'score' => round($score, 4),
                'frequencia' => $frequency[$n],
                'atraso' => $delay[$n],
                'tendencia' => $recentFreq[$n],
            ];
        }

        // Ordenar por score decrescente
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

        return $scores;
    }

    /**
     * Gera um único jogo usando seleção ponderada por score.
     */
    private function generateSingleGame(array $scores, int $qtdDezenas): array
    {
        // Criar pool ponderado
        $pool = [];
        foreach ($scores as $item) {
            $pool[] = [
                'dezena' => $item['dezena'],
                'weight' => $item['score'],
            ];
        }

        $selected = [];
        $remainingPool = $pool;

        for ($i = 0; $i < $qtdDezenas; $i++) {
            if (empty($remainingPool)) {
                break;
            }

            $dezena = $this->weightedRandomSelect($remainingPool);
            $selected[] = $dezena;

            // Remover a dezena selecionada do pool (unicidade)
            $remainingPool = array_filter(
                $remainingPool,
                fn($item) => $item['dezena'] !== $dezena
            );
            $remainingPool = array_values($remainingPool);
        }

        // Ordenar numericamente
        sort($selected);

        return $selected;
    }

    /**
     * Seleção aleatória ponderada pelo score.
     */
    private function weightedRandomSelect(array $pool): string
    {
        $totalWeight = array_sum(array_column($pool, 'weight'));

        if ($totalWeight <= 0) {
            // Fallback para seleção uniforme
            return $pool[array_rand($pool)]['dezena'];
        }

        $rand = mt_rand() / mt_getrandmax() * $totalWeight;
        $cumulative = 0;

        foreach ($pool as $item) {
            $cumulative += $item['weight'];
            if ($rand <= $cumulative) {
                return $item['dezena'];
            }
        }

        return $pool[count($pool) - 1]['dezena'];
    }
}
