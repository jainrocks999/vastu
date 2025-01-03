@if ($product->variations_count > 0 && ! $product->is_variation)
    <span class="text-muted">&mdash;</span>
@else
    <x-core::form.text-input
        :label="false"
        value="{{ $product->{$type} ?: null }}"
        data-bb-toggle="product-bulk-change"
        data-url="{{ route('ecommerce.product-prices.update', ['product' => $product->id]) }}"
        data-column="student_price"
        data-id="product-price-{{ $type }}-{{ $product->id }}"
        step="any"
        class="input-mask-number"
        :group-flat="true"
        :name="null"
    >
    <x-slot:prepend>
            <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
        </x-slot:prepend>
    </x-core::form.text-input>
@endif
