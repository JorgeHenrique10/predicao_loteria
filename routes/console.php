<?php

use App\Services\LotofacilService;
use App\Services\MegaSenaService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduled Tasks
|--------------------------------------------------------------------------
| Sincronização automática das loterias após dias de sorteio.
*/

// ===== Mega-Sena =====
Artisan::command('megasena:sync', function () {
    $service = app(MegaSenaService::class);
    $result = $service->sync();
    $this->info("Sincronização concluída: {$result['imported']} importados, total: {$result['total']}");
})->purpose('Sincronizar resultados da Mega-Sena com a API da Caixa');

Schedule::command('megasena:sync')
    ->wednesdays()->at('22:00');

Schedule::command('megasena:sync')
    ->saturdays()->at('22:00');

// ===== Lotofácil =====
Artisan::command('lotofacil:sync', function () {
    $service = app(LotofacilService::class);
    $result = $service->sync();
    $this->info("Sincronização concluída: {$result['imported']} importados, total: {$result['total']}");
})->purpose('Sincronizar resultados da Lotofácil com a API da Caixa');

Schedule::command('lotofacil:sync')
    ->mondays()->at('22:00');

Schedule::command('lotofacil:sync')
    ->wednesdays()->at('22:00');

Schedule::command('lotofacil:sync')
    ->fridays()->at('22:00');
