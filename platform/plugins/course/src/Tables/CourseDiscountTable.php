<?php

namespace Botble\Course\Tables;

use Botble\Course\Enums\DiscountTypeEnum;
use Botble\Course\Models\CourseDiscount;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CourseDiscountTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseDiscount::class)
            ->addActions([
                //EditAction::make()->route('course_discount.edit'),
                DeleteAction::make()->route('course_discount.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {

        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('detail', function (CourseDiscount $item) {
                $isCoupon = $item->type === DiscountTypeEnum::COUPON;

                return view('plugins/course::course_discount.detail', compact('item', 'isCoupon'))->render();
            })
            ->editColumn('total_used', function (CourseDiscount $item) {
                if ($item->type === DiscountTypeEnum::PROMOTION) {
                    return '&mdash;';
                }

                if ($item->quantity === null) {
                    return number_format($item->total_used);
                }

                return sprintf('%d/%d', number_format($item->total_used), number_format($item->quantity));
            });
    
        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select(['*']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('detail')
                ->name('code')
                ->title(trans('plugins/course::course-discount.detail'))
                ->alignStart(),
            Column::make('total_used')
                ->title(trans('plugins/course::course-discount.used'))
                ->width(100),
            DateColumn::make('start_date')
                ->title(trans('plugins/course::course-discount.start_date')),
            DateColumn::make('end_date')
                ->title(trans('plugins/course::course-discount.end_date')),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('course_discount.create'), 'course_discount.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('course_discount.destroy'),
        ];
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        if ($this->isEmpty()) {
            return view('plugins/course::course_discount.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}
