<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    // Método para criar um novo filtro
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'user_id' => 'required|exists:users,id', // Verifica se o usuário existe
            'property_number' => 'nullable|string',
        ]);

        // Verifica se o property_number existe na tabela properties
        if ($request->property_number && !\DB::table('properties')->where('property_number', $request->property_number)->exists()) {
            return response()->json(['message' => 'Não existe este número de propriedade cadastrado!'], 404);
        }

        // Criando o filtro
        $userFilter = Filter::create([
            'user_id' => $request->user_id,
            'property_number' => $request->property_number,
        ]);

        // Executa o endpoint de scraping
        app('App\Http\Controllers\ScrapingController')->postLeilaoData($request->property_number);

        // Retorna o filtro criado
        return response()->json($userFilter, 201);
    }

    // Método para listar todos os filtros de um usuário
    public function index($userId)
    {
        // Obtém os filtros do usuário
        $userFilters = Filter::where('user_id', $userId)->get(['id', 'property_number']);

        // Busca as propriedades correspondentes na tabela properties
        $propertyNumbers = $userFilters->pluck('property_number');
        $properties = \DB::table('properties')->whereIn('property_number', $propertyNumbers)->get();

        // Adiciona o id do filtro a cada propriedade
        $properties = $properties->map(function ($property) use ($userFilters) {
            $filter = $userFilters->firstWhere('property_number', $property->property_number);
            $property->filter_id = $filter->id;
            return $property;
        });

        // Retorna as propriedades encontradas com o id do filtro
        return response()->json($properties, 200);
    }

    // Método para remover um filtro específico
    public function destroy($id)
    {
        $filter = Filter::find($id);

        if (!$filter) {
            return response()->json(['message' => 'Filter not found'], 404);
        }

        $filter->delete();

        return response()->json(['message' => 'Filtro deletado com sucesso!'], 200);
    }
}



