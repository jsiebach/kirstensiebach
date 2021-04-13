@extends('master')

@section('page_title')
    {{$page->meta_title}}
@endsection

@section('body')
    <div class="resume">
        <a class="btn btn-success pull-right" href="/uploads/Siebach_CV_3-10-2021.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Download PDF</a>
        @include('partials.page-title')
        <h2>Education</h2>
        <ul>
            @foreach(json_decode($page->education) as $school)
                <li>{{$school->info}}</li>
            @endforeach
        </ul>
        <hr>
        <h2>Achievements and Honors</h2>
        <ul>
            @foreach(json_decode($page->achievements) as $honor)
                <li class="partioned">{{$honor->description}} <span class="right">{{$honor->date}}</span></li>
            @endforeach
        </ul>
        <hr>
        <h2>Academic Talks</h2>
        <ul>
            @foreach(json_decode($page->talks) as $talk)
                <li>{{$talk->info}}</li>
            @endforeach
        </ul>
        <hr>
        <h2>Professional Experience</h2>
        <ul>
            @foreach(json_decode($page->experience) as $experience)
                <li class="partioned">{{$experience->description}} <span class="right">{{$experience->date}}</span></li>
            @endforeach
        </ul>
        <hr>
        <h2>Science Team and Professional Memberships</h2>
        <ul>
            @foreach(json_decode($page->memberships) as $membership)
                <li>{{$membership->info}}</li>
            @endforeach
        </ul>
        <hr>
        <h2>Technical Skills</h2>
        <ul>
            @foreach(json_decode($page->skills) as $skill)
                <li class="partioned"><strong>{{$skill->category}}</strong> <span class="right">{{$skill->skills}}</span></li>
            @endforeach
        </ul>
        <hr>
    </div>
@endsection
