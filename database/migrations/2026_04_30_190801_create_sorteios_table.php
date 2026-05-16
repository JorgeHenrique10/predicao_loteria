<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('concurso')->unique();
            $table->date('data');
            $table->jsonb('dezenas');
            $table->boolean('acumulou')->default(false);
            $table->timestamps();

            $table->index('concurso');
            $table->index('data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteios');
    }
};
