<?php

namespace Botble\Course\Tables; // 

use Botble\Course\Models\Trainer; //
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


class TrainerTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Trainer::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make('image')
                    ->alignStart(),
                LinkableColumn::make('title')
                    ->title(trans('plugins/course::trainer.trainer_title'))
                    ->route('trainer.edit')
                    ->alignStart(),
                FormattedColumn::make('subtitle')
                    ->title(trans('plugins/course::trainer.trainer_subtitle'))
                    ->alignStart(),
                // FormattedColumn::make('description')
                //     ->title(trans('plugins/course::trainer.trainer_description'))
                //     ->alignStart(),
                 
                
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('trainer.create'))
            ->addActions([
                EditAction::make()->route('trainer.edit'),
                DeleteAction::make()->route('trainer.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('trainer.destroy'))
            ->addBulkChanges([
                TextBulkChange::make()
                    ->name('title')
                    ->title(trans('plugins/course::trainer.trainer_title')),
                TextBulkChange::make()
                    ->name('subtitle')
                    ->title(trans('plugins/course::trainer.trainer_subtitle')),
                TextBulkChange::make()
                    ->name('description')
                    ->title(trans('plugins/course::trainer.trainer_description')),
                TextBulkChange::make()
                    ->name('id')
                    ->title(trans('plugins/course::trainer.trainer_id')),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'title',
                        'subtitle',
                        'description',
                        'image',
                        'created_at',
                        'updated_at',
                        'status',
                        
                    ]);
                   
            });
    }
}
