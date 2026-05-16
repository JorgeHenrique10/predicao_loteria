<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PredicaoLotofacil extends Model
{
    use HasUuids;

    protected $table = 'predicoes_lotofacil';

    protected $fillable = [
        'qtd_dezenas',
        'jogos',
    ];

    protected function casts(): array
    {
        return [
            'qtd_dezenas' => 'integer',
            'jogos' => 'array',
        ];
    }
}
