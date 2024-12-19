<p class="text-muted">
    @if($isActivated)
        {{ trans('plugins/franchise::store.control.block_help') }}
    @else
        {!! BaseHelper::clean(trans('plugins/franchise::store.control.blocked_help', ['reason' => "<strong>$blockReason</strong>"])) !!}
    @endif
</p>

<x-core::button
    type="button"
    :color="$isActivated ? 'danger' : 'info'"
    size="sm"
    data-bs-toggle="modal"
    data-bs-target="#vendor-control-modal"
>
    @if($isActivated)
        {{ trans('plugins/franchise::store.control.block') }}
    @else
        {{ trans('plugins/franchise::store.control.unblock') }}
    @endif
</x-core::button>
