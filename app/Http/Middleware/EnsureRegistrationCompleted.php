<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user is not authenticated, continue
        if (!$user) {
            return $next($request);
        }

        // Skip registration check for internal users (Super Admin, HR, Interviewer)
        // They are created by seeder and don't go through registration flow
        $internalRoles = ['super_admin', 'hr', 'interviewer'];
        $userRole = $user->role->name ?? null;
        
        if (in_array($userRole, $internalRoles)) {
            return $next($request);
        }

        // If user registration is not completed, redirect to appropriate step
        if (!$user->registration_completed) {
            // Determine which step to redirect to
            $step = $user->registration_step ?? 1;
            
            // Don't redirect if user is already on a registration step
            if ($request->is('register/*')) {
                return $next($request);
            }
            
            // Redirect to the appropriate step
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
