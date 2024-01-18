@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.callout')
    @include('partials.page-title')
    <img class="banner" src="/storage/{{ $page->banner }}" alt="">
    <br>
    <br>
    @markdown($page->intro)
    <h2>Current Team</h2>
    @foreach($page->teamMembers()->where('alumni', false)->ordered()->get() as $i => $teamMember)
        @include('partials.lab-team', compact('i', 'teamMember'))
    @endforeach
    <h2>Lab Alumni</h2>
    @foreach($page->teamMembers()->where('alumni', true)->ordered()->get() as $i => $teamMember)
        @include('partials.lab-team', compact('i', 'teamMember'))
    @endforeach
    <hr>
    @markdown($page->lower_content)
@endsection
