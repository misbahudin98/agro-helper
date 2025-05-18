<?php

namespace App\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        Passport::hashClientSecrets();

        Passport::tokensExpireIn(now()->addMinutes(((int) env("PASSPORT_ACCESS", 30))));
        Passport::refreshTokensExpireIn(now()->addMinutes(((int)  env("PASSPORT_REFRESH", 45))));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::tokensCan([
            'admin'   => 'Access User',
            // Tambahkan scope lain sesuai kebutuhan
        ]);

        if (isset($_SERVER['HTTP_REFERER'])) {
            # code...
            $refererHost = $_SERVER['HTTP_REFERER'];
            $refererHost = parse_url($refererHost, PHP_URL_SCHEME) . "://" . parse_url($refererHost, PHP_URL_HOST);

            if (!empty($refererHost) && $refererHost != env("APP_URL")) {
                // Ambil daftar allowed origins dari konfigurasi (misalnya, di config/cors.php)
                $allowedOrigins = [
                    "1" => "https://farm.misbah.live/callback.php",

                ];
                // dd($url, $allowedOrigins);
                // dd($refererHost, $allowedOrigins);
                // Cek apakah host referer termasuk dalam daftar allowed

                $index = null;
                foreach ($allowedOrigins as $key => $value) {
                    $value =  parse_url($value, PHP_URL_SCHEME) . "://" . parse_url($value, PHP_URL_HOST);
                    if ($value == $refererHost) {
                        $index = $key;
                    }
                }
                // dd( $refererHost . '/callback|' . $index);

                // dd($index !== false &&$refererHost != env("APP_URL"));
                if ($refererHost != config('app.url')) {
                    // dd($index,$refererHost,$allowedOrigins);
                    if (!$index) {

                        abort(403);
                    }
Cookie::queue(
    Cookie::make(
        'callback', 
        $allowedOrigins[$index] . '|' . $index, 
        0,         // 0 menit => session cookie
        '/',       // path
        null,      // domain (null untuk default)
        true,      // secure
        true,      // httpOnly
        false,     // raw (false untuk tidak menggunakan raw)
        'Strict'   // sameSite policy
    )
);
                }
                app()->instance('index', $index);
                // Jika valid, Anda dapat menyetel cookie atau menambahkan logika tambahan di sini.
                // Misalnya, menyetel cookie dengan masa berlaku singkat:

                // dd(Cookie::get('callback'));

            }
        }
    }
}
