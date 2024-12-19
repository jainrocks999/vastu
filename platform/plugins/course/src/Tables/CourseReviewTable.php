<?php

namespace Botble\Course\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Course\Models\CourseReview;
use Botble\Course\Tables\Formatters\ReviewImagesFormatter;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CourseReviewTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseReview::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('course_review.create'))
            ->addActions([
                Action::make('view')
                    ->route('course_review.show')
                    ->permission('reviews.index')
                    ->label(__('View'))
                    ->icon('ti ti-eye'),
                DeleteAction::make()->route('course_review.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('course_id', function (CourseReview $item) {
                if (! empty($item->course) && $item->course->url) {
                    return Html::link(
                        $item->course->url,
                        BaseHelper::clean($item->title),
                        ['target' => '_blank']
                    );
                }

                return null;
            })
            ->editColumn('customer_id', function (CourseReview $item) {
                if (! $item->user->id) {
                    if (! $item->customer_email && ! $item->customer_name) {
                        return null;
                    }

                    return sprintf('%s (%s)', BaseHelper::clean($item->customer_name), $item->customer_email);
                }

                return Html::link(
                    route('customers.edit', $item->user->id),
                    BaseHelper::clean($item->user->name)
                )->toHtml();
            })
            ->editColumn('star', function (CourseReview $item) {
                return view('plugins/course::course-reviews.partials.rating', ['star' => $item->star])->render();
            })
            ->formatColumn('images', ReviewImagesFormatter::class)
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query->where(function ($query) use ($keyword) {
                        return $query
                            ->whereHas('course', function ($subQuery) use ($keyword) {
                                return $subQuery->where('courses.title', 'LIKE', '%' . $keyword . '%');
                            })
                            ->orWhereHas('user', function ($subQuery) use ($keyword) {
                                return $subQuery->where('ec_customers.name', 'LIKE', '%' . $keyword . '%');
                            })
                            ->orWhere('comment', 'LIKE', '%' . $keyword . '%');
                    });
                }

                return $query;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->select([
                'id',
                'star',
                'comment',
                'course_id',
                'customer_id',
                'customer_name',
                'customer_email',
                'status',
                'created_at',
                'images',
            ])
            ->with(['user', 'course']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('course_id')
                ->title(trans('plugins/course::course-review.course'))
                ->alignStart(),
            Column::make('customer_id')
                ->title(trans('plugins/course::course-review.user'))
                ->alignStart(),
            Column::make('star')
                ->title(trans('plugins/course::course-review.star')),
            LinkableColumn::make('comment')
                ->title(trans('plugins/course::course-review.comment'))
                ->route('course_review.show')
                ->alignStart()
                ->limit(70),
            Column::formatted('images')
                ->title(trans('plugins/course::course-review.images'))
                ->alignStart()
                ->width(150)
                ->orderable(false)
                ->searchable(false),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('review.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        // if ($this->isEmpty()) {
        //     return view('plugins/course::course-reviews.intro');
        // }

        return parent::renderTable($data, $mergeData);
    }
}
