<?php

namespace Botble\Franchise\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\ServiceProvider;
use Botble\Franchise\Contracts\Franchise as FranchiseContract;
use Botble\Franchise\FranchiseCollection;
use Botble\Franchise\FranchiseItem;
use Botble\Franchise\FranchiseSupport;
use Botble\Franchise\Models\Franchise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_META_BOXES, function (string $context, array|string|Model|null $object = null): void {
            if (
                ! $object instanceof BaseModel
                || $context != 'advanced'
                || ! in_array($object::class, config('plugins.franchise.general.schema_supported', []))
                || ! setting('enable_franchise_schema', 0)
            ) {
                return;
            }

            MetaBox::addMetaBox(
                'franchise_schema_config_wrapper',
                trans('plugins/franchise::franchise.franchise_schema_config', [
                    'link' => Html::link(
                        'https://developers.google.com/search/docs/data-types/franchisepage',
                        trans('plugins/franchise::franchise.learn_more'),
                        ['target' => '_blank']
                    ),
                ]),
                function () {
                    return (new FranchiseSupport())->renderMetaBox(func_get_args()[0] ?? null);
                },
                $object::class,
                $context
            );
        }, 39, 2);

        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object): void {
            if (
                ! in_array($object::class, config('plugins.franchise.general.schema_supported', []))
                || ! setting('enable_franchise_schema', 0)
            ) {
                return;
            }

            $franchises = (array) $object->getMetaData('franchise_schema_config', true);

            if (is_plugin_active('franchise')) {
                $selectedExistingFranchises = $object->getMetaData('franchise_ids', true);

                if ($selectedExistingFranchises && is_array($selectedExistingFranchises)) {
                    $selectedExistingFranchises = array_filter($selectedExistingFranchises);

                    if ($selectedExistingFranchises) {
                        $selectedFranchises = Franchise::query()
                            ->wherePublished()
                            ->whereIn('id', $selectedExistingFranchises)
                            ->pluck('answer', 'question')
                            ->all();

                        foreach ($selectedFranchises as $question => $answer) {
                            $franchises[] = [
                                [
                                    'key' => 'question',
                                    'value' => $question,
                                ],
                                [
                                    'key' => 'answer',
                                    'value' => $answer,
                                ],
                            ];
                        }
                    }
                }
            }

            $franchises = array_filter($franchises);

            if (empty($franchises)) {
                return;
            }

            foreach ($franchises as $key => $item) {
                if (! $item[0]['value'] && ! $item[1]['value']) {
                    Arr::forget($value, $key);
                }
            }

            $schemaItems = new FranchiseCollection();

            foreach ($franchises as $item) {
                $schemaItems->push(
                    new FranchiseItem(BaseHelper::clean($item[0]['value']), BaseHelper::clean($item[1]['value']))
                );
            }

            app(FranchiseContract::class)->registerSchema($schemaItems);
        }, 39, 2);
    }
}
