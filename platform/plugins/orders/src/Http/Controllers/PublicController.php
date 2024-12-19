<?php

namespace Botble\Orders\Http\Controllers;

use Botble\Orders\Models\Orders;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Facades\RvMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicController extends BaseController
{
    public function getOrdersClick(string $key, BaseHttpResponse $response)
    {
        $orders = Orders::query()->where('key', $key)->first();

        if (! $orders || ! $orders->url) {
            return $response->setNextUrl(route('public.single'));
        }

        $orders::withoutEvents(fn () => $orders::withoutTimestamps(fn () => $orders->increment('clicked')));

        return $response->setNextUrl($orders->url);
    }

    public function getOrdersImage(string $randomHash, string $ordersKey, string $size, string $hashName, BaseHttpResponse $response)
    {
        /**
         * @var Orders $orders
         */
        $orders = Orders::query()->where('key', $ordersKey)->firstOrFail();

        if (! $orders) {
            abort(404);
        }

        abort_if($randomHash !== $orders->random_hash, 404);

        if ($size === 'tablet') {
            $image = $orders->tablet_image ?: $orders->image;
        } elseif ($size === 'mobile') {
            $image = ($orders->mobile_image ?: $orders->tablet_image) ?: $orders->image;
        } else {
            $image = $orders->image;
        }

        if (! $image) {
            abort(404);
        }

        $realPath = RvMedia::getRealPath($image);

        abort_if(! Str::of(
            $orders->parseImageUrl($size)
        )->endsWith($hashName . '.jpg'), 404);

        if (Str::startsWith($realPath, ['http://', 'https://'])) {
            return $response->setNextUrl($realPath);
        }

        if (! File::exists($realPath)) {
            abort(404);
        }

        return response()->file($realPath, [
            'Content-Type' => File::mimeType($realPath),
        ]);
    }

    public function getOrdersClickAlternative(string $randomHash, string $ordersKey)
    {
        return app()->call([$this, 'getOrdersClick'], ['key' => $ordersKey]);
    }
}
