<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } elseif (Auth::check() && Auth::user()->language) {
            App::setLocale(Auth::user()->language);
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
