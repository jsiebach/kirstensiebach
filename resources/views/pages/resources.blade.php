@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('header-styles')
    @unless ($page->isListed())
        <meta name="robots" content="noindex">
    @endunless
@endsection

@section('body')
    @include('partials.page-title')
    @if ($page->intro)
        <div class="resources-intro">
            @markdown($page->intro)
        </div>
    @endif

    @if ($unsectionedResourceItems->isEmpty() && $resourceSections->isEmpty())
        <p class="text-muted">Resources are on their way. Please check back soon.</p>
    @endif

    @if ($unsectionedResourceItems->isNotEmpty())
        @include('partials.resource-list', ['resourceItems' => $unsectionedResourceItems])
    @endif

    @foreach ($resourceSections as $section)
        <section class="resources-section">
            <h3 class="resources-section__name">{{ $section->name }}</h3>
            @if ($section->description)
                <p class="resources-section__description">{{ $section->description }}</p>
            @endif
            @include('partials.resource-list', ['resourceItems' => $section->resourceItems])
        </section>
    @endforeach
@endsection
