@extends(FranchiseHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            @include('plugins/ecommerce::order-returns.partials.order-information', [
                'productEditRouteName' => 'franchise.vendor.products.edit',
                'orderReturnEditRouteName' => 'franchise.vendor.order-returns.edit',
            ])
        </div>

        <div class="col-md-3">
            @include('plugins/ecommerce::order-returns.partials.customer-information')
        </div>
    </div>
@endsection
