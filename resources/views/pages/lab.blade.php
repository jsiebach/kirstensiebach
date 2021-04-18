@extends('master')

@section('page_title')
    {{$page->meta_title}}
@endsection

@section('body')
    @include('partials.page-title')

    <img style="width: 80%; height: auto; margin-left: 10%;" src="/storage/{{ $page->banner }}" alt="">
    <br>
    <br>
    @markdown($page->intro)
    <h2>Lab Team</h2>
    @foreach($page->teamMembers as $teamMember)
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h3>{{ $teamMember->name }}</h3>
                <h5>{{ $teamMember->email }}</h5>
                @markdown($teamMember->bio)
            </div>
            <div class="col-md-6">
                <img style="max-width: 100%; height: auto;" src="/storage/{{ $teamMember->profile_picture }}" alt="">
            </div>
        </div>
    @endforeach
    <h2>{{ $page->facilities_title }}</h2>
    <hr>
    @markdown($page->facilities_content)
@endsection
