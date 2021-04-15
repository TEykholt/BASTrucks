@include("incl/header")
        <h1 class="mt-2">KPIs</h1>
        <table class="table">
            <tr>
                <th>kpi_id</th>
                <th>CreatedAt</th>
                <th>AVresponseTime</th>
                <th>AVtotalResolutiontime</th>
                <th>timeServiceFactor</th>
                <th>AVuseFeedbackScore</th>
                <th>customerSatisfaction</th>
                <th>statusVerdelingIssues</th>
            </tr>

            @foreach($results as $result)
                <tr>
                    <td>{{$result['id']}}</td>
                    <td>{{$result['DateTime']}}</td>
                    <td>{{$result['AVresponseTime']}}</td>
                    <td>{{$result['AVtotalResolutiontime']}}</td>
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
