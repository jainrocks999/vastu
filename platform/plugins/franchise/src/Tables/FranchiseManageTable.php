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

use Illuminate\Contracts\View\View;

use Botble\Table\Actions\Action;


class FranchiseManageTable extends TableAbstract
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
                // FormattedColumn::make('dob')
                //     ->title(trans('plugins/franchise::franchise.dob'))
                //     ->alignStart()
                //     ->getValueUsing(fn (FormattedColumn $column) => 
                //          \Carbon\Carbon::parse($column->getItem()->customer->dob)->format('d/m/Y')),
                FormattedColumn::make('level')
                    ->title(trans('plugins/franchise::franchise.level'))
                    ->aligncenter(),
                FormattedColumn::make('charges')
                    ->title(trans('plugins/franchise::franchise.charges'))
                    ->alignStart(),

                FormattedColumn::make('application_status')
                    ->title(trans('plugins/franchise::franchise.application_status'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $status = $column->getItem()->application_status;
                
                        // Check if the status is 'accept' and render it with a green badge
                        if ($status === 'accept' ) {
                            return '<div align="center"> <span class="badge bg-success text-light">' . trans('Accept') . '</span> </div>';
                        }
                
                        // If the status is something else, return it as normal
                        return $status;
                    }),
             
                CreatedAtColumn::make(),
                StatusColumn::make()
                ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->customer->status),
            ])
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
                    ])
                    ->where('application_status', 'accept');
            });
    }
}
