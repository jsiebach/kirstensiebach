@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.page-title')
    <p>Photography from my travels in the US and abroad. Please contact me at <a href="mailto:photography@kirstensiebach.com">photography@kirstensiebach.com</a> to purchase an image!</p>
    <p>More photos available for viewing on <a target="_blank" href="https://www.flickr.com/photos/77604748@N07/albums">my Flickr page!</a></p>
    <div class="content">
        <div class="niceGallery" id="gallery" data-album="{{ $page->flickr_album ?? 72157645780469251 }}">
        </div>
    </div>
    <script src="{{ mix('/js/photography.js') }}" defer></script>
    <link rel="stylesheet" href="{{ mix('/css/plugins.css') }}">
@endsection
