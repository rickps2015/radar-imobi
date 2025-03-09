<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $properties;
    public $user;

    // Recebe a coleção de propriedades e o usuário ao ser instanciado
    public function __construct($properties, User $user)
    {
        $this->properties = $properties;
        $this->user = $user;
    }

    public function build()
    {
        // Certifique-se de que você está passando corretamente os dados para a view
        return $this->view('emails.property_notification')
                    ->with([
                        'properties' => $this->properties,
                        'user' => $this->user,
                    ]);
    }
}
