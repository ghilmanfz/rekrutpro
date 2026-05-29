<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationCompleted
{
    




    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

         
        if (!$user) {
            return $next($request);
        }

         
         
        $userRole = $user->role->name ?? null;
        
        if (in_array($userRole, Role::internalNames(), true)) {
            return $next($request);
        }

         
        if (!$user->registration_completed) {
             
            $step = $user->registration_step ?? 1;
            
             
            if ($request->is('register/*')) {
                return $next($request);
            }
            
             
            switch ($step) {
                case 1:
                    return redirect()->route('register.step2');
                case 2:
                    return redirect()->route('register.step3');
                case 3:
                    return redirect()->route('register.step4');
                case 4:
                    return redirect()->route('register.step5');
                default:
                    return redirect()->route('register.step2');
            }
        }

        return $next($request);
    }
}
