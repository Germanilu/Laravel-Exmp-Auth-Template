<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('IsSuperAdmin middleware');

        //Recupero el objeto del usuario y accedo a su id
        $userId = auth()->user()->id;

        //Recupero el usuario
        $user = User::find($userId);

        //Busco con el contains si existe 
        $hasRole = $user->roles->contains(3);


        if(!$hasRole){
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Dont have permission'
                ],400
            );
        }


        // foreach ($me->roles as $role) {
        //     echo $role->pivot->created_at;
            
        // }


        return $next($request);
    }
}
