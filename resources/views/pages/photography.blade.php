@extends('master')

@section('page_title')
    {{$page->meta_title}}
@endsection

@section('body')
    @include('partials.page-title')
    <p>Photography from my travels in the US and abroad. Please contact me at <a href="mailto:photography@kirstensiebach.com">photography@kirstensiebach.com</a> to purchase an image!</p>
    <p>More photos available for viewing on <a target="_blank" href="https://www.flickr.com/photos/77604748@N07/albums">my Flickr page!</a></p>
    <div class="content">
        <div class="niceGallery" id="gallery">
        </div>
    </div>
@endsection