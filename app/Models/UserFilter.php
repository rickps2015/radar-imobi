<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFilter extends Model
{
    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com a propriedade, caso exista
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_number', 'property_number');
    }
}
