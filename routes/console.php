<?php

use App\Services\MegaSenaService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduled Tasks
|--------------------------------------------------------------------------
| Sincronização automática da Mega-Sena após dias de sorteio (qua/sáb).
*/

Artisan::command('megasena:sync', function () {
    $service = app(MegaSenaService::class);
    $result = $service->sync();
    $this->info("Sincronização concluída: {$result['imported']} importados, total: {$result['total']}");
})->purpose('Sincronizar resultados da Mega-Sena com a API da Caixa');

Schedule::command('megasena:sync')
    ->wednesdays()->at('22:00');

Schedule::command('megasena:sync')
    ->saturdays()->at('22:00');
