<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('user_filters', function (Blueprint $table) {
            $table->id(); // ID único para cada filtro
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relacionamento com a tabela de usuários
            $table->integer('property_number')->nullable(); // Número da propriedade
            $table->string('state', 2)->nullable(); // Estado
            $table->string('city')->nullable(); // Cidade
            $table->string('neighborhood')->nullable(); // Bairro
            $table->string('address')->nullable(); // Endereço
            $table->decimal('price', 10, 2)->nullable(); // Preço
            $table->decimal('appraisal_value', 10, 2)->nullable(); // Valor de avaliação
            $table->decimal('discount', 5, 2)->nullable(); // Desconto
            $table->text('description')->nullable(); // Descrição
            $table->string('sale_mode')->nullable(); // Modo de venda
            $table->string('link')->nullable(); // Link
            $table->timestamps(); // Data de criação e atualização
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_filters');
    }
}


