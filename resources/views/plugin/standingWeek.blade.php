
<h5 class="text-center card-title">League Table</h5>
<hr>

<table class="table table-striped scoreTable">
    <thead>
    <tr>
        <th scope="col">Teams</th>
        <th scope="col">PTS</th>
        <th scope="col">P</th>
        <th scope="col">W</th>
        <th scope="col">D</th>
        <th scope="col">L</th>
        <th scope="col">GD</th>
    </tr>
    </thead>
    <tbody>
    @foreach($standingWeek->sortByDesc('point') as $team)
        <tr>
            <th scope="row">{{$team->teamName}}</th>
            <td>{{$team->point}}</td>
            <td>{{$team->played_match}}</td>
            <td>{{$team->win}}</td>
            <td>{{$team->draw}}</td>
            <td>{{$team->lose}}</td>
            <td>{{$team->goal_count}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-6">
        <form action="{{route('score.play')}}" method="post" class="ajaxForm">
            <div class="form-error"></div>
            <input type="hidden" name="completeWeek" value="true">
            <button class="btn btn-dark btn-sm">Play All</button>
        </form>
    </div>
    <div class="col-6">
        <form action="{{route('score.play')}}" method="post" class="ajaxForm">
            <div class="form-error"></div>
            <button class="btn btn-dark btn-sm float-right">Next Week</button>
        </form>
    </div>
</div>
