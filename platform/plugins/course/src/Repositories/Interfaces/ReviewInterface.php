<?php

namespace Botble\Course\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ReviewInterface extends RepositoryInterface
{
    public function getGroupedByCourseId(int|string $courseId): Collection;
}
