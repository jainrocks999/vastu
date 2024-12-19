<?php

namespace Botble\Course\Tables;

use Botble\Course\Models\CourseOrder;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\TextBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Botble\Table\Columns\ImageColumn;


class CourseOrderTable extends TableAbstract
{
    public function setup(): void
    {

        $this
            ->model(CourseOrder::class)
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('code')
                    ->title(trans('plugins/course::course-order.course_code'))
                    ->alignStart(),
                LinkableColumn::make('user_name')
                    ->title(trans('plugins/course::course-order.student_name'))
                    ->route('orders.course_order.edit')
                    ->alignStart(),
                    // ->getValueUsing(fn (FormattedColumn $column) => $column->user_name),

                FormattedColumn::make('course_title')
                    ->title(trans('plugins/course::course-order.course_title'))
                    ->alignStart(),
                FormattedColumn::make('amount')
                    ->title(trans('plugins/course::course-order.amount'))
                    ->alignStart(),
                FormattedColumn::make('discount_amount')
                    ->title(trans('plugins/course::course-order.discount_amount'))
                    ->alignStart(),
                FormattedColumn::make('discount_description')
                    ->title(trans('plugins/course::course-order.discount_for'))
                    ->alignStart(),
                FormattedColumn::make('total')
                    ->title(trans('plugins/course::course-order.total'))
                    ->alignStart(),

                FormattedColumn::make('payment_method')
                    ->title(trans('plugins/course::course-order.payment_method'))
                    ->alignStart(),
                FormattedColumn::make('payment_status')
                    ->title(trans('plugins/course::course-order.payment_status'))
                    ->alignStart(),
                FormattedColumn::make('status')
                    ->title(trans('plugins/course::course-order.status'))
                    ->alignStart(),
                FormattedColumn::make('tax_amount')
                    ->title(trans('plugins/course::course-order.tax_amount'))
                    ->alignStart(),
                FormattedColumn::make('start_date')
                    ->title(trans('plugins/course::course-order.start_date'))
                    ->alignStart(),
                FormattedColumn::make('created_at')
                    ->title(trans('plugins/course::course-order.created_at'))
                    ->alignStart(),
                // FormattedColumn::make('course_category_id')
                //     ->title(trans('plugins/course::course-order.course_category_id'))
                //     ->alignStart()
                //     ->withEmptyState()
                //     ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->category->name),
                CreatedAtColumn::make()
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('orders.course_order.create'))
            ->addActions([
                EditAction::make()->route('orders.course_order.edit'),
                DeleteAction::make()->route('orders.course_order.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('course_order.destroy'))
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'code',
                        'user_name',
                        'course_title',
                        'amount',
                        'discount_amount',
                        'discount_description',
                        'total',
                        'payment_method',
                        'payment_status',
                        'status',
                        'tax_amount',
                        'discount_amount',
                        'start_date',
                        'created_at',
                    ]);
            });
    }
}
