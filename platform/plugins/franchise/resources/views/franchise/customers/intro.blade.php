@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-plugins-ecommerce::intro
        :title="trans('plugins/franchise::customer.intro.title')"
        :subtitle="trans('plugins/franchise::customer.intro.description')"
        :action-url="route('franchise.customers.create')"
        :action-label="trans('plugins/franchise::customer.intro.button_text')"
    >
        <x-slot:icon>
            <img
                src="{{ asset('vendor/core/plugins/franchise/images/empty-customer.png') }}"
                alt="image"
            >
        </x-slot:icon>
    </x-plugins-ecommerce::intro>
@stop
