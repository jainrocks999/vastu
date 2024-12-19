<?php

namespace Botble\Course\Tables;

use Botble\Course\Models\CourseCategory;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CourseCategoryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseCategory::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->label(trans('plugins/course::course-category.name'))->route('course_category.edit'),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('course_category.create'))
            ->addActions([
                EditAction::make()->route('course_category.edit'),
                DeleteAction::make()->route('course_category.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('course_category.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'created_at',
                        'status',
                    ]);
            });
    }
}
