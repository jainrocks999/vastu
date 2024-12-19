<?php

namespace Botble\Course\Services\Footprints;

use Illuminate\Http\Request;

interface TrackingFilterInterface
{
    public function shouldTrack(Request $request): bool;
}
