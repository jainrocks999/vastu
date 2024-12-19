<?php

namespace Botble\Ecommerce\Tables;

use Botble\Table\Columns\FormattedColumn;
use Illuminate\Database\Query\Builder;

class ProductPriceTable extends ProductBulkEditableTable
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setView('plugins/ecommerce::product-prices.index')
            ->addColumns([
                FormattedColumn::make('cost_per_item')
                    ->title(trans('plugins/ecommerce::products.form.cost_per_item'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-prices.columns.price', [
                            'product' => $column->getItem(),
                            'type' => 'cost_per_item',
                        ]);
                    })
                    ->nowrap()
                    ->width(150)
                    ->orderable(false),
                FormattedColumn::make('price')
                    ->title(trans('plugins/ecommerce::products.form.price'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-prices.columns.price', [
                            'product' => $column->getItem(),
                            'type' => 'price',
                        ]);
                    })
                    ->nowrap()
                    ->width(150)
                    ->orderable(false),
                FormattedColumn::make('sale_price')
                    ->title(trans('plugins/ecommerce::products.form.price_sale'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-prices.columns.price', [
                            'product' => $column->getItem(),
                            'type' => 'sale_price',
                        ]);
                    })
                    ->nowrap()
                    ->width(150)
                    ->orderable(false),
                FormattedColumn::make('student_price')
                    ->title('student price')
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-prices.columns.student_price', [
                            'product' => $column->getItem(),
                            'type' => 'student_price',
                        ]);
                    })
                    ->nowrap()
                    ->width(150)
                    ->orderable(false),

                FormattedColumn::make('franchise_price')
                    ->title('franchise price')
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-prices.columns.franchise_price', [
                            'product' => $column->getItem(),
                            'type' => 'franchise_price',
                        ]);
                    })
                    ->nowrap()
                    ->width(150)
                    ->orderable(false),
              
            ]);
    }

    public function query()
    {
        /**
         * @var Builder $query
         */
        $query = parent::query();

        $query->addSelect([
            'ec_products.cost_per_item',
            'ec_products.price',
            'ec_products.sale_price',
            'ec_products.student_price',
            'ec_products.franchise_price',
            'ec_products.sale_type',
        ]);

        return $query;
    }
}
