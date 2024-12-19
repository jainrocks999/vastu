<?php

namespace Botble\Franchise\Repositories\Eloquent;

use Botble\Franchise\Models\Store;
use Botble\Franchise\Repositories\Interfaces\StoreInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class StoreRepository extends RepositoriesAbstract implements StoreInterface
{
    public function handleCommissionEachCategory(array $data): array
    {
        return Store::handleCommissionEachCategory($data);
    }

    public function getCommissionEachCategory(): array
    {
        return Store::getCommissionEachCategory();
    }
}
