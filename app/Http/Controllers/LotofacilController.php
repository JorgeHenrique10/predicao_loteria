<?php

namespace App\Http\Controllers;

use App\Models\PredicaoLotofacil;
use App\Models\SorteioLotofacil;
use App\Services\LotofacilPredictionEngine;
use App\Services\LotofacilService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LotofacilController extends Controller
{
    public function __construct(
        private LotofacilService $lotofacilService,
        private LotofacilPredictionEngine $predictionEngine,
    ) {}

    /**
     * Tela principal da Lotofácil.
     */
    public function index()
    {
        $latestSorteio = SorteioLotofacil::orderBy('concurso', 'desc')->first();
        $totalSorteios = SorteioLotofacil::count();
        $recentPredicoes = PredicaoLotofacil::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Lotofacil', [
            'latestSorteio' => $latestSorteio ? [
                'concurso' => $latestSorteio->concurso,
                'data' => $latestSorteio->data->format('d/m/Y'),
                'dezenas' => $latestSorteio->dezenas,
                'acumulou' => $latestSorteio->acumulou,
            ] : null,
            'totalSorteios' => $totalSorteios,
            'recentPredicoes' => $recentPredicoes->map(fn (PredicaoLotofacil $p) => [
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
            $result = $this->lotofacilService->sync();

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
            'qtd_dezenas' => 'required|integer|min:15|max:20',
            'qtd_jogos' => 'required|integer|min:1|max:10',
        ]);

        try {
            $result = $this->predictionEngine->generate(
                $validated['qtd_dezenas'],
                $validated['qtd_jogos'],
            );

            // Persistir a predição
            PredicaoLotofacil::create([
                'qtd_dezenas' => $validated['qtd_dezenas'],
                'jogos' => $result['jogos'],
            ]);

            return back()->with('success', 'Predição gerada com sucesso!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erro ao gerar predição: ' . $e->getMessage());
        }
    }
}
