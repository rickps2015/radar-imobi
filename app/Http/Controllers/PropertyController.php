<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

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
        if ($request->filled('discount_range_primary') && $request->filled('discount_range_secondary')) {
            $discountRangePrimary = (float) $request->input('discount_range_primary');
            $discountRangeSecond = (float) $request->input('discount_range_secondary');

            if ($discountRangePrimary <= $discountRangeSecond) {
                $query->whereBetween('discount', [$discountRangePrimary, $discountRangeSecond]);
            }
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
        if ($request->has('order_by')) {
            $orderBy = $request->order_by;

            switch ($orderBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'discount_asc':
                $query->orderBy('discount', 'asc');
                break;
            case 'discount_desc':
                $query->orderBy('discount', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
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

        // Se o array de propriedades for vazio, realizar a mesma pesquisa para o estado de Alagoas (AL)
        if ($query->take(4)->get()->isEmpty()) {
            $query = Property::where('state', 'AL')
            ->orderBy('discount', 'desc');

            if (empty($request->sale_mode)) {
            $query->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único']);
            }

            $properties = $query->take(4)->get();
        }

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

    public function getPropertiesCountByState()
    {
        // Consulta para contar a quantidade de propriedades por estado
        $propertiesCount = Property::select('state', \DB::raw('COUNT(*) as qtd_properties'))
            ->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único'])
            ->groupBy('state')
            ->get();

        // Consulta para contar a quantidade de propriedades por cidade
        $propertiesCountByCity = Property::select('state', 'city', \DB::raw('COUNT(*) as qtd_properties'))
            ->whereIn('sale_mode', ['Licitação Aberta', 'Leilão SFI - Edital Único'])
            ->groupBy('state', 'city')
            ->get();

        // Adiciona os dados de cidade ao resultado por estado
        $propertiesCount->transform(function ($stateData) use ($propertiesCountByCity) {
            $stateData->data_city = $propertiesCountByCity->filter(function ($cityData) use ($stateData) {
                return $cityData->state === $stateData->state;
            })->values();
            return $stateData;
        });

        // Retorna o resultado como um array de objetos JSON
        return response()->json($propertiesCount, 200);
    }

    public function getLocation(Request $request)
    {
        $ip = $request->ip();

        // Para testar localmente (pois 127.0.0.1 não retorna dados)
        if ($ip === '127.0.0.1') {
            $ip = '8.8.8.8'; // IP público fictício do Google
        }

        $location = Location::get($ip);

        return response()->json([
            'ip' => $ip,
            'city' => $location->cityName,
            'region' => $location->regionName,
            'region_code' => $location->regionCode,
            'country' => $location->countryName,
            'country_code' => $location->countryCode,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'timezone' => $location->timezone,
            'postal_code' => $location->postalCode,
        ]);
    }
}

