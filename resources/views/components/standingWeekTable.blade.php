
<x-datatable :sort="false"
             :url="route('score.xhrWeekScore',array_merge(['week'=>$week],request()->all()))"
             >
    <tr>
        <th :key="teamName">Teams</th>
        <th :key="point">PTS</th>
        <th :key="played_match">P</th>
        <th :key="win">W</th>
        <th :key="draw">D</th>
        <th :key="lose">L</th>
        <th :key="goal_count">GD</th>
    </tr>
</x-datatable>
