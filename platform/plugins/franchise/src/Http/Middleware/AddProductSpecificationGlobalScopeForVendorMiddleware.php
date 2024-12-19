<?php

namespace Botble\Franchise\Http\Middleware;

use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Franchise\Models\Scopes\SpecificationVendorScope;
use Closure;
use Illuminate\Http\Request;

class AddProductSpecificationGlobalScopeForVendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        SpecificationGroup::addGlobalScope(SpecificationVendorScope::class);
        SpecificationAttribute::addGlobalScope(SpecificationVendorScope::class);
        SpecificationTable::addGlobalScope(SpecificationVendorScope::class);

        return $next($request);
    }
}
