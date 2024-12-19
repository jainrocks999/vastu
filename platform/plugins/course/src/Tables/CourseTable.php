<?php

namespace Botble\Course\Tables;

use Botble\Course\Models\Course;
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


class CourseTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Course::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make('image')
                    ->alignStart(),
                LinkableColumn::make('title')
                    ->title(trans('plugins/course::course.title'))
                    ->route('course.edit')
                    ->alignStart(),
                FormattedColumn::make('code')
                    ->title(trans('plugins/course::course.code'))
                    ->alignStart(),
                FormattedColumn::make('price')
                    ->title(trans('plugins/course::course.price'))
                    ->alignStart(),
                FormattedColumn::make('course_category_id')
                    ->title(trans('plugins/course::course.course_category_id'))
                    ->alignStart()
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->category->name),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('course.create'))
            ->addActions([
                EditAction::make()->route('course.edit'),
                DeleteAction::make()->route('course.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('course.destroy'))
            ->addBulkChanges([
                TextBulkChange::make()
                    ->name('title')
                    ->title(trans('plugins/course::course.title')),
                TextBulkChange::make()
                    ->name('code')
                    ->title(trans('plugins/course::course.code')),
                TextBulkChange::make()
                    ->name('price')
                    ->title(trans('plugins/course::course.price')),
                TextBulkChange::make()
                    ->name('course_category_id')
                    ->title(trans('plugins/course::course.course_category_id')),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'image',
                        'title',
                        'code',
                        'price',
                        'course_category_id',
                        'created_at',
                        'status',
                    ]);
            });
    }
}
