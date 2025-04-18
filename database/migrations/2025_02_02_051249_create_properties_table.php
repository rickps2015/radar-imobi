<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_number'); // Número único do imóvel
            $table->string('state', 2); // UF
            $table->string('city'); // Cidade
            $table->string('neighborhood'); // Bairro
            $table->string('address'); // Endereço
            $table->decimal('price', 15, 2); // Preço do imóvel
            $table->decimal('appraisal_value', 15, 2); // Valor de avaliação
            $table->decimal('discount', 5, 2); // Desconto
            $table->text('description'); // Descrição
            $table->string('type_imovel')->nullable(); // Tipo do imóvel
            $table->string('sale_mode'); // Modalidade de venda
            $table->string('link'); // Link de acesso
            $table->dateTime('primary_leilao_data')->nullable(); // Data do primeiro leilão
            $table->time('primary_leilao_hora')->nullable(); // Hora do primeiro leilão
            $table->dateTime('second_leilao_data')->nullable(); // Data do segundo leilão
            $table->time('second_leilao_hora')->nullable(); // Hora do segundo leilão
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
