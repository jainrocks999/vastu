<?php

namespace Botble\Franchise\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface StoreInterface extends RepositoryInterface
{
    public function handleCommissionEachCategory(array $data): array;

    public function getCommissionEachCategory(): array;
}
