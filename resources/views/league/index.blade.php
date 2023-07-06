<!DOCTYPE html>
<html>
<head>
    <title>Champions League</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link href="{{ asset('assets/toast-master/css/jquery.toast.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/datatables/datatables.min.css') }}"/>

    <script src="{{asset('assets/custom.css')}}"></script>

</head>

<body>

<div class="container-fluid mt-4 mb-4">

    <div class="row" style="background-color: #e2e5e7">
        <div class="col-md-12 mt-5">
            <h4 class="text-center">Champions League</h4>
        </div>
    </div>

    @foreach($standingWeeks->groupBy('week') as $key=>$standingWeek)
    <div class="row" style="background-color: #e2e5e7">
        <div class="col-md-8 mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card scoreCard">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7">
{{--                                    <x-standingWeekTable :week="$key"/>--}}
                                    @include('plugin.standingWeek',$standingWeek)
                                </div>
                                <div class="col-md-5">
                                    <h5 class="text-center card-title">Match Result</h5>
                                    <hr>
                                    <h6>{{$key}}.Week Match Result</h6>
                                    <table class="table table-striped">
                                        <tbody>
                                        @foreach($matchScores->where('week',$key)->groupBy('match_id') as $matchScore)
                                            <tr>
                                                <th>{{$matchScore[0]->teamName??''}}</th>
                                                <td>{{$matchScore[0]->score??''}}-{{$matchScore[1]->score??''}}</td>
                                                <th>{{$matchScore[1]->teamName??''}}</th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-5">
            <div class="card">
                <div class="card-body">
                    @include('plugin.standingWeekPercent',$standingWeek)
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<x-success/>
<x-error/>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript" src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/datatable.js?v=1.0.7') }}"></script>
<script src="{{ asset('assets/toast-master/js/jquery.toast.js') }}"></script>

<script src="{{asset('assets/custom.js')}}"></script>
<script src="{{asset('assets/app.js')}}"></script>
@stack('footer')
</body>
</html>
