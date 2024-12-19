<?php

namespace Botble\Course\Repositories\Eloquent;

use Botble\Course\Repositories\Interfaces\ReviewInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends RepositoriesAbstract implements ReviewInterface
{
    public function getGroupedByCourseId(int|string $courseId): Collection
    {
        $data = $this->model
            ->select([DB::raw('COUNT(star) as star_count'), 'star'])
            ->where('course_id', $courseId)
            ->wherePublished()
            ->groupBy('star');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
