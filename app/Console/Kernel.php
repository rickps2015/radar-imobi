<?php

namespace App\Console;

use App\Console\Commands\SendPropertyEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * As artes executadas pelo Artisan.
     *
     * @var array
     */
    protected $commands = [
        SendPropertyEmails::class,
    ];

    /**
     * Defina os programas de agendamento para a execução do Artisan.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Agendar o comando para ser executado diariamente
        // Aqui você pode ajustar para o intervalo desejado, como diário, semanal, etc.
        $schedule->command('send:property-emails')->everyMinute();
    }

    /**
     * Registre qualquer comando Artisan específico do aplicativo.
     *
     * @param  \Illuminate\Foundation\Console\Application  $artisan
     * @return void
     */
    protected function commands()
    {
        // Carregar os comandos definidos em 'routes/console.php'
        $this->load(__DIR__.'/Commands');

        // Registre os comandos do Artisan.
        require base_path('routes/console.php');
    }
}
