<?php

namespace Botble\Franchise\Tables;

use Botble\Franchise\Models\Franchise;
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


class FranchiseTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Franchise::class)
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('service_id')
                    ->title(trans('plugins/franchise::franchise.service_id'))
                    ->alignStart(),
                ImageColumn::make('avatar')
                    ->title(trans('plugins/franchise::franchise.avatar'))
                    ->alignStart()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->customer->avatar),
                LinkableColumn::make('franchise_name')
                    ->title(trans('plugins/franchise::franchise.franchise_name'))
                    ->route('franchise.edit')
                    ->alignStart(),
                FormattedColumn::make('dob')
                    ->title(trans('plugins/franchise::franchise.dob'))
                    ->alignStart()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->customer->dob),
                FormattedColumn::make('gender')
                    ->title(trans('plugins/franchise::franchise.gender'))
                    ->alignStart(),
                FormattedColumn::make('level')
                    ->title(trans('plugins/franchise::franchise.level'))
                    ->alignStart(),
                FormattedColumn::make('charges')
                    ->title(trans('plugins/franchise::franchise.charges'))
                    ->alignStart(),
                FormattedColumn::make('application_status')
                    ->title(trans('plugins/franchise::franchise.application_status'))
                    ->alignStart(),
                // FormattedColumn::make('confirmed_at')
                //     ->title(trans('plugins/franchise::franchise.confirmed_at'))
                //     ->alignStart(),
                // FormattedColumn::make('course_category_id')
                //     ->title(trans('plugins/franchise::franchise.course_category_id'))
                //     ->alignStart()
                //     ->withEmptyState()
                //     ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->category->name),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('franchise.create'))
            ->addActions([
                EditAction::make()->route('franchise.edit'),
                DeleteAction::make()->route('franchise.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('franchise.destroy'))
            ->addBulkChanges([
                TextBulkChange::make()
                    ->name('franchise_name')
                    ->title(trans('plugins/franchise::franchise.franchise_name')),
                TextBulkChange::make()
                    ->name('application_status')
                    ->title(trans('plugins/franchise::franchise.application_status')),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'service_id',
                        'customer_id',
                        'franchise_name',
                        'customer_id',
                        'customer_id',
                        'level',
                        'charges',
                        'application_status',
                        // 'confirmed_at',
                        'created_at',
                        'status',
                    ]);
            });
    }
}
