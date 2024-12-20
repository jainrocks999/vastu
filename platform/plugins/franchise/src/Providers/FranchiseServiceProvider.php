<?php

namespace Botble\Franchise\Providers;

use Botble\ACL\Models\User;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\Form;
use Botble\Base\Facades\MacroableModels;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Models\BaseModel;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Botble\Base\Supports\Helper;
use Botble\Base\Supports\Language;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Ecommerce\PanelSections\SettingEcommercePanelSection;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Http\Middleware\RedirectIfNotVendor;
use Botble\Franchise\Models\Revenue;
use Botble\Franchise\Models\Scopes\HideProductsByLockedVendorScope;
use Botble\Franchise\Models\Store;
use Botble\Franchise\Models\VendorInfo;
use Botble\Franchise\Models\Withdrawal;
use Botble\Franchise\Repositories\Eloquent\RevenueRepository;
use Botble\Franchise\Repositories\Eloquent\StoreRepository;
use Botble\Franchise\Repositories\Eloquent\VendorInfoRepository;
use Botble\Franchise\Repositories\Eloquent\WithdrawalRepository;
use Botble\Franchise\Repositories\Interfaces\RevenueInterface;
use Botble\Franchise\Repositories\Interfaces\StoreInterface;
use Botble\Franchise\Repositories\Interfaces\VendorInfoInterface;
use Botble\Franchise\Repositories\Interfaces\WithdrawalInterface;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\SiteMapManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class FranchiseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        if (! is_plugin_active('ecommerce')) {
            return;
        }

        $this->app->bind(StoreInterface::class, function () {
            return new StoreRepository(new Store());
        });

        $this->app->bind(RevenueInterface::class, function () {
            return new RevenueRepository(new Revenue());
        });

        $this->app->bind(WithdrawalInterface::class, function () {
            return new WithdrawalRepository(new Withdrawal());
        });

        $this->app->bind(VendorInfoInterface::class, function () {
            return new VendorInfoRepository(new VendorInfo());
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app['router']->aliasMiddleware('vendor', RedirectIfNotVendor::class);

        AliasLoader::getInstance()->alias('FranchiseHelper', FranchiseHelper::class);
    }

    public function boot(): void
    {
        if (! is_plugin_active('franchise')) {
            return;
        }

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 128);

        $this
            ->setNamespace('plugins/franchise')
            ->loadAndPublishConfigurations(['permissions', 'email', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->publishAssets()
            ->loadRoutes(['base', 'fronts']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Store::class, [
                'name',
                'description',
                'content',
                'address',
                'company',
                'cover_image',
            ]);
        }

        DashboardMenu::beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-franchise',
                    'priority' => 1,
                    'parent_id' => null,
                    'name' => 'Franchise',
                    'icon' => 'ti ti-building-store',
                    'url' => '#',
                    'permissions' => ['franchise.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-store',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-franchise',
                    'name' => 'Franchise Services',
                    'icon' => null,
                    'url' => fn () => route('franchise_services.index'),
                    'permissions' => ['franchise.services'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-franchise-vendors',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-franchise',
                    'name' => 'Franchise Request',
                    'icon' =>'ti ti-users',
                    'url' => fn () => route('franchise.index'),
                    'permissions' => ['franchise.index'],
                ]);
                // ->when(
                //     FranchiseHelper::getSetting('verify_vendor', 1),
                //     function (DashboardMenuSupport $dashboardMenu) {
                //         $dashboardMenu
                //             ->registerItem([
                //                 'id' => 'cms-plugins-franchise-unverified-vendor',
                //                 'priority' => 1,
                //                 'parent_id' => 'cms-plugins-franchise',
                //                 'name' => 'Manage Franchise',
                //                 'icon' => null,
                //                 'url' => fn () => route('franchise.unverified-vendors.index'),
                //                 'permissions' => ['franchise.unverified-vendors.index'],
                //             ]);
                //     }
                // )
                // ->registerItem([
                //     'id' => 'cms-plugins-franchise-reports',
                //     'priority' => 4,
                //     'parent_id' => 'cms-plugins-franchise',
                //     'name' => 'plugins/franchise::franchise.reports.name',
                //     'icon' => null,
                //     'url' => fn () => '',
                //     'permissions' => ['franchise.reports'],
                // ]);
        });

        DashboardMenu::default();

        $this->app['events']->listen(RouteMatched::class, function () {
            if (! FranchiseHelper::getSetting('verify_vendor', 1)) {
                config([
                    'plugins.franchise.email.templates' => Arr::except(
                        config('plugins.franchise.email.templates'),
                        'verify_vendor'
                    ),
                ]);
            }

            EmailHandler::addTemplateSettings(
                FRANCHISE_MODULE_SCREEN_NAME,
                config('plugins.franchise.email', [])
            );
        });

        // PanelSectionManager::beforeRendering(function () {
        //     PanelSectionManager::default()->registerItem(
        //         SettingEcommercePanelSection::class,
        //         fn () => PanelSectionItem::make('settings.franchise.franchise')
        //             ->setTitle(trans('plugins/franchise::setting.franchise.name'))
        //             ->withIcon('ti ti-building-store')
        //             ->withDescription(trans('plugins/franchise::setting.franchise.description'))
        //             ->withPriority(150)
        //             ->withRoute('franchise.settings'),
        //     );
        // });

        SlugHelper::registering(function () {
            SlugHelper::registerModule(Store::class, fn () => trans('plugins/franchise::store.stores'));
            SlugHelper::setPrefix(Store::class, 'stores');
        });

        SeoHelper::registerModule([Store::class]);
        SiteMapManager::registerKey('stores');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(OrderSupportServiceProvider::class);

        $this->app['events']->listen('eloquent.deleted: ' . Customer::class, function (Customer $customer) {
            Revenue::query()->where('customer_id', $customer->getKey())->delete();
            Withdrawal::query()->where('customer_id', $customer->getKey())->delete();
            VendorInfo::query()->where('customer_id', $customer->getKey())->delete();
            Store::query()->where('customer_id', $customer->getKey())->each(fn (Store $store) => $store->delete());
        });

        $this->app['events']->listen('eloquent.deleted: ' . Customer::class, function (Customer $customer) {
            if (! $customer->is_vendor) {
                return;
            }

            SpecificationGroup::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();

            SpecificationAttribute::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();

            SpecificationTable::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();
        });

        $this->app->booted(function () {
            Customer::resolveRelationUsing('store', function ($model) {
                return $model->hasOne(Store::class)->withDefault();
            });

            Order::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            Product::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            Product::resolveRelationUsing('approvedBy', function ($model) {
                return $model->belongsTo(User::class, 'approved_by')->withDefault();
            });

            Customer::resolveRelationUsing('vendorInfo', function ($model) {
                return $model->hasOne(VendorInfo::class, 'customer_id')->withDefault();
            });

            Discount::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            MacroableModels::addMacro(Customer::class, 'getBalanceAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->balance : 0;
            });

            MacroableModels::addMacro(Customer::class, 'getBankInfoAttribute', function () {
                /**
                 * @return array
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->bank_info : [];
            });

            MacroableModels::addMacro(Customer::class, 'getTaxInfoAttribute', function () {
                /**
                 * @return array
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->tax_info : [];
            });

            MacroableModels::addMacro(Customer::class, 'getTotalFeeAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->total_fee : 0;
            });

            MacroableModels::addMacro(Customer::class, 'getTotalRevenueAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->total_revenue : 0;
            });

            // if (! $this->app->runningInConsole()) {
            //     Product::addGlobalScope(HideProductsByLockedVendorScope::class);
            // }

            if (is_plugin_active('language-advanced')) {
                $this->loadRoutes(['language-advanced']);
            }

            $emailVariables = [
                'store' => 'plugins/franchise::store.store',
                'store_name' => 'plugins/franchise::store.store_name',
                'store_address' => 'plugins/franchise::store.store_address',
                'store_phone' => 'plugins/franchise::store.store_phone',
                'store_url' => 'plugins/franchise::store.store_url',
            ];

            $emailTemplates = [
                'plugins.ecommerce.email.templates.customer_new_order.variables',
                'plugins.ecommerce.email.templates.admin_new_order.variables',
                'plugins.ecommerce.email.templates.customer_cancel_order.variables',
                'plugins.ecommerce.email.templates.order_confirm.variables',
                'plugins.ecommerce.email.templates.order_confirm_payment.variables',
                'plugins.ecommerce.email.templates.order_recover.variables',
                'plugins.ecommerce.email.templates.order-return-request.variables',
                'plugins.ecommerce.email.templates.invoice-payment-created.variables',
                'plugins.ecommerce.email.templates.review_products.variables',
                'plugins.ecommerce.email.templates.download_digital_products.variables',
            ];

            if (! EcommerceHelper::isDisabledPhysicalProduct()) {
                $emailTemplates = [
                    ...$emailTemplates,
                    'plugins.ecommerce.email.templates.customer_delivery_order.variables',
                    'plugins.ecommerce.email.templates.customer_order_delivered.variables',
                ];
            }

            $config = $this->app['config'];

            foreach ($emailTemplates as $emailTemplate) {
                $config->set([$emailTemplate => array_merge($config->get($emailTemplate, []), $emailVariables)]);
            }
        });

        Form::component('customEditor', FranchiseHelper::viewPath('vendor-dashboard.forms.partials.custom-editor'), [
            'name',
            'value' => null,
            'attributes' => [],
        ]);

        Form::component('customImage', FranchiseHelper::viewPath('vendor-dashboard.forms.partials.custom-image'), [
            'name',
            'value' => null,
            'attributes' => [],
        ]);

        Form::component('customImages', FranchiseHelper::viewPath('vendor-dashboard.forms.partials.custom-images'), [
            'name',
            'values' => null,
            'attributes' => [],
        ]);
    }

    public function setInAdmin(bool $isInAdmin): bool
    {
        $segment = request()->segment(1);

        if ($segment && in_array($segment, Language::getLocaleKeys())) {
            $segment = request()->segment(2);
        }

        return $segment === 'vendor' || $isInAdmin;
    }
}
