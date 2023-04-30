<hr>
<div class="row">
    @if($i % 2 === 0)
        <div class="col-md-6 text-center">
            <img style="max-width: 100%; height: auto; max-height: 500px;" src="/storage/{{ $teamMember->profile_picture }}" alt="">
        </div>
    @endif
    <div class="col-md-6">
        <h3>{{ $teamMember->name }}</h3>
        <h5>{{ $teamMember->title }}</h5>
        <h5>{{ $teamMember->email }}</h5>
        @markdown($teamMember->bio)
    </div>
    @if($i % 2 === 1)
        <div class="col-md-6 text-center">
            <img style="max-width: 100%; height: auto; max-height: 500px;" src="/storage/{{ $teamMember->profile_picture }}" alt="">
        </div>
    @endif
</div>
