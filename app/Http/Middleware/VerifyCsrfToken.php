<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'checkout','Strippayinitiate','return_url','gtw_checkout','gtw_checkout_form','gtw_fetch','epayCheckout','Directapi'
    ];
}
