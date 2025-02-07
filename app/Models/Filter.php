<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'user_filters'; // Nome da tabela

    protected $fillable = [
        'user_id', // ID do usuário
        'property_number', // Número da propriedade
        'state', // Estado
        'city', // Cidade
        'neighborhood', // Bairro
        'address', // Endereço
        'price', // Preço
        'appraisal_value', // Valor de avaliação
        'discount', // Desconto
        'description', // Descrição
        'sale_mode', // Modo de venda
        'link', // Link
    ];

    // Relacionamento com a tabela users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




