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
            'properties.*.property_number' => 'string',
            'properties.*.state' => 'string|size:2',
            'properties.*.city' => 'string',
            'properties.*.neighborhood' => 'string',
            'properties.*.address' => 'string',
            'properties.*.price' => 'numeric',
            'properties.*.appraisal_value' => 'numeric',
            'properties.*.discount' => 'numeric',
            'properties.*.description' => 'string',
            'properties.*.sale_mode' => 'string',
            'properties.*.link' => 'url',
        ]);

        // Inserindo múltiplos imóveis no banco de dados
        $properties = Property::insert($request->properties);

        // Retornar a resposta com os imóveis criados
        return response()->json([
            'message' => 'Imóveis cadastrados com sucesso!',
            'properties' => $properties
        ], 201)
        ->header('Access-Control-Allow-Origin', 'http://localhost:5173', 'https://radar-imobi-spa.vercel.app')  // Adicione o cabeçalho manualmente
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function index(Request $request)
    {
        $query = Property::query();

        if ($request->has('property_number')) {
            $query->where('property_number', $request->property_number);
        }
        if ($request->has('state')) {
            $query->where('state', $request->state);
        }
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        if ($request->has('neighborhood')) {
            $query->where('neighborhood', $request->neighborhood);
        }
        if ($request->has('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->has('price')) {
            $query->where('price', $request->price);
        }
        if ($request->has('appraisal_value')) {
            $query->where('appraisal_value', $request->appraisal_value);
        }
        if ($request->has('discount')) {
            $query->where('discount', $request->discount);
        }
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->has('sale_mode')) {
            $query->where('sale_mode', $request->sale_mode);
        }
        if (empty($request->sale_mode)) {
            $query->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único']);
        }
        if ($request->has('link')) {
            $query->where('link', 'like', '%' . $request->link . '%');
        }

        $properties = $query->paginate(12);

        // Recupera o ID do usuário autenticado
        // Verifica se o usuário está autenticado
        if ($request->user()) {
            $userId = $request->user()->id;

            // Recupera os filtros do usuário
            $userFilters = \DB::table('user_filters')
                ->where('user_id', $userId)
                ->pluck('property_number')
                ->toArray();

            // Adiciona os parâmetros isNotification e id_notification a cada propriedade
            $properties->getCollection()->transform(function ($property) use ($userFilters, $userId) {
                $filter = \DB::table('user_filters')
                    ->where('user_id', $userId)
                    ->where('property_number', $property->property_number)
                    ->first();

                $property->isNotification = !is_null($filter);
                $property->id_notification = $filter ? $filter->id : null;
                return $property;
            });
        }

        // Retorna as propriedades encontradas com o id do filtro e a propriedade booleana de notificação
        return response()->json($properties, 200);
    }

    public function topDiscounted($state, Request $request)
    {
        $query = Property::where('state', $state)
            ->orderBy('discount', 'desc');

        if (empty($request->sale_mode)) {
            $query->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único']);
        }

        // Recuperar os quatro imóveis com maior taxa de desconto
        $properties = $query->take(4)->get();

        // Retornar a resposta com os imóveis encontrados
        return response()->json($properties, 200);
    }

    public function getUniqueSaleModes()
    {
        // Recupera os valores únicos da coluna `sale_mode`, excluindo valores nulos
        $saleModes = Property::select('sale_mode')
                     ->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único'])  // Filtra apenas os valores desejados
                     ->distinct()                 // Garante que os valores sejam únicos
                     ->pluck('sale_mode')         // Extrai os valores da coluna
                     ->toArray();                 // Converte para um array

        // Retorna os valores como um array JSON
        return response()->json($saleModes);
    }
}

