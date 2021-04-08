<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Dashboard</title>
        <link href="{{ URL::asset('css/app.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('css/KPIs.css')}}" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1 class="mt-2">KPIs</h1>
        <table class="table">
            <tr>
                <th>kpi_id</th>
                <th>DateTime</th>
                <th>AVresponseTime</th>
                <th>AVTotalResolution</th>
                <th>timeServiceFactor</th>
                <th>AVuseFeedbackScore</th>
                <th>customerSatisfaction</th>
                <th>statusVerdelingIssues</th>
            </tr>

            @foreach($results as $result)
                <tr>
                    <td>{{$result['kpi_id']}}</td>
                    <td>{{$result['DateTime']}}</td>
                    <td>{{$result['AVresponseTime']}}</td>
                    <td>{{$result['AVTotalResolution']}}</td>
                    <td>{{$result['timeServiceFactor']}}</td>
                    <td>{{$result['AVuseFeedbackScore']}}</td>
                    <td>{{$result['customerSatisfaction']}}</td>
                    <td>{{$result['statusVerdelingIssues']}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    </body>
</html>
