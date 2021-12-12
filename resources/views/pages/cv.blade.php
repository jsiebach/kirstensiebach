@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    <div class="resume" style="height: 100%">
        <a class="btn btn-success pull-right" href="https://kirstensiebach.com/uploads/Siebach_CV_3-10-2021.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Download PDF</a>
        @include('partials.page-title')
        <embed src="https://kirstensiebach.com/uploads/Siebach_CV_3-10-2021.pdf" type="application/pdf" width="100%" height="100%">
    </div>
@endsection
