<?php

namespace Botble\Orders\Tables;

use Botble\Orders\Models\Orders;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\DateBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class OrdersTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Orders::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                NameColumn::make()->route('orders.edit'),
                FormattedColumn::make('key')
                    ->title(trans('plugins/orders::orders.shortcode'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $value = $column->getItem()->key;

                        if (! function_exists('shortcode')) {
                            return $value;
                        }

                        return shortcode()->generateShortcode('orders', ['key' => $value]);
                    })
                    ->renderUsing(fn (FormattedColumn $column) => Html::tag('code', $column->getValue()))
                    ->copyable()
                    ->copyableState(fn (FormattedColumn $column) => $column->getValue()),
                Column::make('clicked')
                    ->title(trans('plugins/orders::orders.clicked'))
                    ->alignStart(),
                DateColumn::make('expired_at')->title(trans('plugins/orders::orders.expired_at')),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('orders.create'))
            ->addActions([
                EditAction::make()->route('orders.edit'),
                DeleteAction::make()->route('orders.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('orders.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                DateBulkChange::make()->name('expired_at')->title(trans('plugins/orders::orders.expired_at')),
            ])
            ->queryUsing(function ($query) {
                $query->select([
                    'id',
                    'image',
                    'key',
                    'name',
                    'clicked',
                    'expired_at',
                    'status',
                ]);
            });
    }
}
