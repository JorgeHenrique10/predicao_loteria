<?php

namespace App\Http\Controllers;

use App\Models\Predicao;
use App\Models\Sorteio;
use App\Services\MegaSenaService;
use App\Services\PredictionEngine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private MegaSenaService $megaSenaService,
        private PredictionEngine $predictionEngine,
    ) {}

    /**
     * Tela principal do dashboard.
     */
    public function index()
    {
        $latestSorteio = Sorteio::orderBy('concurso', 'desc')->first();
        $totalSorteios = Sorteio::count();
        $recentPredicoes = Predicao::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'latestSorteio' => $latestSorteio ? [
                'concurso' => $latestSorteio->concurso,
                'data' => $latestSorteio->data->format('d/m/Y'),
                'dezenas' => $latestSorteio->dezenas,
                'acumulou' => $latestSorteio->acumulou,
            ] : null,
            'totalSorteios' => $totalSorteios,
            'recentPredicoes' => $recentPredicoes->map(fn (Predicao $p) => [
                'id' => $p->id,
                'qtd_dezenas' => $p->qtd_dezenas,
                'jogos' => $p->jogos,
                'created_at' => $p->created_at->format('d/m/Y H:i'),
            ]),
        ]);
    }

    /**
     * Sincroniza os resultados com a API da Caixa.
     */
    public function sync()
    {
        try {
            $result = $this->megaSenaService->sync();

            return back()->with('success', "Sincronização concluída! {$result['imported']} novos concursos importados. Total: {$result['total']}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Erro na sincronização: ' . $e->getMessage());
        }
    }

    /**
     * Gera predições de jogos.
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'qtd_dezenas' => 'required|integer|min:6|max:20',
            'qtd_jogos' => 'required|integer|min:1|max:10',
        ]);

        try {
            $result = $this->predictionEngine->generate(
                $validated['qtd_dezenas'],
                $validated['qtd_jogos'],
            );

            // Persistir a predição (RN02)
            Predicao::create([
                'qtd_dezenas' => $validated['qtd_dezenas'],
                'jogos' => $result['jogos'],
            ]);

            return back()->with('success', 'Predição gerada com sucesso!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erro ao gerar predição: ' . $e->getMessage());
        }
    }
}
