@if (! empty($product->video))
    @foreach($product->video as $video)
        @continue(! $video['url'])

        <div class="bb-product-video">
            @if ($video['provider'] === 'video')
                <video
                    id="{{ md5($video['url']) }}"
                    playsinline="playsinline"
                    mute="true"
                    preload="auto"
                    class="media-video"
                    aria-label="{{ $product->name }}"
                    poster="{{ $video['thumbnail'] }}"
                >
                    <source src="{{ $video['url'] }}" type="video/{{ File::extension($video['url']) ?: 'mp4' }}">
                    <img src="{{ $video['thumbnail'] }}" alt="{{ $video['url'] }}">
                </video>
                <button class="bb-button-trigger-play-video" data-target="{{ md5($video['url']) }}">
                    <x-core::icon name="ti ti-player-play-filled" />
                </button>
            @else
                <iframe
                    data-provider="{{ $video['provider'] }}"
                    src="{{ $video['url'] }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                </iframe>
            @endif
        </div>
    @endforeach
@endif
