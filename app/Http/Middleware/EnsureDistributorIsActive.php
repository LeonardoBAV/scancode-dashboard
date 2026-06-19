<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDistributorIsActive
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user === null || $user->distributor_id === null) {
            return $next($request);
        }

        $distributor = $user->distributor;

        if ($distributor !== null && ! $distributor->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->to(Filament::getCurrentPanel()->getLoginUrl())
                ->withErrors(['email' => __('filament.tenancy.inactive_distributor')]);
        }

        return $next($request);
    }
}
