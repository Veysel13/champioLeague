<h5 class="card-title">{{$key}}. Week Production Of Champion</h5>
<hr>
<table class="table table-striped">
    <tbody>
    @foreach($standingWeek->sortByDesc('winPercentage') as $team)
        <tr>
            <th scope="row">{{$team->teamName}}</th>
            <td>%{{$team->winPercentage}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
