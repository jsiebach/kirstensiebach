@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.callout')
    @include('partials.page-title')
    <img class="img-right thumbnail pull-right" src="/storage/{{ $page->banner }}" alt="">
    @markdown($page->intro)
    <div class="clearfix"></div>
    <h2>Current Projects</h2>
    <hr>
    @foreach($page->research()->ordered()->get() as $i => $research)
    <h3>{{ $research->project_name }}</h3>
    @if($research->image)
    @if($i % 2 === 0)
    <img class="img-left thumbnail pull-left" style="max-height: 500px" src="/storage/{{ $research->image }}">
    @else
    <img class="img-right thumbnail pull-right" style="max-height: 500px" src="/storage/{{ $research->image }}">
    @endif
    @endif
    @markdown($research->description)
    <div class="clear clearfix"></div>
    <hr>
    @endforeach
    @markdown($page->lower_content)
@endsection
