<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function get()
    {
        $config = [
            'appName' => config('app.name'),
            'centralDomain' => data_get(config('tenancy.central_domains'),'0'),
            'domainStart' => data_get(
                explode('.', request()->route()->domain()),
                0
            ),
            'locale' => $locale = app()->getLocale(),
            'locales' => config('app.locales'),
        ];

        $config['tenant'] = [
            'initialized' => tenancy()->initialized
        ];

        if (tenancy()->initialized){
            $config['tenant']['domain'] = tenancy()->tenant->domains()->first()->domain;
        }

        return $config;
    }
}
