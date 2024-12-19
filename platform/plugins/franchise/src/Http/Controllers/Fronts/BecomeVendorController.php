<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Forms\Fronts\BecomeVendorForm;
use Botble\Franchise\Http\Controllers\BaseController;
use Botble\Franchise\Http\Requests\Fronts\BecomeVendorRequest;
use Botble\Franchise\Models\Store;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\Theme;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BecomeVendorController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            if (! FranchiseHelper::isVendorRegistrationEnabled()) {
                abort(404);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $customer = auth('customer')->user();

        SeoHelper::setTitle(__('Become Vendor'));

        Theme::breadcrumb()
            ->add(__('Become Vendor'), route('franchise.vendor.become-vendor'));

        if ($customer->is_vendor) {
            $store = $customer->store;

            if (
                FranchiseHelper::getSetting('requires_vendor_documentations_verification', true)
                && (! $store->certificate_file || ! $store->government_id_file)
            ) {
                $storeInfo = [
                    'shop_name' => $store->name,
                    'shop_url' => $store->slug,
                    'shop_phone' => $store->phone,
                ];

                $form = BecomeVendorForm::createFromArray($storeInfo)
                    ->addBefore(
                        'shop_name',
                        'missing_documentation_alert',
                        HtmlField::class,
                        HtmlFieldOption::make()
                            ->content('<div class="alert alert-warning">' . __('Missing documentations! Please upload your certificate of incorporation and government ID to continue.') . '</div>')
                    )
                    ->setUrl(route('franchise.vendor.become-vendor.update'))
                    ->setMethod('PUT');

                return Theme::scope('franchise.become-vendor', compact('form'), FranchiseHelper::viewPath('become-vendor', false))
                    ->render();
            }

            if (FranchiseHelper::getSetting('verify_vendor', 1) && ! $customer->vendor_verified_at) {
                return Theme::scope('franchise.approving-vendor', compact('store'), FranchiseHelper::viewPath('approving-vendor', false))
                    ->render();
            }

            return redirect()->route('franchise.vendor.dashboard');
        }

        $form = BecomeVendorForm::create();

        return Theme::scope('franchise.become-vendor', compact('form'), FranchiseHelper::viewPath('become-vendor', false))
            ->render();
    }

    public function store(BecomeVendorRequest $request)
    {
        $customer = auth('customer')->user();

        if ($customer->is_vendor) {
            abort(404);
        }

        $existing = SlugHelper::getSlug($request->input('shop_url'), SlugHelper::getPrefix(Store::class));

        if ($existing) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Shop URL is existing. Please choose another one!'));
        }

        event(new Registered($customer));

        return $this
            ->httpResponse()
            ->setData([
                'redirect_url' => route('franchise.vendor.dashboard'),
            ])
            ->setMessage(__('Registered successfully!'));
    }

    public function update(BecomeVendorRequest $request)
    {
        $customer = auth('customer')->user();

        if (
            $customer->is_vendor
            && (! FranchiseHelper::getSetting('requires_vendor_documentations_verification', true)
                || ($customer->certificate_of_incorporation && $customer->government_id))
        ) {
            abort(404);
        }

        $store = $customer->store;

        $store->name = $request->input('shop_name');
        $store->phone = $request->input('shop_phone');

        Slug::query()->updateOrCreate([
            'reference_type' => Store::class,
            'reference_id' => $store->id,
            'key' => Str::slug($request->input('shop_url')),
            'prefix' => SlugHelper::getPrefix(Store::class),
        ]);

        $storage = Storage::disk('local');

        if (! $storage->exists("franchise/$store->slug")) {
            $storage->makeDirectory("franchise/$store->slug");
        }

        if ($certificateFile = $request->file('certificate_file')) {
            $certificateFilePath = $storage->putFileAs("franchise/$store->slug", $certificateFile, 'certificate.' . $certificateFile->getClientOriginalExtension());
            $store->certificate_file = $certificateFilePath;
        }

        if ($governmentIdFile = $request->file('government_id_file')) {
            $governmentIdFilePath = $storage->putFileAs("franchise/$store->slug", $governmentIdFile, 'government_id.' . $governmentIdFile->getClientOriginalExtension());
            $store->government_id_file = $governmentIdFilePath;
        }

        $store->save();

        return $this
            ->httpResponse()
            ->setData([
                'redirect_url' => route('franchise.vendor.become-vendor'),
            ])
            ->setMessage(__('Updated registration info successfully!'));
    }

    public function downloadCertificate()
    {
        $customer = auth('customer')->user();

        if (! $customer->is_vendor || ! $customer->store) {
            abort(404);
        }

        $storage = Storage::disk('local');

        if (! $storage->exists($certificate = $customer->store->certificate_file)) {
            abort(404);
        }

        return response()->file($storage->path($certificate));
    }

    public function downloadGovernmentId()
    {
        $customer = auth('customer')->user();

        if (! $customer->is_vendor || ! $customer->store) {
            abort(404);
        }

        $storage = Storage::disk('local');

        if (! $storage->exists($governmentId = $customer->store->government_id_file)) {
            abort(404);
        }

        return response()->file($storage->path($governmentId));
    }
}
