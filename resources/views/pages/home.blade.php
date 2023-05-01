@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.callout')
    <div class="row">
        <div class="col-md-8 col-sm-7">
            <h1>Kirsten Siebach
                <small><i>{{$page->tagline}}</i></small>
            </h1>
            <hr>
            <img class="banner" src="/storage/{{ $page->banner }}" alt="">
            <div>
                <h3>Bio</h3>
                @markdown($page->bio)
            </div>
            <div>
                <h3>Press</h3>
                <ul class="list-group">
                    @foreach(json_decode($page->press) as $press)
                        <li class="list-group-item">
                            <a href="{!! $press->link !!}" target="_blank">{!! $press->title !!}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4 col-sm-5">
            <div class="text-center">
                <div class="thumbnail" style="border: none;margin-bottom:5px;">
                    <img class="img-circle" src="/storage/{{ $page->profile_picture}}" style="width:80%;margin-top:20px;">
                </div>
                <div class="panel-body">
                    <p>
                        {!! $page->profile_summary !!}
                    </p>
                </div>
                <table class="table table-condensed text-left">
                    @foreach($page->socialLinks as $link)
                        <tr>
                            <td style="padding-left: 30px;">
                                <i class="fa {!! $link->icon !!}"></i>
                            </td>
                            <td>
                                <a href="{!! $link->link !!}" target="_blank">{!! $link->title !!}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('header-styles')
    {!! nova_get_setting('schema_markup') !!}
@append
