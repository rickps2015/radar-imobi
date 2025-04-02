<?php

namespace App\Http\Controllers;

use App\Models\UserFilter;
use App\Models\Property;
use App\Mail\PropertyEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function sendPropertyEmails()
    {
        // Pega todos os filtros de usuário da tabela user_filters
        $userFilters = UserFilter::all();

        // Agrupar filtros por usuário
        $userFiltersGrouped = $userFilters->groupBy('user_id');

        foreach ($userFiltersGrouped as $userId => $filters) {
            // Obter o usuário com base no user_id
            $user = $filters->first()->user; // Certifique-se de que você tenha um relacionamento 'user' no modelo UserFilter

            // Filtrar propriedades com base nas datas de leilão e nos filtros do usuário
            $filteredProperties = collect();
            foreach ($filters as $filter) {
            $property = Property::where('property_number', $filter->property_number)->first();
            if ($property) {
                $today = now()->startOfDay(); // Zera hora, minuto, segundo e milissegundo

                // Verifica se primary_leilao_data não é null antes de usar Carbon
                $primaryDate = $property->primary_leilao_data ? \Carbon\Carbon::parse($property->primary_leilao_data)->startOfDay() : null;

                // Verifica se second_leilao_data não é null antes de usar Carbon
                $secondDate = $property->second_leilao_data ? \Carbon\Carbon::parse($property->second_leilao_data)->startOfDay() : null;

                // Verifica se alguma das datas é igual à data de hoje
                if (($primaryDate && $primaryDate->equalTo($today)) || ($secondDate && $secondDate->equalTo($today))) {
                    $filteredProperties->push($property);
                }
            }
            }

            // Se houver propriedades filtradas e o usuário tiver um e-mail, envie o e-mail
            if ($filteredProperties->isNotEmpty() && $user && $user->email) {
            // Passando os dados para a classe de e-mail (ajustar para que a classe de e-mail seja preenchida corretamente)
            Mail::to($user->email)->send(new PropertyEmail($filteredProperties, $user));
            }
        }

        return response()->json(['message' => 'Todos os e-mails foram enviados!', 'date_last_execution' => now()]);
    }
}
