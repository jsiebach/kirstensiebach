@if($page->use_callout)
<div class="row video-callout">
    <div class="col-xs-12">
        <div class="alert alert-success">
            {{ $page->callout }}
            <br><br><a href="{{ $page->callout_action }}" class="button">{{ $page->callout_action_text }}</a><br><br>
        </div>
    </div>
</div>
@endif
