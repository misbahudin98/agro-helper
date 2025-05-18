<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class XssSanitizer

{

    public function handle(Request $request, Closure $next)

    {

        $input = $request->all();

        // Periksa apakah header Origin ada dan valid
        //   if (!$request->hasHeader('Origin') || 
        //     !in_array($request->header('Origin'), config('cors.allowed_origins'))) {
        //     throw new HttpResponseException(response()->json([
        //         "error" => "invalid_client",
        //         "error_description" => "Client authentication failed",
        //         "message" => "Forbidden."
        //     ])->setStatusCode(403));
        // }


        array_walk_recursive($input, function (&$input) {
            $input = str_replace('"', "'", strip_tags($input));
        });
        // $clientTimezone = $request->client_timezone;
        $request->merge($input);

        return $next($request);
    }
}
