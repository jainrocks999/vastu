<?php

namespace Botble\Franchise\Tables;

use Botble\Franchise\Models\FranchiseService;
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


class FranchiseServiceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(FranchiseService::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make('logo')
                ->alignStart(),
                LinkableColumn::make('services_name')
                    ->title(trans('plugins/franchise::franchise-service.services_name'))
                    ->route('franchise_services.edit')
                    ->alignStart(),
                    FormattedColumn::make('premium_services')
                    ->title(trans('plugins/franchise::franchise-service.premium_services'))
                    ->alignStart()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->premium_services == 1 ? 'Yes' : 'No'),
                FormattedColumn::make('short_description')
                    ->title(trans('plugins/franchise::franchise-service.short_description'))
                    ->alignStart(),
                FormattedColumn::make('status')
                    ->title(trans('plugins/franchise::franchise.status'))
                    ->alignStart(),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('franchise_services.create'))
            ->addActions([
                EditAction::make()->route('franchise_services.edit'),
                DeleteAction::make()->route('franchise_services.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('franchise_services.destroy'))
            ->addBulkChanges([
                TextBulkChange::make()
                    ->name('franchise_name')
                    ->title(trans('plugins/franchise::franchise-service.franchise_name')),
                TextBulkChange::make()
                    ->name('services_price')
                    ->title(trans('plugins/franchise::franchise-service.services_price')),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'logo',
                        'services_name',
                        'premium_services',
                        'short_description',
                        'status',
                        'created_at'
                    ]);
            });
    }
}
