{{-- Click-to-play preview: the YouTube player (~4MB) only loads when someone presses play --}}
<div class="videoWrapper">
    <button type="button" class="video-preview" data-youtube-id="{{ $id }}" data-title="{{ $title }}" aria-label="Play video: {{ $title }}">
        <img src="https://i.ytimg.com/vi/{{ $id }}/hqdefault.jpg" alt="" loading="lazy">
        <span class="video-preview__play" aria-hidden="true"></span>
    </button>
</div>
