<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Http;

class LogRequestData
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $ip = $request->ip();
            $location = null;

            try {
                $geo = Http::get("http://ip-api.com/json/{$ip}")->json();

                if (isset($geo['status']) && $geo['status'] === 'success') {
                    $location = [
                        'country' => $geo['country'] ?? null,
                        'region' => $geo['regionName'] ?? null,
                        'city' => $geo['city'] ?? null,
                        'lat' => $geo['lat'] ?? null,
                        'lon' => $geo['lon'] ?? null,
                        'isp' => $geo['isp'] ?? null,
                    ];
                }
            } 
            catch (\Exception $e) {
                $location = null;
            }

            RequestLog::create([
                'ip'         => $ip,
                'user_agent' => $request->header('User-Agent'),
                'method'     => $request->method(),
                'url'        => $request->fullUrl(),
                'payload'    => $request->all(),
                'location'   => $location,
            ]);

        } catch (\Exception $e) {
            
        }

        return $response;
    }
}
