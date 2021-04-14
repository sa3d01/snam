<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('apiToken')) {
            try {
                $split = explode("sa3d01", $request->header('apiToken'));
                $user = User::where('apiToken', $split['1'])->first();
            } catch (Exception $e) {
                $response = response()->json(['status' => 401, 'msg' => 'Invalid authentication token in request'],401);
            }
            if (!$user) {
                $response = response()->json(['status' => 401, 'msg' => 'Invalid authentication token in request'],401);
            } else {
                $response = $next($request);
            }
        } elseif (!$request->header('apiToken')) {
            $response = response()->json(['status' => 401, 'msg' => 'Invalid authentication token in request'],401);
        }
        return $response;
    }
}
