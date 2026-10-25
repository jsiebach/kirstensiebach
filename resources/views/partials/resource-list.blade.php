<ul class="resource-list list-unstyled">
    @foreach ($resourceItems as $item)
        <li class="resource-item resource-item--{{ $item->type->value }}">
            <div class="resource-item__icon" aria-hidden="true">
                <i class="fa {{ $item->type->siteIcon() }}"></i>
            </div>
            <div class="resource-item__body">
                <h4 class="resource-item__title">{{ $item->title }}</h4>
                @if ($item->description)
                    <p class="resource-item__description">{!! nl2br(e($item->description)) !!}</p>
                @endif

                @switch($item->type)
                    @case(\App\Enums\ResourceType::Document)
                        @if ($item->fileUrl())
                            <a class="btn btn-primary btn-sm" href="{{ $item->fileUrl() }}" download="{{ $item->downloadName() }}">
                                <i class="fa fa-download"></i> Download
                                @if ($details = $item->fileDetails())
                                    <span class="resource-item__details">({{ $details }})</span>
                                @endif
                            </a>
                        @endif
                        @break
                    @case(\App\Enums\ResourceType::Image)
                        @if ($item->fileUrl())
                            <a class="resource-item__image thumbnail" href="{{ $item->fileUrl() }}" target="_blank" rel="noopener">
                                <img src="{{ $item->fileUrl() }}" alt="{{ $item->title }}" loading="lazy">
                            </a>
                            <a href="{{ $item->fileUrl() }}" download="{{ $item->downloadName() }}"><i class="fa fa-download"></i> Download picture</a>
                        @endif
                        @break
                    @case(\App\Enums\ResourceType::Video)
                        @if ($item->youtubeId())
                            <div class="resource-item__video">
                                @include('partials.youtube', ['id' => $item->youtubeId(), 'title' => $item->title])
                            </div>
                        @endif
                        @break
                    @case(\App\Enums\ResourceType::Link)
                        <a href="{{ $item->url }}" target="_blank" rel="noopener">{{ $item->url }} <i class="fa fa-external-link"></i></a>
                        @break
                @endswitch
            </div>
        </li>
    @endforeach
</ul>
