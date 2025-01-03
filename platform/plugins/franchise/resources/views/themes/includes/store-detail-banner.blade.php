<div class="bb-shop-banner" @if ($coverImage) style="background-image: url('{{ RvMedia::getImageUrl($coverImage) }}');" @endif>
    <div class="bb-shop-banner-overlay"></div>
    <div class="container bb-shop-banner-content">
        {{ RvMedia::image($store->logo, $store->name, useDefaultImage: true, attributes: ['class' => 'bb-shop-banner-logo']) }}

        <div class="bb-shop-banner-info">
            <h2 class="bb-shop-banner-name">{{ $store->name }}</h2>

            @if (EcommerceHelper::isReviewEnabled())
                <div class="bb-shop-banner-rating">
                    @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $store->reviews()->avg('star'), 'size' => 80])
                    <small>{{ __('(:count reviews)', ['count' => number_format($store->reviews->count())]) }}</small>
                </div>
            @endif

            @if ($store->full_address || $store->phone || $store->email)
                <div class="bb-shop-banner-contact">
                    @if (!FranchiseHelper::hideStoreAddress() && $store->full_address)
                        <div class="bb-shop-banner-address d-flex gap-1">
                            <x-core::icon name="ti ti-map-pin" />
                            {{ $store->full_address }}
                        </div>
                    @endif

                    @if (!FranchiseHelper::hideStorePhoneNumber() && $store->phone)
                        <div class="bb-shop-banner-phone d-flex gap-1">
                            <x-core::icon name="ti ti-phone" />
                            <a href="tel:{{ $store->phone }}">{{ $store->phone }}</a>
                        </div>
                    @endif

                    @if (!FranchiseHelper::hideStoreEmail() && $store->email)
                        <div class="bb-shop-banner-address d-flex gap-1">
                            <x-core::icon name="ti ti-mail" />
                            <a href="mailto:{{ $store->email }}">{{ $store->email }}</a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($store->description)
                <div class="bb-shop-banner-description ck-content">
                    {!! BaseHelper::clean($store->description) !!}
                </div>
            @endif

            @if (!FranchiseHelper::hideStoreSocialLinks() && ($socials = $store->getMetaData('social_links', true)))
                <ul class="bb-shop-banner-socials">
                    @foreach (FranchiseHelper::getAllowedSocialLinks() as $key => $social)
                        @continue(! Arr::get($socials, $key))

                        <li>
                            <a href="{{ Arr::get($social, 'url') . Arr::get($socials, $key) }}" target="_blank">
                                @if ($icon = Arr::get($social, 'icon'))
                                    <x-core::icon :name="'ti ti-brand-' . $icon" />
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
