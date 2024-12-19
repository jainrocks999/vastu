<?php

namespace Botble\Franchise\Http\Middleware;

use Botble\Franchise\Facades\FranchiseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotVendor
{
    public function handle(Request $request, Closure $next, string $guard = 'customer')
    {
        if (! Auth::guard($guard)->check() || ! Auth::guard($guard)->user()->is_vendor) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest(route('customer.login'));
        }

        if (FranchiseHelper::getSetting('verify_vendor', 1) &&
            ! Auth::guard($guard)->user()->vendor_verified_at) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Vendor account is not verified.', 403);
            }

            return redirect()->guest(route('franchise.vendor.become-vendor'));
        }

        return $next($request);
    }
}