<?php

namespace Botble\Orders\Providers;

use Botble\Orders\Facades\OrdersManager;
use Botble\Orders\Forms\OrdersForm;
use Botble\Orders\Models\Orders;

use Botble\Orders\Repositories\Eloquent\OrdersRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderReturnItemRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderReturnRepository;

use Botble\Orders\Repositories\Interfaces\OrdersInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Throwable;

class OrderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(OrdersInterface::class, function () {
            return new OrdersRepository(new Orders());
        });

        if (! setting('ecommerce_disable_physical_product')) {
            $this->app->bind(ShippingInterface::class, function () {
                return new ShippingRepository(new Shipping());
            });

            $this->app->bind(ShippingRuleInterface::class, function () {
                return new ShippingRuleRepository(new ShippingRule());
            });

            $this->app->bind(ShippingRuleItemInterface::class, function () {
                return new ShippingRuleItemRepository(new ShippingRuleItem());
            });

            $this->app->bind(ShipmentInterface::class, function () {
                return new ShipmentRepository(new Shipment());
            });

            $this->app->bind(ShipmentHistoryInterface::class, function () {
                return new ShipmentHistoryRepository(new ShipmentHistory());
            });
        }

        $this->app->bind(OrderInterface::class, function () {
            return new OrderRepository(new Order());
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryRepository(new OrderHistory());
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductRepository(new OrderProduct());
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressRepository(new OrderAddress());
        });

        $this->app->bind(OrderReturnInterface::class, function () {
            return new OrderReturnRepository(new OrderReturn());
        });

        $this->app->bind(OrderReturnItemInterface::class, function () {
            return new OrderReturnItemRepository(new OrderReturnItem());
        });

        AliasLoader::getInstance()->alias('OrdersManager', OrdersManager::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/orders')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadHelpers()
            ->loadAndPublishViews();

        DashboardMenu::beforeRetrieving(function () {
            DashboardMenu::make()
            ->registerItem([
                'id' => 'cms-customer-list',
                'priority' => 1,
                'name' => 'Customer Lists',
                'url' => fn () => route('customers.index'),
                'icon' => 'ti ti-home',
                'permissions' => ['orders.index'],
            ]);

            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-orders',
                    'priority' => 1,
                    'icon' => 'ti ti-ad-circle',
                    'name' => 'plugins/orders::orders.name',
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-orders-list',
                    'parent_id' => 'cms-plugins-orders',
                    'priority' => 1,
                    'name' => 'plugins/orders::orders.product_order',
                    'icon' => 'ti ti-truck-delivery',
                    'url' => fn () => route('orders.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-orders-course',
                    'parent_id' => 'cms-plugins-orders',
                    'priority' => 2,
                    'name' => 'plugins/orders::orders.course_order',
                    'icon' => 'ti ti-ad-circle',
                    'url' => fn () => route('orders.course_order.index'),
                    'permissions' => ['orders.index'],
                ]) 
                ->registerItem([
                    'id' => 'cms-plugins-appointment-orders',
                    'parent_id' => 'cms-plugins-orders',
                    'priority' => 3,
                    'name' => 'plugins/orders::orders.franchise_appointment',
                    'icon' => 'ti ti-ad-circle',
                    'url' => fn () => route('orders.franchise.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-order-invoice',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-orders',    
                    'name' => 'plugins/orders::orders.invoice',
                    'icon' => 'ti ti-file-invoice',
                    'url' => fn () => route('orders.invoice.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-order-shipment',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-orders',
                    'name' => 'plugins/orders::orders.shipment',
                    'icon' => 'ti ti-basket-down',
                    'url' => fn () => route('orders.shipments.index'),
                    'permissions' => ['orders.index'],
                ]);
               

                // ->registerItem([
                //     'id' => 'cms-plugins-orders',
                //     'priority' => 8,
                //     'parent_id' => 'cms-plugins-orders',
                //     'name' => 'plugins/ecommerce::order.menu',
                //     'icon' => 'ti ti-truck-delivery',
                //     'url' => fn () => route('orders.index'),
                //     'permissions' => ['orders.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-incomplete-order',
                //     'priority' => 1,
                //     'parent_id' => 'cms-plugins-orders',
                //     'name' => 'plugins/ecommerce::order.incomplete_order',
                //     'icon' => 'ti ti-basket-cancel',
                //     'url' => fn () => route('orders.incomplete-list'),
                //     'permissions' => ['orders.index'],
                // ])
                // ->registerItem([
                //     'id' => 'cms-plugins-order-return',
                //     'priority' => 2,
                //     'parent_id' => 'cms-plugins-orders',
                //     'name' => 'plugins/ecommerce::order.order_return',
                //     'icon' => 'ti ti-basket-down',
                //     'url' => fn () => route('order_returns.index'),
                //     'permissions' => ['orders.edit'],
                // ]);
        });
         
       
       
        // PanelSectionManager::default()->beforeRendering(function () {
        //     PanelSectionManager::registerItem(
        //         SettingOthersPanelSection::class,
        //         fn () => PanelSectionItem::make('orders')
        //             ->setTitle(trans('plugins/orders::orders.settings.title'))
        //             ->withIcon('ti ti-ad-circle')
        //             ->withPriority(480)
        //             ->withDescription(trans('plugins/orders::orders.settings.description'))
        //             ->withRoute('orders.settings')
        //     );
        // });

        $this->app['events']->listen(RouteMatched::class, function () {
            if (class_exists(Shortcode::class)) {
                Shortcode::register('orders', __('Orders'), __('Orders'), function ($shortcode) {
                    if (! $shortcode->key) {
                        return null;
                    }

                    return OrdersManager::displayOrders((string) $shortcode->key);
                });

                Shortcode::setAdminConfig('orders', function ($attributes) {
                    $orders = Orders::query()
                        ->wherePublished()
                        ->pluck('name', 'key')
                        ->all();

                    return ShortcodeForm::createFromArray($attributes)
                        ->withLazyLoading()
                        ->add(
                            'key',
                            SelectField::class,
                            SelectFieldOption::make()
                                ->label(trans('plugins/orders::orders.select_ad'))
                                ->choices($orders)
                        );
                });
            }
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Orders::class, [
                'name',
                'image',
                'url',
            ]);
        }

        if (defined('THEME_FRONT_HEADER')) {
            add_filter(THEME_FRONT_HEADER, function ($html) {
                $autoOrders = setting('orders_google_ordersense_auto_orders');

                if (! $autoOrders) {
                    return $html;
                }

                return $html . $autoOrders;
            }, 128);

            add_filter(THEME_FRONT_HEADER, function ($html) {
                $clientId = setting('orders_google_ordersense_unit_client_id');

                if (! $clientId) {
                    return $html;
                }

                return $html . view('plugins/orders::partials.google-ordersense.unit-orders-header', compact('clientId'))->render();
            }, 128);

            add_filter(THEME_FRONT_HEADER, function ($html) {
                $clientId = setting('orders_google_ordersense_unit_client_id');

                if (! $clientId) {
                    return $html;
                }

                return $html . view('plugins/orders::partials.google-ordersense.unit-orders-footer')->render();
            }, 128);
        }

        try {
            add_filter('orders_render', function (?string $html, string|array $location, array $attributes = []) {
                if (! is_string($location)) {
                    return null;
                }

                return $html . OrdersManager::display($location, $attributes);
            }, 128, 3);
        } catch (Throwable $exception) {
            BaseHelper::logError($exception);
        }

        OrdersForm::beforeRendering(function () {
            add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, function ($request, $data = null) {
                if (! $data instanceof Orders || ! in_array(Route::currentRouteName(), ['orders.create', 'orders.edit'])) {
                    return false;
                }

                echo view('plugins/orders::partials.notification')
                    ->render();

                return true;
            }, 45, 2);
        });
    }
}
