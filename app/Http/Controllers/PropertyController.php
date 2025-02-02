<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Cadastrar múltiplos imóveis.
     */
    public function store(Request $request)
    {
        // Validando os dados de entrada
        $request->validate([
            'properties' => 'required|array',
            'properties.*.property_number' => 'required|integer|unique:properties,property_number',
            'properties.*.state' => 'required|string|size:2',
            'properties.*.city' => 'required|string',
            'properties.*.neighborhood' => 'required|string',
            'properties.*.address' => 'required|string',
            'properties.*.price' => 'required|numeric',
            'properties.*.appraisal_value' => 'required|numeric',
            'properties.*.discount' => 'required|numeric',
            'properties.*.description' => 'required|string',
            'properties.*.sale_mode' => 'required|string',
            'properties.*.link' => 'required|url',
        ]);

        // Inserindo múltiplos imóveis no banco de dados
        $properties = Property::insert($request->properties);

        // Retornar a resposta com os imóveis criados
        return response()->json([
            'message' => 'Imóveis cadastrados com sucesso!',
            'properties' => $properties
        ], 201);
    }
}

