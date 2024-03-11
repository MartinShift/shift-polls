<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Cookie;
use App;
use Auth;   
class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private function parseAcceptLanguageHeader($header)
    {
        $languages = explode(',', $header);
        $preferredLanguage = explode(';', $languages[0])[0];
        $primaryLanguage = explode('-', $languages[0])[0];
        return $primaryLanguage;
    }
    public function handle(Request $request, Closure $next): Response
    {
       
        if (Cookie::get('preferredLanguage') == '') {
        $acceptLanguage = $request->header('Accept-Language');
        $preferredLanguage = $this->parseAcceptLanguageHeader($acceptLanguage);
            cookie::queue("locale", $preferredLanguage,24*60*365);
            cookie::queue("preferredLanguage", $preferredLanguage,24*60*0);
            app()->setLocale($preferredLanguage);
        }
          $cookieLocale = Cookie::get('locale');
          if ($cookieLocale && in_array($cookieLocale, array_keys(config('languages')))) {
              App::setLocale($cookieLocale);
          } else {
              App::setLocale('en');
          }
  
          return $next($request);
    }
}
