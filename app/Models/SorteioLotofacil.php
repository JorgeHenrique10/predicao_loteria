<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SorteioLotofacil extends Model
{
    use HasUuids;

    protected $table = 'sorteios_lotofacil';

    protected $fillable = [
        'concurso',
        'data',
        'dezenas',
        'acumulou',
    ];

    protected function casts(): array
    {
        return [
            'concurso' => 'integer',
            'data' => 'date',
            'dezenas' => 'array',
            'acumulou' => 'boolean',
        ];
    }
}
