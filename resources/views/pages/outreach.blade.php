@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.page-title')
    <div class="row">
        <div class="{{ $page->intro_image ? 'col-md-7' : 'col-md-12' }} col-xs-12">
            @markdown($page->intro)
            @if ($page->contact_callout)
                <div class="alert alert-info text-center outreach-contact">
                    @markdown($page->contact_callout)
                </div>
            @endif
        </div>
        @if ($page->intro_image)
            <div class="col-md-5 col-xs-12">
                <div class="thumbnail">
                    <img src="/storage/{{ $page->intro_image }}" alt="Kirsten Siebach speaking at an outreach event">
                </div>
            </div>
        @endif
    </div>

    @php($videos = collect($page->videos ?? [])->filter(fn ($video) => \App\Support\YouTube::idFromUrl($video['youtube_url'] ?? null)))
    @if ($videos->isNotEmpty())
        <h3>Videos</h3>
        @foreach ($videos->chunk(2) as $row)
            <div class="row">
                @foreach ($row as $video)
                    <div class="col-xs-12 col-md-6 text-center outreach-video">
                        <h4>{{ $video['title'] }}</h4>
                        @include('partials.youtube', ['id' => \App\Support\YouTube::idFromUrl($video['youtube_url']), 'title' => $video['title']])
                        @if (filled($video['description'] ?? null))
                            <p class="outreach-video__description">{{ $video['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif

    @if (! empty($page->photos))
        <br><br>
        <div class="outreach-slideshow" style="width:74%;margin:0 13%">
            <div class="slideshow">
                <ul class="bxslider">
                    @foreach ($page->photos as $photo)
                        <li><img src="/storage/{{ $photo }}" alt="Outreach event photo"></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @foreach ($page->sections ?? [] as $section)
        @continue(empty($section['entries']))
        @php($hasDates = collect($section['entries'])->contains(fn ($entry) => filled($entry['date'] ?? null)))
        <br>
        <h3>{{ $section['heading'] }}</h3>
        <table class="table table-condensed table-striped table-hover">
            @foreach ($section['entries'] as $entry)
                <tr>
                    @if ($hasDates)
                        <td class="text-nowrap">{{ $entry['date'] ?? '' }}</td>
                    @endif
                    <td>
                        {{ $entry['description'] }}
                        @if (filled($entry['link'] ?? null))
                            <br><a href="{{ $entry['link'] }}" target="_blank" rel="noopener">{{ $entry['link'] }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endforeach
    @vite(['resources/js/outreach.js', 'resources/css/plugins.css'])
@endsection
