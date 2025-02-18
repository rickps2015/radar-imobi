<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies = '*';  // Ou você pode definir um IP específico

    /**
     * The headers that should be used to detect a proxy.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
