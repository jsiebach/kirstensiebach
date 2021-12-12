@extends('master')

@section('page_title'){{$page->meta_title}}@endsection
@section('meta_description'){{$page->meta_description}}@endsection

@section('body')
    @include('partials.page-title')
    <h2>Peer Reviewed <small><a href="#abstracts" class="pull-right">Scroll to First-Author Conference Abstracts</a></small></h2>
    <table class="table table-hover table-striped publications">
        <tbody v-for="section in publications">
        @foreach($publications as $title => $group)
        <tr><td colspan="2" style="text-align: center"><h2>{{$title}}</h2></td></tr>
        @foreach($group as $pub)
        <tr>
            <td>
                <p style="font-size: 18px">{{ $pub->title }}</p>
                <p>@markdown($pub->authors) {{$pub->date_published->format('Y')}}</p>
                <div><i>{{$pub->publication_name ? $pub->publication_name . " | ":""}}</i>{{$pub->published ? $pub->date_published->format("Y-m-d"):"Submitted ".$pub->date_published->format("Y-m-d")}}  </div>
                @if($pub->link && $pub->doi)
                <div>
                    DOI: <a class="" target="_blank" href="{{$pub->link}}">{{$pub->doi}}</a>
                </div>
                @endif
                @if($pub->doi && !$pub->link)
                    <div v-if="pub.doi && !pub.link">DOI: {{$pub->doi}}</div>
                @endif
                @if($pub->abstract)
                <div id="abstract-{{$pub->id}}" style="display: none">
                    <br>
                    <i><strong>Abstract:</strong>@markdown($pub->abstract)</i>
                </div>
                @endif
            </td>
            <td>
                @if($pub->abstract)
                <button class="btn btn-success btn-sm expand" data-id="{{$pub->id}}">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
        @endforeach
        </tbody>
    </table>
        <a style="padding-top: 40px; margin-top: -40px;" name="abstracts">
            &nbsp;
        </a>
            <h2>First-Author Conference Abstracts</h2>
    <table class="table table-hover table-striped publications">
        @foreach($abstracts as $abstract)
        <tr>
            <td>
                @if($abstract->link)
                <p style="font-size: 18px">
                    <a class="" target="_blank" href="{{$abstract->link}}">{{ $abstract->title }}</a>
                </p>
                @else
                <p style="font-size: 18px">
                    {{ $abstract->title }}
                </p>
                @endif
                <p>{{ $abstract->authors }}</p>
                <p><i>{{ $abstract->location }}</i> | {{ $abstract->city_state }} | {{ $abstract->date->format('M Y') }}</p>
                <p>@markdown($abstract->details)</p>
            </td>
        </tr>
        @endforeach
    </table>
@endsection

@section('footer-scripts')
    <script>
        $('.expand').click(function(){
            $("#abstract-"+$(this).data('id')).toggle();
        })
    </script>
@endsection

