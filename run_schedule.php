<?php

while (true) {
    // Execute o comando schedule:run
    shell_exec('php artisan send:property-emails');

    // Aguarde 24 horas (86400 segundos)
    sleep(86400);
}
