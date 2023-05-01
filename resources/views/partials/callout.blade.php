@if($page->add_call_to_action_banner)
<div class="row video-callout">
    <div class="col-xs-12">
        <div class="alert alert-success">
            {{ $page->call_to_action }}
            <br><br><a href="{{ $page->action_link }}" target="_blank" class="button">{{ $page->action_text }}</a><br><br>
        </div>
    </div>
</div>
@endif
