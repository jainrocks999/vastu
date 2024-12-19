{!! SeoHelper::render() !!}

@include(FranchiseHelper::viewPath('vendor-dashboard.layouts.header-meta'))

<link
    href="{{ asset('vendor/core/plugins/franchise/fonts/linearicons/linearicons.css') }}?v={{ FranchiseHelper::getAssetVersion() }}"
    rel="stylesheet"
>
<link
    href="{{ asset('vendor/core/plugins/franchise/css/franchise.css') }}?v={{ FranchiseHelper::getAssetVersion() }}"
    rel="stylesheet"
>

@if (BaseHelper::isRtlEnabled())
    <link href="{{ asset('vendor/core/core/base/css/core.rtl.css') }}" rel="stylesheet">

    <link
        href="{{ asset('vendor/core/plugins/franchise/css/franchise-rtl.css') }}?v={{ FranchiseHelper::getAssetVersion() }}"
        rel="stylesheet"
    >
@endif

@if (File::exists($styleIntegration = Theme::getStyleIntegrationPath()))
    {!! Html::style(Theme::asset()->url('css/style.integration.css?v=' . filectime($styleIntegration))) !!}
@endif