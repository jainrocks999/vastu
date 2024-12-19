@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row row-cards">
        <div class="col-md-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/franchise::store.information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <img
                                src="{{ RvMedia::getImageUrl($vendor->store->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $vendor->store->name }}"
                                class="avatar avatar-rounded avatar-xl"
                            />
                        </div>

                        @if ($vendor->store->id)
                            <a href="{{ route('franchise.store.edit', $vendor->store->id) }}" target="_blank">
                                {{ $vendor->store->name }}
                                <x-core::icon name="ti ti-external-link" />
                            </a>
                        @endif
                    </div>

                    @if($vendor->store->phone)
                        <div class="hr my-3"></div>

                        <dl class="row p-3 pt-0">
                            <dt class="col">{{ trans('plugins/franchise::store.store_phone') }}</dt>
                            <dd class="col-auto">{{ $vendor->store->phone }}</dd>
                        </dl>
                    @endif
                </x-core::card.body>
            </x-core::card>
        </div>
        <div class="col-md-9">
            <x-core::card>
                <x-core::card.header>
                    {{ trans('plugins/franchise::store.vendor_information') }}
                </x-core::card.header>

                <x-core::card.body>
                    <x-core::datagrid>
                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/franchise::store.vendor_name') }}
                            </x-slot:title>

                            <a href="{{ route('customers.edit', $vendor->id) }}" target="_blank">
                                {{ $vendor->name }}
                                <x-core::icon name="ti ti-external-link" />
                            </a>
                        </x-core::datagrid.item>

                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/franchise::unverified-vendor.forms.email') }}
                            </x-slot:title>
                            {{ $vendor->email }}
                        </x-core::datagrid.item>

                        @if ($vendor->phone)
                            <x-core::datagrid.item>
                                <x-slot:title>
                                    {{ trans('plugins/franchise::unverified-vendor.forms.vendor_phone') }}
                                </x-slot:title>
                                {{ $vendor->phone }}
                            </x-core::datagrid.item>
                        @endif

                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/franchise::unverified-vendor.forms.registered_at') }}
                            </x-slot:title>
                            {{ BaseHelper::formatDateTime($vendor->created_at) }}
                        </x-core::datagrid.item>

                        @if($vendor->store->certificate_file)
                            <x-core::datagrid.item>
                                <x-slot:title>
                                    {{ trans('plugins/franchise::unverified-vendor.forms.certificate') }}
                                </x-slot:title>
                                <a href="{{ route('franchise.unverified-vendors.download-certificate', $vendor) }}" target="_blank">
                                    {{ trans('plugins/franchise::unverified-vendor.view_certificate') }}
                                </a>
                            </x-core::datagrid.item>
                        @endif

                        @if($vendor->store->government_id_file)
                            <x-core::datagrid.item>
                                <x-slot:title>
                                    {{ trans('plugins/franchise::unverified-vendor.forms.government_id') }}
                                </x-slot:title>
                                <a href="{{ route('franchise.unverified-vendors.download-government-id', $vendor) }}" target="_blank">
                                    {{ trans('plugins/franchise::unverified-vendor.view_government_id') }}
                                </a>
                            </x-core::datagrid.item>
                        @endif
                    </x-core::datagrid>
                </x-core::card.body>

                <x-core::card.footer class="text-end">
                    <x-core::button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#reject-vendor-modal"
                        icon="ti ti-x"
                    >
                        {{ trans('plugins/franchise::unverified-vendor.reject') }}
                    </x-core::button>
                    <x-core::button
                        data-bs-toggle="modal"
                        data-bs-target="#approve-vendor-for-selling-modal"
                        type="button"
                        color="primary"
                        icon="ti ti-check"
                    >
                        {{ trans('plugins/franchise::unverified-vendor.approve') }}
                    </x-core::button>
                </x-core::card.footer>
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <x-core::modal.action
        id="approve-vendor-for-selling-modal"
        type="warning"
        :form-action="route('franchise.unverified-vendors.approve-vendor', $vendor)"
        :title="trans('plugins/franchise::unverified-vendor.approve_vendor_confirmation')"
        :description="trans('plugins/franchise::unverified-vendor.approve_vendor_confirmation_description', [
            'vendor' => $vendor->name,
        ])"
        :submit-button-attrs="['id' => 'confirm-vendor-button']"
        :submit-button-label="trans('plugins/franchise::unverified-vendor.approve')"
    />

    <x-core::modal.action
        id="reject-vendor-modal"
        type="danger"
        :form-action="route('franchise.unverified-vendors.reject-vendor', $vendor)"
        :title="trans('plugins/franchise::unverified-vendor.reject_vendor_confirmation')"
        :description="trans('plugins/franchise::unverified-vendor.reject_vendor_confirmation_description', [
            'vendor' => $vendor->name,
        ])"
        :submit-button-attrs="['id' => 'confirm-vendor-button']"
        :submit-button-label="trans('plugins/franchise::unverified-vendor.reject')"
    />
@endpush
