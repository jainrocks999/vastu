<?php

namespace Botble\Orders\Supports;

use Botble\Orders\Models\Orders;
use Botble\Base\Enums\BaseStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrdersManager
{
    protected Collection $data;

    protected bool $loaded = false;

    protected array $locations = [];

    public function __construct()
    {
        $this->locations = [
            'not_set' => trans('plugins/orders::orders.not_set'),
        ];

        $this->data = collect();
    }

    public function display(string $location, array $attributes = []): string
    {
        $this->load();

        $data = $this
            ->filterExpired($this->data)
            ->where('location', $location)
            ->sortBy('order');

        if ($data->isNotEmpty()) {
            $data = $data->random(1);
        }

        return view('plugins/orders::partials.ad-display', compact('data', 'attributes'))->render();
    }

    public function load(bool $force = false, array $with = []): self
    {
        if (! $this->loaded || $force) {
            $this->data = $this->read($with);
            $this->loaded = true;
        }

        return $this;
    }

    protected function read(array $with): Collection
    {
        return Orders::query()->with($with)->get();
    }

    public function locationHasOrders(string $location): bool
    {
        $this->load();

        return $this
            ->filterExpired($this->data)
            ->where('location', $location)
            ->sortBy('order')
            ->isNotEmpty();
    }

    public function displayOrders(?string $key, array $attributes = []): ?string
    {
        if (! $key) {
            return null;
        }

        $this->load();

        $orders = $this
            ->filterExpired($this->data)
            ->where('key', $key)
            ->first();

        if (! $orders) {
            return null;
        }

        $data = [$orders];

        if (! isset($attributes['style'])) {
            $attributes['style'] = 'text-align: center;';
        }

        return view('plugins/orders::partials.ad-display', compact('data', 'attributes'))->render();
    }

    public function getData(bool $isLoad = false, bool $isNotExpired = false): Collection
    {
        if ($isLoad) {
            $this->load();
        }

        if ($isNotExpired) {
            return $this->filterExpired($this->data);
        }

        return $this->data;
    }

    public function registerLocation(string $key, string $name): self
    {
        $this->locations[$key] = $name;

        return $this;
    }

    public function getLocations(): array
    {
        return apply_filters('orders_locations', $this->locations);
    }

    public function getOrders(string $key): ?Orders
    {
        if (! $key) {
            return null;
        }

        $orders = $this->getData(true)->firstWhere('key', $key);

        if (! $orders || ! $orders->image) {
            return null;
        }

        return $orders;
    }

    protected function filterExpired(Collection $data): Collection
    {
        return $data
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->filter(fn (Orders $item) => $item->expired_at->gte(Carbon::now()));
    }
}
