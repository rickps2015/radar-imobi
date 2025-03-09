<?php

namespace App\Console\Commands;

use App\Models\UserFilter;
use App\Models\Property;
use App\Mail\PropertyEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPropertyEmails extends Command
{
    protected $signature = 'send:property-emails';
    protected $description = 'Enviar e-mails para os usuários com base nos filtros de propriedades';

    public function handle()
    {
        // Pega todos os filtros de usuário da tabela user_filters
        $userFilters = UserFilter::all();

        // Agrupar filtros por usuário
        $userFiltersGrouped = $userFilters->groupBy('user_id');

        foreach ($userFiltersGrouped as $userId => $filters) {
            // Obter o usuário com base no user_id
            $user = $filters->first()->user;  // Certifique-se de que você tenha um relacionamento 'user' no modelo UserFilter

            // Obter todas as propriedades baseadas nos filtros do usuário
            $properties = collect();
            foreach ($filters as $filter) {
                $properties = $properties->merge(Property::where('property_number', $filter->property_number)->get());
            }

            // Se houver propriedades e o usuário tiver um e-mail, envie o e-mail
            if ($properties->isNotEmpty() && $user && $user->email) {
                // Passando os dados para a classe de e-mail (ajustar para que a classe de e-mail seja preenchida corretamente)
                Mail::to($user->email)->send(new PropertyEmail($properties, $user));
                $this->info('E-mail enviado para: ' . $user->email);
            }
        }

        $this->info('Todos os e-mails foram enviados!');
    }
}

