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
            'property_number' => 'nullable|integer',
            'state' => 'nullable|string|size:2',
            'city' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'address' => 'nullable|string',
            'price' => 'nullable|numeric',
            'appraisal_value' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'sale_mode' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        // Criando o filtro
        $userFilter = Filter::create([
            'user_id' => $request->user_id,
            'property_number' => $request->property_number,
            'state' => $request->state,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'address' => $request->address,
            'price' => $request->price,
            'appraisal_value' => $request->appraisal_value,
            'discount' => $request->discount,
            'description' => $request->description,
            'sale_mode' => $request->sale_mode,
            'link' => $request->link,
        ]);

        // Retorna o filtro criado
        return response()->json($userFilter, 201);
    }

    // Método para listar todos os filtros de um usuário
    public function index($userId)
    {
        $filters = Filter::where('user_id', $userId)->get();
        return response()->json($filters, 200);
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



