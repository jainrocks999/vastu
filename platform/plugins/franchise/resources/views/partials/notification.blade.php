@if (!$isApproved)
    <x-core::alert type="warning" class="approve-product-warning">
        {!! BaseHelper::clean(
            trans('plugins/franchise::store.product_approval_notification', [
                'vendor' => Html::link($product->createdBy->store->url, $product->createdBy->store->name, [
                    'target' => '_blank',
                ]),
                'approve_link' => Html::link(
                    route('products.approve-product', $product->id),
                    trans('plugins/franchise::store.approve_here'),
                    ['class' => 'approve-product-for-selling-button'],
                ),
            ]),
        ) !!}
    </x-core::alert>
@else
    <x-core::alert type="info" class="approved-product-info">
        {!! BaseHelper::clean(
            trans('plugins/franchise::store.product_approved_notification', [
                'vendor' => Html::link($product->createdBy->store->url, $product->createdBy->store->name, [
                    'target' => '_blank',
                ]),
                'user' => $product->approvedBy->name,
            ]),
        ) !!}
    </x-core::alert>
@endif

@push('footer')
    @if (!$isApproved)
        <x-core::modal
            id="approve-product-for-selling-modal"
            type="warning"
            :title="trans('plugins/franchise::store.approve_product_confirmation')"
            button-id="confirm-approve-product-for-selling-button"
            :button-label="trans('plugins/franchise::store.approve')"
        >
            {!! trans('plugins/franchise::store.approve_product_confirmation_description', [
                'vendor' => Html::link($product->createdBy->store->url, $product->createdBy->store->name, ['target' => '_blank']),
            ]) !!}
        </x-core::modal>
    @endif
@endpush
