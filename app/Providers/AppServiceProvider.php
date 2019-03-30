<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('uniqueCandidatePosition', function ($attribute, $value, $parameters, $validator) {
            $count = DB::table('positions_candidates')
              ->where('candidate_id', $value)
              ->where('position_id', $parameters[0])
              ->count();
            return $count === 0;
        }, 'This candidate has already been associated to this position');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
